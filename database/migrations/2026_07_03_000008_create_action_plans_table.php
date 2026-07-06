<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('action_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('strategic_initiative_id')->constrained('strategic_initiatives')->cascadeOnDelete();
            $table->unsignedInteger('urutan'); // nomor urut Action Plan dalam 1 inisiatif
            $table->string('nama_action_plan');
            $table->string('output')->nullable();
            $table->foreignId('pic_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('pic_pendukung')->nullable(); // VP/pihak pendukung lain, teks bebas seperti di Excel kolom E
            $table->string('stakeholder_eksternal')->nullable();
            $table->date('deadline')->nullable();
            $table->decimal('bobot', 5, 2)->default(0);
            $table->decimal('progress_percent', 5, 2)->default(0);
            $table->text('kendala')->nullable();
            $table->text('dukungan_direktur')->nullable();
            $table->text('update_terakhir')->nullable();
            $table->date('tgl_update')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('action_plans');
    }
};
