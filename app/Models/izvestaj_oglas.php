<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class izvestaj_oglas extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'izvestaj_oglas';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The primary key associated with the table.
     *
     * @var array
     */
    protected $primaryKey = ['izvestajID', 'oglasID'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'izvestajID',
        'oglasID',
    ];

    /**
     * Get the izvestaj that owns the pivot.
     */
    public function izvestaj()
    {
        return $this->belongsTo(Izvestaj::class, 'izvestajID', 'izvestajID');
    }

    /**
     * Get the oglas that owns the pivot.
     */
    public function oglas()
    {
        return $this->belongsTo(Oglas::class, 'oglasID', 'oglasID');
    }
}