<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProjetEtapeIdNullable extends Migration
{
    public function up(): void
    {
        // 1. modifier type + nullable
        Schema::table('etape_commentaires', function (Blueprint $table) {
            $table->unsignedInteger('projet_etape_id')->nullable()->change();
        });

        // 2. ajouter la FK correcte
        Schema::table('etape_commentaires', function (Blueprint $table) {
            $table->foreign('projet_etape_id')
                ->references('id')
                ->on('projet_etapes')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('etape_commentaires', function (Blueprint $table) {
            $table->dropForeign(['projet_etape_id']);
        });

        Schema::table('etape_commentaires', function (Blueprint $table) {
            $table->unsignedInteger('projet_etape_id')->nullable(false)->change();
        });
    }
}