<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('type_validation')->nullable();
            $table->text('commentaire_validation')->nullable();
            $table->unsignedBigInteger('valide_par')->nullable();
            $table->timestamp('date_validation')->nullable();

            $table->foreign('valide_par')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['valide_par']);
            $table->dropColumn([
                'type_validation',
                'commentaire_validation',
                'valide_par',
                'date_validation',
            ]);
        });
    }
};