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
        Schema::table('penjualan_tokabe', function (Blueprint $table) {
            $table->string('approval')->default('Lock')->nullable()->after('sisa_pembayaran');
            $table->timestamp('approved_at')->nullable()->after('approval');
            $table->text('alasan_batal')->nullable()->after('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjualan_tokabe', function (Blueprint $table) {
            $table->dropColumn(['approval', 'approved_at', 'alasan_batal']);
        });
    }
};
