<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_validation_permissions', function (Blueprint $table) {
            $table->id();

            $table->unsignedSmallInteger('role_id');
            $table->string('validation_type', 50);

            $table->timestamps();

            $table->unique(
                ['role_id', 'validation_type'],
                'uniq_role_validation_type'
            );

            $table->index('role_id', 'idx_rvp_role');
            $table->index('validation_type', 'idx_rvp_validation_type');

            $table->foreign('role_id', 'fk_rvp_role')
                ->references('id')
                ->on('roles')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('role_validation_permissions', function (Blueprint $table) {
            $table->dropForeign('fk_rvp_role');
        });

        Schema::dropIfExists('role_validation_permissions');
    }
};