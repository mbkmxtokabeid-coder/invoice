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
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_barang');
            $table->string('kode_barang');
            // Foreign Key ke tabel kateegori_item
            $table->unsignedBigInteger('kategori_id');
            $table->foreign('kategori_id')->references('id')->on('kategori_barang')->onUpdate('cascade')
                ->onDelete('cascade');

            $table->integer('stok');
            $table->string('harga_modal')->nullable();
            $table->string('harga_jual')->nullable();
            $table->dateTime('tgl_masuk')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
