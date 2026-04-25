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
        Schema::create('spb', function (Blueprint $table) {

            $table->id();
            $table->string('nama_spb');
            $table->string('customer');
            $table->string('perusahaan');
            $table->string('nomor_telepon');
            $table->integer('jumlah_item');
            $table->enum('status', ['Sudah diantar', 'Belum diantar']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spb');
    }
};
