<?php

use Illuminate\Database\Eloquent\Scope;
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
        Schema::create('spk', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_invoice');
            $table->string('customer');
            $table->integer('jumlah');
            $table->enum('satuan', ['Set', 'Pcs', 'Kotak', 'Blok', 'Lbr', 'Rim', 'Und']);
            $table->string('jenis_bahan');
            $table->string('ketebalan');
            $table->string('ukuran');
            $table->string('lain');
            $table->enum('express', ['Y', 'N']);
            $table->dateTime('tgl_mulai');
            $table->dateTime('target_selesai');
            $table->string('timeline');
            $table->enum('status_spk', ['Selesai', 'Belum Selesai']);
            $table->enum('status_kerja', ['Selesai', 'Proses Design', 'Proses Produksi', 'Batal', 'Belum Diproses']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spk');
    }
};
