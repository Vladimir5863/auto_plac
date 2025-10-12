<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class uplata extends Model
{
    use HasFactory;

    protected $table = 'uplata';
    protected $primaryKey = 'uplataID';
    protected $fillable = ['korisnikID', 'oglasID', 'datumUplate', 'iznos'];
    protected $casts = [
        'datumUplate' => 'date',
        'iznos' => 'float'
    ];
    public function korisnik() {
        return $this->belongsTo(User::class, 'korisnikID');
    }

    public function oglas() {
        return $this->belongsTo(Oglas::class, 'oglasID');
    }
}
