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
        Schema::table('spk', function (Blueprint $table) {
            $table->text('pekerjaan')->nullable()->change();
            $table->text('jenis_bahan')->nullable()->change();
            $table->text('ukuran')->nullable()->change();
            $table->text('lain')->nullable()->change();
            $table->text('ketebalan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spk', function (Blueprint $table) {
            $table->string('pekerjaan', 255)->nullable()->change();
            $table->string('jenis_bahan', 255)->nullable()->change();
            $table->string('ukuran', 255)->nullable()->change();
            $table->string('lain', 255)->nullable()->change();
            $table->string('ketebalan', 255)->nullable()->change();
        });
    }
};
