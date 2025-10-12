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
        return $this->belongsTo(vozilo::class, 'voziloID');
    }

    public function uplate() {
        return $this->hasMany(uplata::class, 'toOglasID');
    }

    public function izvestaji() {
        return $this->belongsToMany(izvestaj::class, 'izvestaj_oglas', 'oglasID', 'izvestajID');
    }
}
