<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class izvestaj extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'izvestaj';
    protected $primaryKey = 'izvestajID';
    protected $fillable = ['brojUplata', 'datumOd', 'datumDo'];

    public function oglasi() {
        return $this->belongsToMany(Oglas::class, 'izvestaj_oglas', 'izvestajID', 'oglasID');
    }
}
