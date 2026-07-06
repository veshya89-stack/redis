<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = ['judul', 'tanggal', 'catatan'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function strategicInitiatives()
    {
        return $this->belongsToMany(StrategicInitiative::class, 'initiative_meeting')
            ->withPivot('catatan_pembahasan')
            ->withTimestamps();
    }
}
