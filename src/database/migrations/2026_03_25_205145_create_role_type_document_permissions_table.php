<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_type_document_permissions', function (Blueprint $table) {
            $table->id();

            $table->unsignedSmallInteger('role_id');
            $table->unsignedSmallInteger('type_document_id');

            $table->boolean('can_view')->default(false);
            $table->boolean('can_upload')->default(false);
            $table->boolean('can_download')->default(false);

            $table->timestamps();

            $table->unique(
                ['role_id', 'type_document_id'],
                'uniq_role_type_document'
            );

            $table->index('role_id', 'idx_rtdp_role');
            $table->index('type_document_id', 'idx_rtdp_type_document');

            $table->foreign('role_id', 'fk_rtdp_role')
                ->references('id')
                ->on('roles')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('type_document_id', 'fk_rtdp_type_document')
                ->references('id')
                ->on('types_documents')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('role_type_document_permissions', function (Blueprint $table) {
            $table->dropForeign('fk_rtdp_role');
            $table->dropForeign('fk_rtdp_type_document');
        });

        Schema::dropIfExists('role_type_document_permissions');
    }
};