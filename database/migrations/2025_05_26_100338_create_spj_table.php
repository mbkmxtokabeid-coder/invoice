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
        Schema::create('spj', function (Blueprint $table) {
            $table->id();
            
            $table->string('nomor_spj');
            $table->string('perusahaan');
            $table->string('nama_pemberi_tugas');
            $table->string('nama_kurir');
            $table->date('tanggal_tugas');
            $table->time('waktu_berangkat');
            $table->string('tujuan');
            $table->decimal('biaya_bahan_bakar', 12, 2)->nullable();
            $table->float('jarak_tempuh')->nullable();
            $table->text('deskripsi_barang')->nullable();
            $table->text('keterangan_tambahan')->nullable();
            $table->text('deskripsi_tugas')->nullable();
            $table->time('jam_kembali')->nullable();
            $table->enum('status', ['Terbayar', 'Belum Bayar'])->default('Belum Bayar');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spj');
    }
};
