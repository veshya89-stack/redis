<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('action_plan_division', function (Blueprint $table) {
            $table->foreignId('action_plan_id')->constrained('action_plans')->cascadeOnDelete();
            $table->foreignId('division_id')->constrained('divisions')->cascadeOnDelete();
            $table->primary(['action_plan_id', 'division_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('action_plan_division');
    }
};
