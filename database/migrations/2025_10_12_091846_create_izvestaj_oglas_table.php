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
            $table->id();
            $table->unsignedBigInteger('izvestajID');
            // For purchase entries (ad-based). Nullable to allow featured entries.
            $table->unsignedBigInteger('oglasID')->nullable();
            // For featured entries (user-based). Nullable to allow purchase entries.
            $table->unsignedBigInteger('korisnikID')->nullable();
            // Transaction type within this report row: 'featured' or 'purchase'
            $table->string('tip', 20);
            // Snapshot of transaction data
            $table->date('datumUplate')->nullable();
            $table->decimal('iznos', 12, 2)->nullable();

            // Soft deletes support
            $table->softDeletes();

            // Ensure logical uniqueness without forcing nullable FKs into a primary key
            // Include deleted_at so a soft-deleted row does not block re-inserts
            $table->unique(['izvestajID', 'tip', 'oglasID', 'korisnikID', 'deleted_at'], 'io_uniq_izv_tip_ogl_kor_del');

            $table->foreign('izvestajID')->references('izvestajID')->on('izvestaj')->onDelete('cascade');
            $table->foreign('oglasID')->references('oglasID')->on('oglas')->nullOnDelete();
            $table->foreign('korisnikID')->references('id')->on('users')->nullOnDelete();
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

