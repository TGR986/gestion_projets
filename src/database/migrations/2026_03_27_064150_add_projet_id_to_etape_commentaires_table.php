<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('etape_commentaires', 'projet_id')) {
            Schema::table('etape_commentaires', function (Blueprint $table) {
                $table->unsignedInteger('projet_id')->nullable()->after('id');

                $table->foreign('projet_id')
                    ->references('id')
                    ->on('projets')
                    ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('etape_commentaires', 'projet_id')) {
            Schema::table('etape_commentaires', function (Blueprint $table) {
                $table->dropForeign(['projet_id']);
                $table->dropColumn('projet_id');
            });
        }
    }
};