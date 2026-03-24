<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etape_commentaires', function (Blueprint $table) {
            $table->id();

            // 🔥 IMPORTANT : INT (comme projet_etapes.id)
            $table->unsignedInteger('projet_etape_id');

            // 🔥 IMPORTANT : BIGINT (comme users.id)
            $table->unsignedBigInteger('user_id');

            $table->text('contenu');

            $table->timestamps();

            $table->foreign('projet_etape_id')
                ->references('id')
                ->on('projet_etapes')
                ->cascadeOnDelete();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etape_commentaires');
    }
};