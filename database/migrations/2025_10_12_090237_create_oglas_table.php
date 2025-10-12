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
        Schema::create('oglas', function (Blueprint $table) {
            $table->id('oglasID');
            $table->date('datumKreiranja');
            $table->date('datumIsteka');
            $table->float('cenaIstaknutogOglasa');
            $table->unsignedBigInteger('voziloID');
            $table->unsignedBigInteger('korisnikID');
            $table->enum('statusOglasa', ['istaknutiOglas', 'standardniOglas', 'deaktiviranOglas', 'istekaoOglas']);
            $table->timestamps();
            
            $table->foreign('voziloID')->references('voziloID')->on('vozilo')->onDelete('cascade');
            $table->foreign('korisnikID')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oglas');
    }
};
