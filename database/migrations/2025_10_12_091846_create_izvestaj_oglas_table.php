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
        Schema::create('izvestaj_oglas', function (Blueprint $table) {
            $table->unsignedBigInteger('izvestajID');
            $table->unsignedBigInteger('oglasID');

            $table->primary(['izvestajID', 'oglasID']);

            $table->foreign('izvestajID')->references('izvestajID')->on('izvestaj')->onDelete('cascade');
            $table->foreign('oglasID')->references('oglasID')->on('oglas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izvestaj_oglas');
    }
};
