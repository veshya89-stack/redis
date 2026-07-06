<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'pic', 'viewer'])->default('pic')->after('email');
            $table->string('jabatan')->nullable()->after('role');
            $table->foreignId('division_id')->nullable()->after('jabatan')
                ->constrained('divisions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('division_id');
            $table->dropColumn(['role', 'jabatan']);
        });
    }
};
