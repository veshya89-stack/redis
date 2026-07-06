<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('initiative_evp', function (Blueprint $table) {
            $table->foreignId('strategic_initiative_id')->constrained('strategic_initiatives')->cascadeOnDelete();
            $table->foreignId('evp_id')->constrained('evps')->cascadeOnDelete();
            $table->primary(['strategic_initiative_id', 'evp_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('initiative_evp');
    }
};
