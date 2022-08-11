<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mouvements', function (Blueprint $table) {
            $table->id();
            $table->date('date_mouvement');
            $table->integer('quantite');
            $table->integer('prix');
            $table->foreignId('produit_id')->constrained('produits')->restrictOnDelete();
            $table->foreignId('type_id')->constrained('types')->restrictOnDelete();
            $table->tinyInteger('etat',false,true)->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mouvements');
    }
};
