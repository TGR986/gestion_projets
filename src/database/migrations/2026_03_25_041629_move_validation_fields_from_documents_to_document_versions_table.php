<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('document_versions', function (Blueprint $table) {
            $table->string('type_validation')->nullable();
            $table->text('commentaire_validation')->nullable();
            $table->foreignId('valide_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('date_validation')->nullable();
        });

        Schema::table('documents', function (Blueprint $table) {
            if (Schema::hasColumn('documents', 'valide_par')) {
                $table->dropConstrainedForeignId('valide_par');
            }

            $columnsToDrop = [];

            if (Schema::hasColumn('documents', 'type_validation')) {
                $columnsToDrop[] = 'type_validation';
            }

            if (Schema::hasColumn('documents', 'commentaire_validation')) {
                $columnsToDrop[] = 'commentaire_validation';
            }

            if (Schema::hasColumn('documents', 'date_validation')) {
                $columnsToDrop[] = 'date_validation';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('type_validation')->nullable();
            $table->text('commentaire_validation')->nullable();
            $table->foreignId('valide_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('date_validation')->nullable();
        });

        Schema::table('document_versions', function (Blueprint $table) {
            if (Schema::hasColumn('document_versions', 'valide_par')) {
                $table->dropConstrainedForeignId('valide_par');
            }

            $columnsToDrop = [];

            if (Schema::hasColumn('document_versions', 'type_validation')) {
                $columnsToDrop[] = 'type_validation';
            }

            if (Schema::hasColumn('document_versions', 'commentaire_validation')) {
                $columnsToDrop[] = 'commentaire_validation';
            }

            if (Schema::hasColumn('document_versions', 'date_validation')) {
                $columnsToDrop[] = 'date_validation';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};