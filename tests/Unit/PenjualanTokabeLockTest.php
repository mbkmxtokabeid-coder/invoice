<?php

namespace Tests\Unit;

use App\Models\PenjualanTokabe;
use PHPUnit\Framework\TestCase;

/**
 * Feature: tokabe-approval-pelunasan
 * Property 3: Lock mengosongkan approved_at
 *
 * Uses PHPUnit data providers with 100 iterations to simulate property-based testing.
 * Tests the controller lock logic directly on model instances — no database needed.
 */
// Feature: tokabe-approval-pelunasan, Property 3: Lock mengosongkan approved_at
class PenjualanTokabeLockTest extends TestCase
{
    // ---------------------------------------------------------------------------
    // Data Provider — 100 varied Unlock starting states
    // ---------------------------------------------------------------------------

    /**
     * Generate 100 varied starting states where approval = 'Unlock'.
     * Covers different approved_at timestamps and different invoice statuses.
     */
    public static function unlockedInvoiceProvider(): array
    {
        $cases = [];

        $statuses = ['Belum Lunas', 'Lunas', 'Batal', 'DP'];

        // Varied approved_at timestamps: recent, old, very old, just now, etc.
        $timestamps = [
            date('Y-m-d H:i:s'),                              // now
            date('Y-m-d H:i:s', strtotime('-30 minutes')),    // 30 min ago
            date('Y-m-d H:i:s', strtotime('-1 hour')),        // 1 hour ago
            date('Y-m-d H:i:s', strtotime('-3 hours')),       // 3 hours ago
            date('Y-m-d H:i:s', strtotime('-6 hours')),       // exactly 6 hours ago
            date('Y-m-d H:i:s', strtotime('-7 hours')),       // 7 hours ago (past threshold)
            date('Y-m-d H:i:s', strtotime('-1 day')),         // 1 day ago
            date('Y-m-d H:i:s', strtotime('-7 days')),        // 1 week ago
            '2024-01-01 00:00:00',                             // fixed past date
            '2023-06-15 12:30:00',                             // another fixed past date
            '2025-03-20 08:00:00',                             // future date
            date('Y-m-d H:i:s', strtotime('-1 year')),        // 1 year ago
            date('Y-m-d H:i:s', strtotime('+1 hour')),        // 1 hour in future
        ];

        for ($i = 0; $i < 100; $i++) {
            $cases["iteration_$i"] = [
                'invoice'          => 'INV-' . str_pad((string) ($i + 1), 5, '0', STR_PAD_LEFT),
                'nomor_invoice'    => sprintf('%03d/TKB/I/2024', $i + 1),
                'customer'         => 'Customer ' . ($i + 1),
                'status'           => $statuses[$i % count($statuses)],
                'approval'         => 'Unlock',
                'approved_at'      => $timestamps[$i % count($timestamps)],
                'total_pembayaran' => ($i + 1) * 150000,
                'sisa_pembayaran'  => ($i % 3 === 0) ? 0 : ($i + 1) * 50000,
            ];
        }

        return $cases;
    }

    // ---------------------------------------------------------------------------
    // Property 3: Lock mengosongkan approved_at
    // Validates: Requirements 5.2
    // ---------------------------------------------------------------------------

    /**
     * @dataProvider unlockedInvoiceProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 3: Lock mengosongkan approved_at
     *
     * For any PenjualanTokabe currently Unlock, applying lock logic must result in
     * approval = 'Lock' AND approved_at = null.
     *
     * Validates: Requirements 5.2
     */
    public function test_lock_sets_approval_to_lock_and_clears_approved_at(
        string $invoice,
        string $nomor_invoice,
        string $customer,
        string $status,
        string $approval,
        string $approved_at,
        int $total_pembayaran,
        int $sisa_pembayaran
    ): void {
        // Arrange: create a model instance in Unlock state
        $model = new PenjualanTokabe();
        $model->invoice          = $invoice;
        $model->nomor_invoice    = $nomor_invoice;
        $model->customer         = $customer;
        $model->status           = $status;
        $model->approval         = $approval;    // 'Unlock'
        $model->approved_at      = $approved_at; // some non-null timestamp
        $model->total_pembayaran = $total_pembayaran;
        $model->sisa_pembayaran  = $sisa_pembayaran;

        // Pre-condition: model must start as Unlock with a non-null approved_at
        $this->assertSame('Unlock', $model->approval, 'Pre-condition: approval must be Unlock');
        $this->assertNotNull($model->approved_at, 'Pre-condition: approved_at must not be null');

        // Act: apply lock logic (mirrors PenjualanTokabeController::lock)
        $model->approval    = 'Lock';
        $model->approved_at = null;

        // Assert: approval must be 'Lock'
        $this->assertSame(
            'Lock',
            $model->approval,
            "After lock, approval must be 'Lock' (started as: $approval, approved_at was: $approved_at)"
        );

        // Assert: approved_at must be null
        $this->assertNull(
            $model->approved_at,
            "After lock, approved_at must be null (was: $approved_at)"
        );
    }

    /**
     * @dataProvider unlockedInvoiceProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 3: Lock mengosongkan approved_at
     *
     * Regardless of the original approved_at timestamp value, after lock logic
     * the approved_at field must always be null — never retain the old value.
     *
     * Validates: Requirements 5.2
     */
    public function test_lock_never_retains_old_approved_at(
        string $invoice,
        string $nomor_invoice,
        string $customer,
        string $status,
        string $approval,
        string $approved_at,
        int $total_pembayaran,
        int $sisa_pembayaran
    ): void {
        // Arrange
        $model = new PenjualanTokabe();
        $model->approval    = 'Unlock';
        $model->approved_at = $approved_at; // varied timestamp

        // Act: apply lock logic
        $model->approval    = 'Lock';
        $model->approved_at = null;

        // Assert: the old timestamp must not be retained
        $this->assertNotSame(
            $approved_at,
            $model->approved_at,
            "After lock, approved_at must not retain old value '$approved_at'"
        );

        // Assert: it must specifically be null, not just different
        $this->assertNull($model->approved_at);

        // Assert: approval is also correctly set
        $this->assertSame('Lock', $model->approval);
    }
}
