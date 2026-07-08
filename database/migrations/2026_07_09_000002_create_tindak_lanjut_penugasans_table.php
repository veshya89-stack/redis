<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tindak_lanjut_penugasans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penugasan_id')->constrained('penugasans')->cascadeOnDelete();
            $table->date('tanggal');
            $table->foreignId('division_id')->constrained('divisions');
            $table->text('deskripsi');
            $table->unsignedTinyInteger('progress')->default(0); // 0-100
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tindak_lanjut_penugasans');
    }
};
