<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class oglas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'oglas';
    protected $primaryKey = 'oglasID';
    protected $fillable = [
        'datumKreiranja', 'datumIsteka', 'cenaIstaknutogOglasa',
        'voziloID', 'korisnikID', 'statusOglasa'
    ];
    protected $casts = [
        'datumKreiranja' => 'date',
        'datumIsteka' => 'date',
        'cenaIstaknutogOglasa' => 'float'
    ];

    public function korisnik() {
        return $this->belongsTo(User::class, 'korisnikID');
    }

    public function vozilo() {
        return $this->belongsTo(Vozilo::class, 'voziloID');
    }

    public function uplate() {
        return $this->hasMany(Uplata::class, 'toOglasID');
    }

    public function izvestaji() {
        return $this->belongsToMany(Izvestaj::class, 'izvestaj_oglas', 'oglasID', 'izvestajID');
    }
}
