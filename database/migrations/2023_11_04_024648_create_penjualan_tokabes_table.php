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
        Schema::create('penjualan_tokabe', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice');
            $table->foreign('invoice')->references('id')->on('invoice')->onUpdate('cascade')->onDelete('cascade');
            $table->string('nomor_invoice');
            $table->date('tgl_penjualan');
            $table->string('customer');
            $table->string('perusahaan')->nullable();
            $table->string('no_telepon');
            $table->unsignedBigInteger('admin');
            $table->foreign('admin')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('order_by', ['Walk in', 'Tokopedia', 'Shopee', 'WhatsApp/Online', 'Sales']);
            $table->string('nama_sales')->nullable();
            $table->dateTime('tgl_selesai');
            $table->integer('jumlah_item');
            $table->integer('dp')->nullable();
            $table->enum('jenis_pembayaran', ['Cash Lunas', 'Cash Belum Lunas', 'Transfer DP', 'Transfer Lunas', 'DP', 'PO']);
            $table->integer('total_harga');
            $table->enum('status', ['Lunas', 'Belum Lunas', 'Batal']);
            $table->integer('potongan')->nullable();
            $table->integer('diskon')->nullable();
            $table->integer('ppn')->nullable();
            $table->integer('total_pembayaran');
            $table->integer('sisa_pembayaran');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan_tokabes');
    }
};
