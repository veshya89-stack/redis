<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('initiative_meeting', function (Blueprint $table) {
            $table->id();
            $table->foreignId('strategic_initiative_id')->constrained('strategic_initiatives')->cascadeOnDelete();
            $table->foreignId('meeting_id')->constrained('meetings')->cascadeOnDelete();
            $table->text('catatan_pembahasan')->nullable();
            $table->timestamps();
            $table->unique(['strategic_initiative_id', 'meeting_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('initiative_meeting');
    }
};
