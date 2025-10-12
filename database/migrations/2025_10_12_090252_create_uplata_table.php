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
        Schema::create('uplata', function (Blueprint $table) {
            $table->id('uplataID');
            $table->unsignedBigInteger('korisnikID');
            $table->unsignedBigInteger('oglasID');
            $table->date('datumUplate');
            $table->double('iznos');
            $table->timestamps();
            
            $table->foreign('korisnikID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('oglasID')->references('oglasID')->on('oglas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uplata');
    }
};
