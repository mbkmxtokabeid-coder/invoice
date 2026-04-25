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
        Schema::create('penjualan_jasa_tokabe', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('barang_id');
            $table->foreign('barang_id')->references('id')->on('barang')->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unsignedBigInteger('penjualan_id');
            $table->foreign('penjualan_id')->references('id')->on('penjualan_tokabe')->onUpdate('cascade')
                ->onDelete('cascade');

            $table->text('deskripsi_item');
            $table->integer('qty');
            $table->string('satuan');
            $table->integer('harga');
            $table->integer('jumlah_harga');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan_jasa_tokabes');
    }
};
