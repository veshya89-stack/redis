<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('strategic_initiatives', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique(); // INS-01, INS-02, ...
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->foreignId('pic_evp_id')->constrained('evps')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('strategic_initiatives');
    }
};
