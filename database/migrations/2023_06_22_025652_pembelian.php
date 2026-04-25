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
        Schema::create('pembelian', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // $table->unsignedBigInteger('anggaran');
            $table->foreignId('anggaran')->constrained('budget')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('id_vendor')->references('id')->on('vendor')->onUpdate('cascade')->onDelete('cascade');
            $table->string('nomor_inv');
            $table->date('tanggal');
            $table->enum('status', ['Lunas', 'Belum Lunas']);
            $table->integer('terbayar');
            $table->integer('sisa');
            $table->integer('jumlah_harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian');
    }
};
