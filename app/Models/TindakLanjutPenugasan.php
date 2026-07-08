<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TindakLanjutPenugasan extends Model
{
    use HasFactory;

    protected $fillable = [
        'penugasan_id',
        'tanggal',
        'division_id',
        'deskripsi',
        'progress',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function penugasan()
    {
        return $this->belongsTo(Penugasan::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
