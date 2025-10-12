<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Oglas;
use App\Models\Uplata;
use App\Models\Izvestaj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show admin dashboard
     */
    public function index()
    {
        // Get statistics
        $totalUsers = User::count();
        $totalAds = Oglas::count();
        $activeAds = Oglas::where('statusOglasa', '!=', 'deaktiviranOglas')->count();
        $featuredAds = Oglas::where('statusOglasa', 'istaknutiOglas')->count();
        // Profit: only featured payments count as revenue
        $totalPayments = Uplata::where('tip', 'featured')->sum('iznos');
        $monthlyPayments = Uplata::where('tip', 'featured')
            ->whereMonth('datumUplate', now()->month)
            ->whereYear('datumUplate', now()->year)
            ->sum('iznos');

        // Get recent activity
        $recentAds = Oglas::with(['vozilo', 'korisnik'])
            ->latest()
            ->limit(5)
            ->get();

        $recentPayments = Uplata::with(['korisnik', 'oglas.vozilo'])
            ->latest()
            ->limit(5)
            ->get();

        $recentUsers = User::latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalAds', 'activeAds', 'featuredAds',
            'totalPayments', 'monthlyPayments', 'recentAds',
            'recentPayments', 'recentUsers'
        ));
    }

    /**
     * Show all users
     */
    public function users(Request $request)
    {
        $status = $request->get('status', 'all'); // all | active | banned

        if ($status === 'active') {
            $query = User::query(); // withoutTrashed by default
        } elseif ($status === 'banned') {
            $query = User::onlyTrashed();
        } else {
            $query = User::withTrashed();
        }

        // Free text search (username/email/phone)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('korisnickoIme', 'like', "%{$q}%")
                    ->orWhere('eMail', 'like', "%{$q}%")
                    ->orWhere('brojTelefona', 'like', "%{$q}%");
            });
        }

        $users = $query->withCount(['oglasi', 'uplate'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users', compact('users', 'status'));
    }

    /**
     * Soft-delete (ban) user and soft-delete all their ads and vehicles
     */
    public function banUser($id)
    {
        $user = User::findOrFail($id);

        // Do not allow banning admins
        if ($user->tipKorisnika === 'admin') {
            return redirect()->back()->with('error', 'Nije dozvoljeno banovati administratora.');
        }

        // Soft delete user
        $user->delete();

        // Soft delete user's ads and their vehicles
        $ads = Oglas::with('vozilo')->where('korisnikID', $id)->get();
        foreach ($ads as $ad) {
            // Soft delete ad
            $ad->delete();
            // Soft delete vehicle if exists
            if ($ad->vozilo) {
                $ad->vozilo->delete();
            }
        }

        return redirect()->route('admin.users')
            ->with('success', 'Korisnik je banovan, a svi njegovi oglasi su deaktivirani.');
    }

    /**
     * Unban user (restore) and restore their ads and vehicles
     */
    public function unbanUser($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        // Restore user
        if ($user->trashed()) {
            $user->restore();
        }

        // Restore user's ads and vehicles
        $ads = Oglas::withTrashed()->with(['vozilo' => function($q){ $q->withTrashed(); }])
            ->where('korisnikID', $id)
            ->get();
        foreach ($ads as $ad) {
            if (method_exists($ad, 'restore') && $ad->trashed()) {
                $ad->restore();
            }
            if ($ad->vozilo && method_exists($ad->vozilo, 'restore') && $ad->vozilo->trashed()) {
                $ad->vozilo->restore();
            }
        }

        return redirect()->route('admin.users')
            ->with('success', 'Korisnik je uspešno unbanovan.');
    }

    /**
     * Show user details
     */
    public function showUser($id)
    {
        $user = User::with(['oglasi.vozilo', 'uplate.oglas.vozilo'])
            ->findOrFail($id);

        $userStats = [
            'totalAds' => $user->oglasi()->count(),
            'activeAds' => $user->oglasi()->where('statusOglasa', '!=', 'deaktiviranOglas')->count(),
            'featuredAds' => $user->oglasi()->where('statusOglasa', 'istaknutiOglas')->count(),
            'totalPayments' => $user->uplate()->sum('iznos'),
        ];

        return view('admin.user-details', compact('user', 'userStats'));
    }

    /**
     * Show all ads
     */
    public function ads(Request $request)
    {
        $query = Oglas::with(['vozilo', 'korisnik']);

        // Filters: status and date range (created_at)
        $status = $request->get('status', $request->get('statusOglasa', 'all'));
        if (in_array($status, ['istaknutiOglas','standardniOglas','deaktiviranOglas','istekaoOglas','prodatOglas'])) {
            $query->where('statusOglasa', $status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Free text search (brand/model/username)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->whereHas('vozilo', function($v) use ($q) {
                        $v->where('marka', 'like', "%{$q}%")
                          ->orWhere('model', 'like', "%{$q}%");
                    })
                    ->orWhereHas('korisnik', function($u) use ($q) {
                        $u->where('korisnickoIme', 'like', "%{$q}%");
                    });
            });
        }

        $ads = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->query());

        return view('admin.ads', compact('ads', 'status'));
    }

    /**
     * Delete ad
     */
    public function deleteAd($id)
    {
        $ad = Oglas::findOrFail($id);
        $ad->update(['statusOglasa' => 'deaktiviranOglas']);

        return redirect()->back()
            ->with('success', 'Oglas je uspešno deaktiviran!');
    }

    /**
     * Activate ad (set to standardniOglas)
     */
    public function activateAd($id)
    {
        $ad = Oglas::findOrFail($id);
        $ad->update(['statusOglasa' => 'standardniOglas', 'cenaIstaknutogOglasa' => 0]);

        return redirect()->back()
            ->with('success', 'Oglas je uspešno aktiviran!');
    }

    /**
     * Show reports
     */
    public function reports()
    {
        $query = Izvestaj::query();
        if (request()->filled('date_from')) {
            $query->whereDate('created_at', '>=', request('date_from'));
        }
        if (request()->filled('date_to')) {
            $query->whereDate('created_at', '<=', request('date_to'));
        }
        $reports = $query->orderBy('created_at', 'desc')->paginate(10)->appends(request()->query());

        return view('admin.reports', compact('reports'));
    }

    /**
     * Create new report
     */
    public function createReport()
    {
        return view('admin.create-report');
    }

    /**
     * Store new report
     */
    public function storeReport(Request $request)
    {
        $request->validate([
            'datumOd' => 'required|date',
            'datumDo' => 'required|date|after:datumOd',
        ], [
            'datumOd.required' => 'Datum od je obavezan.',
            'datumDo.required' => 'Datum do je obavezan.',
            'datumDo.after' => 'Datum do mora biti posle datuma od.',
        ]);

        // Payments in range, filtered by selected types (defaults to all)
        $types = $request->input('types', ['featured','purchase']);
        $payments = Uplata::with(['korisnik', 'oglas'])
            ->whereBetween('datumUplate', [$request->datumOd, $request->datumDo])
            ->whereIn('tip', $types)
            ->get();

        $countAll = $payments->count();
        $sumAll = $payments->sum('iznos');
        $sumFeatured = $payments->where('tip', 'featured')->sum('iznos');
        $sumPurchase = $payments->where('tip', 'purchase')->sum('iznos');

        // Store basic report row
        $report = Izvestaj::create([
            'brojUplata' => $countAll,
            'datumOd' => $request->datumOd,
            'datumDo' => $request->datumDo,
        ]);

        // Persist each payment snapshot into izvestaj_oglas
        foreach ($payments as $p) {
            DB::table('izvestaj_oglas')->insert([
                'izvestajID' => $report->izvestajID,
                'oglasID' => $p->toOglasID ?? null,
                'korisnikID' => $p->fromUserID ?? null,
                'tip' => $p->tip,
                'datumUplate' => $p->datumUplate,
                'iznos' => $p->iznos,
            ]);
        }

        return redirect()->route('admin.reports')
            ->with('success', 'Izveštaj je uspešno kreiran!');
    }

    /**
     * Show report details
     */
    public function showReport($id)
    {
        $report = Izvestaj::with(['oglasi.vozilo', 'oglasi.korisnik'])->findOrFail($id);

        // Load persisted entries from izvestaj_oglas for this report; allow type filtering via query
        $types = request()->input('types', ['featured','purchase']);
        $entries = DB::table('izvestaj_oglas as io')
            ->leftJoin('oglas as o', 'io.oglasID', '=', 'o.oglasID')
            ->leftJoin('users as u', 'io.korisnikID', '=', 'u.id')
            ->leftJoin('vozilo as v', 'o.voziloID', '=', 'v.voziloID')
            ->select(
                'io.*',
                'o.oglasID as ad_id',
                'o.voziloID',
                'o.statusOglasa',
                'o.cenaIstaknutogOglasa',
                'u.korisnickoIme',
                'v.marka as vozilo_marka',
                'v.model as vozilo_model'
            )
            ->where('io.izvestajID', $report->izvestajID)
            ->whereIn('io.tip', $types)
            ->orderBy('io.datumUplate', 'desc')
            ->get();

        $summary = [
            'countAll' => $entries->count(),
            'sumAll' => $entries->sum('iznos'),
            'sumFeatured' => $entries->where('tip', 'featured')->sum('iznos'),
            'sumPurchase' => $entries->where('tip', 'purchase')->sum('iznos'),
        ];

        // Ads table: distinct ads present in entries
        $adIds = $entries->pluck('oglasID')->filter()->unique()->values();
        $adsInReport = Oglas::with(['vozilo','korisnik'])->whereIn('oglasID', $adIds)->get();

        return view('admin.report-details', compact('report', 'summary', 'entries', 'adsInReport'));
    }

    /**
     * Delete report (will also remove pivot rows via FK cascade)
     */
    public function deleteReport($id)
    {
        $report = Izvestaj::findOrFail($id);
        $report->delete();

        return redirect()->route('admin.reports')
            ->with('success', 'Izveštaj je uspešno obrisan.');
    }

    /**
     * Get monthly statistics
     */
    public function monthlyStats()
    {
        // Monthly revenue = sum of all featured ad payments (isticanja oglasa)
        $raw = DB::table('uplata')
            ->select(
                DB::raw('DATE_FORMAT(datumUplate, "%Y-%m-01") as ym'),
                DB::raw('SUM(iznos) as total_amount')
            )
            ->where('tip', 'featured')
            ->groupBy('ym')
            ->get()
            ->pluck('total_amount', 'ym');

        // Build last 12 months with zero fill
        $months = [];
        $labels = [];
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $dt = now()->startOfMonth()->subMonths($i);
            $ym = $dt->format('Y-m-01');
            $months[] = $ym;
            // Localized short month label (e.g., Jan) - keep simple array if locale not set
            $labels[] = $dt->format('M');
            $data[] = (float) ($raw[$ym] ?? 0);
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }

    /**
     * Get quarterly statistics
     */
    public function quarterlyStats()
    {
        // Quarterly profit stats based on featured payments only
        $quarterlyStats = DB::table('uplata')
            ->select(
                DB::raw('YEAR(datumUplate) as year'),
                DB::raw('QUARTER(datumUplate) as quarter'),
                DB::raw('COUNT(*) as payment_count'),
                DB::raw('SUM(iznos) as total_amount')
            )
            ->where('tip', 'featured')
            ->groupBy('year', 'quarter')
            ->orderBy('year', 'desc')
            ->orderBy('quarter', 'desc')
            ->limit(8)
            ->get();

        return response()->json($quarterlyStats);
    }

    /**
     * Get yearly statistics
     */
    public function yearlyStats()
    {
        // Yearly profit stats based on featured payments only
        $yearlyStats = DB::table('uplata')
            ->select(
                DB::raw('YEAR(datumUplate) as year'),
                DB::raw('COUNT(*) as payment_count'),
                DB::raw('SUM(iznos) as total_amount')
            )
            ->where('tip', 'featured')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->limit(5)
            ->get();

        return response()->json($yearlyStats);
    }
}