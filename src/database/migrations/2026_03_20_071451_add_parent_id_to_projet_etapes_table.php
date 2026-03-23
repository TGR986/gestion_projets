<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projet_etapes', function (Blueprint $table) {

            $table->unsignedInteger('parent_id')
                ->nullable()
                ->after('projet_id');

            $table->foreign('parent_id')
                ->references('id')
                ->on('projet_etapes')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('projet_etapes', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};