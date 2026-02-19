<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('recipe_tag', function (Blueprint $table) {
            // Supprimer la colonne 'id' et la clé primaire ancienne
            $table->dropPrimary();
            $table->dropColumn('id');
            // Ajouter la clé primaire composée
            $table->primary(['recipe_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::table('recipe_tag', function (Blueprint $table) {
            // Revert les changements
            $table->dropPrimary(['recipe_id', 'tag_id']);
            $table->id()->first();
        });
    }
};
