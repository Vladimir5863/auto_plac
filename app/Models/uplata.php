<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class uplata extends Model
{
    use HasFactory;

    protected $table = 'uplata';
    protected $primaryKey = 'uplataID';
    protected $fillable = ['fromUserID', 'toUserID', 'toOglasID', 'datumUplate', 'iznos', 'tip'];
    protected $casts = [
        'datumUplate' => 'date',
        'iznos' => 'float'
    ];
    public function oglas() { return $this->belongsTo(Oglas::class, 'toOglasID'); }

    // Backward-compatibility for existing views using $payment->korisnik
    public function korisnik() { return $this->belongsTo(User::class, 'fromUserID'); }
}
