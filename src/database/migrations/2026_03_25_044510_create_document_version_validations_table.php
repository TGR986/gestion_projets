<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_version_validations', function (Blueprint $table) {
            $table->id();

            // ⚠️ IMPORTANT : même type que document_versions.id (INT UNSIGNED)
            $table->unsignedInteger('document_version_id');

            $table->string('type_validation');
            $table->text('commentaire')->nullable();

            // users.id = BIGINT → donc OK en BIGINT
            $table->unsignedBigInteger('valide_par')->nullable();

            $table->timestamp('date_validation')->nullable();

            $table->timestamps();

            // Empêche doublon par type
            $table->unique(
                ['document_version_id', 'type_validation'],
                'doc_version_type_validation_unique'
            );

            // FK version
            $table->foreign('document_version_id')
                ->references('id')
                ->on('document_versions')
                ->onDelete('cascade');

            // FK user
            $table->foreign('valide_par')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_version_validations');
    }
};