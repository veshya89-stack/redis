<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evp extends Model
{
    protected $fillable = ['kode', 'nama'];

    public function strategicInitiatives()
    {
        return $this->hasMany(StrategicInitiative::class, 'pic_evp_id');
    }

    public function initiativesTerkait()
    {
        return $this->belongsToMany(StrategicInitiative::class, 'initiative_evp');
    }
}
