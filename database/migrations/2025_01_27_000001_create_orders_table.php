<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        // Pour PostgreSQL, il faut d'abord supprimer la contrainte de clé étrangère si elle existe
        $table->dropForeign(['user_id']);
        $table->unsignedBigInteger('user_id')->nullable()->change();
        // Puis éventuellement la recréer
        $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
        $table->unsignedBigInteger('user_id')->nullable(false)->change();
        $table->foreign('user_id')->references('id')->on('users');
    });
}
}