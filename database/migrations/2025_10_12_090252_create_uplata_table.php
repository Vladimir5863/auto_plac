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
            // sekundarni ključevi (svi nullable): ko plaća, kome ide, za koji oglas
            $table->unsignedBigInteger('fromUserID')->nullable();
            $table->unsignedBigInteger('toUserID')->nullable();
            $table->unsignedBigInteger('toOglasID')->nullable();

            $table->dateTime('datumUplate');
            $table->decimal('iznos', 12, 2);
            // tip: wallet (dopuna novčanika), featured (isticanje oglasa), purchase (kupovina vozila)
            $table->string('tip', 20)->default('wallet');

            $table->timestamps();

            // indeksi
            $table->index(['datumUplate']);
            $table->index(['tip']);

            // spoljašnji ključevi
            $table->foreign('fromUserID')->references('id')->on('users')->onDelete('set null');
            $table->foreign('toUserID')->references('id')->on('users')->onDelete('set null');
            $table->foreign('toOglasID')->references('oglasID')->on('oglas')->onDelete('set null');
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
