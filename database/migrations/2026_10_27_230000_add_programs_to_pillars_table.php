<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('pillars', 'programs')) {
            return;
        }

        Schema::table('pillars', function (Blueprint $table) {
            $table->json('programs')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('pillars', function (Blueprint $table) {
            $table->dropColumn('programs');
        });
    }
};
