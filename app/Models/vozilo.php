<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class vozilo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'vozilo';
    protected $primaryKey = 'voziloID';
    protected $fillable = [
        'marka', 'model', 'godinaProizvodnje', 'cena', 'tipGoriva', 'kilometraza',
        'tipKaroserije', 'snagaMotoraKW', 'stanje', 'opis', 'slike', 'lokacija',
        'klima', 'tipMenjaca', 'ostecenje', 'euroNorma', 'kubikaza'
    ];

    protected $casts = [
        'slike' => 'array',
        'ostecenje' => 'boolean'
    ];

    public function oglas() {
        return $this->hasOne(Oglas::class, 'voziloID');
    }
}
