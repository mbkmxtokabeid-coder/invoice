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
        Schema::create('barang_spb', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('spb');
            $table->foreign('spb')->references('id')->on('spb')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('barang_id');
            $table->foreign('barang_id')->references('id')->on('kategori_barang')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('deskripsi');
            $table->string('satuan');
            $table->integer('qty');
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_spb');
    }
};
