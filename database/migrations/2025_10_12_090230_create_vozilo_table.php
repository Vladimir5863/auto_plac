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
        Schema::create('vozilo', function (Blueprint $table) {
            $table->id('voziloID');
            $table->string('marka');
            $table->string('model');
            $table->integer('godinaProizvodnje');
            $table->double('cena');
            $table->string('tipGoriva');
            $table->string('kilometraza');
            $table->string('tipKaroserije');
            $table->double('snagaMotoraKW');
            $table->string('stanje');
            $table->text('opis');
            $table->json('slike');
            $table->string('lokacija');
            $table->string('klima');
            $table->string('tipMenjaca');
            $table->boolean('ostecenje');
            $table->string('euroNorma');
            $table->integer('kubikaza');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vozilo');
    }
};
