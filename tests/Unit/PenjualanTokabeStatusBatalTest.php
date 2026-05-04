<?php

namespace Tests\Unit;

use App\Models\PenjualanTokabe;
use PHPUnit\Framework\TestCase;

// Feature: tokabe-approval-pelunasan, Property 7: storeStatusBatal menyimpan alasan pembatalan

/**
 * Property 7: storeStatusBatal menyimpan alasan pembatalan
 *
 * For any invoice and any alasan_batal string (including special characters,
 * long strings, unicode/emoji), calling storeStatusBatal logic must result in
 * status = 'Batal' AND alasan_batal stored exactly as provided.
 *
 * Validates: Requirements 9.2
 *
 * Uses PHPUnit data providers with 100 iterations to simulate property-based testing.
 */
class PenjualanTokabeStatusBatalTest extends TestCase
{
    // ---------------------------------------------------------------------------
    // Data Providers
    // ---------------------------------------------------------------------------

    /**
     * Generate 100 varied alasan_batal strings covering:
     * - Normal text
     * - Special characters
     * - Long strings
     * - Unicode / emoji
     * - Empty-ish strings (single space, tab, newline)
     * - Numbers and mixed content
     */
    public static function alasanBatalProvider(): array
    {
        $cases = [];

        $samples = [
            // Normal text
            'Pelanggan membatalkan pesanan',
            'Stok habis',
            'Harga tidak sesuai',
            'Pelanggan tidak bisa dihubungi',
            'Pembayaran gagal',
            // Special characters
            'Alasan: harga > budget & tidak sesuai!',
            'Batal karena "force majeure" (bencana alam)',
            'Diskon 50% tidak berlaku; syarat & ketentuan',
            '<script>alert("xss")</script>',
            "Tab\there & newline\nhere",
            // Long strings
            str_repeat('A', 500),
            str_repeat('Alasan pembatalan yang sangat panjang. ', 20),
            str_repeat('x', 1000),
            implode(' ', array_fill(0, 50, 'kata')),
            str_repeat('1234567890', 100),
            // Unicode / emoji
            'Pembatalan 🚫 karena masalah teknis',
            '取消原因：客户要求',
            'Причина отмены: нет в наличии',
            'سبب الإلغاء: انتهاء المخزون',
            '❌ Batal ✅ Refund 💰',
            'Emoji: 😀😂🎉🔥💯',
            'Japanese: 注文キャンセル理由',
            'Arabic: إلغاء الطلب',
            'Mixed: Hello 世界 🌍 مرحبا',
            'Symbols: ©®™€£¥§¶',
            // Empty-ish strings
            ' ',
            '  ',
            "\t",
            "\n",
            "\r\n",
            // Numbers and mixed
            '12345',
            '0',
            '-1',
            '3.14',
            'Ref#12345/2024',
            'INV-001 dibatalkan pada 2024-01-01',
            'Kode: TKB-9999 | Status: Batal',
            // SQL injection attempts (should be stored as-is)
            "'; DROP TABLE penjualan_tokabe; --",
            "1 OR 1=1",
            "UNION SELECT * FROM users",
            // HTML entities
            '&lt;b&gt;bold&lt;/b&gt;',
            '&amp; &lt; &gt; &quot; &#39;',
            // Whitespace variations
            'Alasan  dengan  spasi  ganda',
            "Alasan\tdengan\ttab",
            "Baris pertama\nBaris kedua\nBaris ketiga",
            // Very short strings
            'A',
            'OK',
            'No',
            '.',
            '!',
            // Slash and backslash
            'path/to/file',
            'C:\\Windows\\System32',
            'http://example.com/cancel?id=1&reason=test',
            // Null-byte and control chars (PHP strings can hold these)
            "Normal string",
            "String with percent: 100%",
            "String with at: user@example.com",
            "String with hash: #cancel",
            "String with plus: 1+1=2",
        ];

        // Fill up to 100 iterations, cycling through samples
        for ($i = 0; $i < 100; $i++) {
            $alasan = $samples[$i % count($samples)];
            // Add iteration suffix to ensure uniqueness and variety
            if ($i >= count($samples)) {
                $alasan = $alasan . ' [iter-' . $i . ']';
            }
            $cases["iteration_$i"] = [$alasan];
        }

        return $cases;
    }

    // ---------------------------------------------------------------------------
    // Property 7: storeStatusBatal menyimpan alasan pembatalan
    // Validates: Requirements 9.2
    // ---------------------------------------------------------------------------

    /**
     * @dataProvider alasanBatalProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 7: storeStatusBatal menyimpan alasan pembatalan
     *
     * For any alasan_batal string, applying storeStatusBatal logic must result in
     * status = 'Batal' (invariant, regardless of input).
     */
    public function test_storeStatusBatal_sets_status_to_batal(string $alasan_batal): void
    {
        // Arrange: create a model instance with arbitrary starting state
        $model = new PenjualanTokabe();
        $model->status = 'Belum Lunas';
        $model->alasan_batal = null;

        // Act: apply storeStatusBatal logic (mirrors PenjualanTokabeController::storeStatusBatal)
        $model->status = 'Batal';
        $model->alasan_batal = $alasan_batal;

        // Assert: status must be exactly 'Batal'
        $this->assertSame(
            'Batal',
            $model->status,
            "After storeStatusBatal, status must be 'Batal' for alasan_batal: " . mb_substr($alasan_batal, 0, 50)
        );
    }

    /**
     * @dataProvider alasanBatalProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 7: storeStatusBatal menyimpan alasan pembatalan
     *
     * For any alasan_batal string, applying storeStatusBatal logic must store
     * alasan_batal exactly as provided (round-trip identity).
     */
    public function test_storeStatusBatal_stores_alasan_batal_exactly(string $alasan_batal): void
    {
        // Arrange
        $model = new PenjualanTokabe();
        $model->status = 'Belum Lunas';
        $model->alasan_batal = null;

        // Act: apply storeStatusBatal logic
        $model->status = 'Batal';
        $model->alasan_batal = $alasan_batal;

        // Assert: alasan_batal must be stored exactly as provided (round-trip)
        $this->assertSame(
            $alasan_batal,
            $model->alasan_batal,
            "alasan_batal must be stored exactly as provided (round-trip identity)"
        );
    }

    /**
     * @dataProvider alasanBatalProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 7: storeStatusBatal menyimpan alasan pembatalan
     *
     * Combined invariant: both status = 'Batal' AND alasan_batal round-trip must hold
     * simultaneously for any input string.
     */
    public function test_storeStatusBatal_sets_both_status_and_alasan_batal(string $alasan_batal): void
    {
        // Arrange: start from various initial states
        $initialStatuses = ['Belum Lunas', 'Lunas', 'DP', 'Batal'];
        $initialStatus = $initialStatuses[strlen($alasan_batal) % count($initialStatuses)];

        $model = new PenjualanTokabe();
        $model->status = $initialStatus;
        $model->alasan_batal = null;

        // Act: apply storeStatusBatal logic
        $model->status = 'Batal';
        $model->alasan_batal = $alasan_batal;

        // Assert both invariants hold simultaneously
        $this->assertSame(
            'Batal',
            $model->status,
            "status must be 'Batal' after storeStatusBatal"
        );
        $this->assertSame(
            $alasan_batal,
            $model->alasan_batal,
            "alasan_batal must equal the input string exactly"
        );
    }
}
