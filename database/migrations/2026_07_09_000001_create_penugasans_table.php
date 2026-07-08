<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penugasans', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->text('isu_strategis');
            $table->string('pic'); // teks bebas, sesuai keputusan (fleksibel kalau ganti personel)
            $table->date('tanggal_mulai');
            $table->date('target_selesai')->nullable();
            $table->enum('status', [
                'belum_mulai',
                'on_progress',
                'need_attention',
                'critical',
                'selesai',
            ])->default('belum_mulai');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penugasans');
    }
};
