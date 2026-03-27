<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'prenom')) {
                $table->string('prenom')->nullable()->after('name');
            }

            if (! Schema::hasColumn('users', 'fonction')) {
                $table->string('fonction')->nullable()->after('prenom');
            }

            if (! Schema::hasColumn('users', 'structure')) {
                $table->string('structure')->nullable()->after('fonction');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'structure')) {
                $table->dropColumn('structure');
            }

            if (Schema::hasColumn('users', 'fonction')) {
                $table->dropColumn('fonction');
            }

            if (Schema::hasColumn('users', 'prenom')) {
                $table->dropColumn('prenom');
            }
        });
    }
};