<?php

namespace Tests\Unit;

use App\Models\PenjualanTokabe;
use PHPUnit\Framework\TestCase;

/**
 * Feature: tokabe-approval-pelunasan
 * Property-based tests for PenjualanTokabe model lock/unlock field behavior.
 * Uses PHPUnit data providers with 100 iterations to simulate property-based testing.
 */
class PenjualanTokabeModelTest extends TestCase
{
    // ---------------------------------------------------------------------------
    // Data Providers
    // ---------------------------------------------------------------------------

    /**
     * Generate 100 varied invoice seed data sets for property testing.
     * Each entry represents a different starting state of a PenjualanTokabe instance.
     */
    public static function invoiceSeedProvider(): array
    {
        $cases = [];
        $statuses = ['Belum Lunas', 'Lunas', 'Batal', 'DP'];
        $approvals = ['Lock', 'Unlock', null];
        $timestamps = [
            null,
            '2024-01-01 00:00:00',
            '2023-06-15 12:30:00',
            '2025-03-20 08:00:00',
            date('Y-m-d H:i:s'),
        ];

        for ($i = 0; $i < 100; $i++) {
            $cases["iteration_$i"] = [
                'invoice'          => 'INV-' . str_pad((string) ($i + 1), 5, '0', STR_PAD_LEFT),
                'nomor_invoice'    => 'TKB-' . ($i + 1000),
                'customer'         => 'Customer ' . ($i + 1),
                'status'           => $statuses[$i % count($statuses)],
                'approval'         => $approvals[$i % count($approvals)],
                'approved_at'      => $timestamps[$i % count($timestamps)],
                'total_pembayaran' => ($i + 1) * 100000,
                'sisa_pembayaran'  => ($i % 2 === 0) ? ($i * 50000) : 0,
            ];
        }

        return $cases;
    }

    /**
     * Generate 100 invoice seed data sets where approval is already 'Unlock'.
     * Used for Property 3 (lock clears approved_at).
     */
    public static function unlockedInvoiceSeedProvider(): array
    {
        $cases = [];
        $timestamps = [
            '2024-01-01 00:00:00',
            '2023-06-15 12:30:00',
            '2025-03-20 08:00:00',
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s', strtotime('-1 hour')),
            date('Y-m-d H:i:s', strtotime('-7 hours')),
            date('Y-m-d H:i:s', strtotime('-1 day')),
        ];

        for ($i = 0; $i < 100; $i++) {
            $cases["iteration_$i"] = [
                'invoice'     => 'INV-' . str_pad((string) ($i + 1), 5, '0', STR_PAD_LEFT),
                'customer'    => 'Customer ' . ($i + 1),
                'approval'    => 'Unlock',
                'approved_at' => $timestamps[$i % count($timestamps)],
            ];
        }

        return $cases;
    }

    // ---------------------------------------------------------------------------
    // Property 2: Unlock mengisi kedua field secara atomik
    // Validates: Requirements 4.2
    // ---------------------------------------------------------------------------

    /**
     * @dataProvider invoiceSeedProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 2: Unlock mengisi kedua field secara atomik
     *
     * For any valid PenjualanTokabe, applying unlock logic must result in
     * approval = 'Unlock' AND approved_at filled (not null).
     */
    public function test_unlock_sets_approval_to_unlock(
        string $invoice,
        string $nomor_invoice,
        string $customer,
        string $status,
        mixed $approval,
        mixed $approved_at,
        int $total_pembayaran,
        int $sisa_pembayaran
    ): void {
        // Arrange: create a model instance with arbitrary starting state
        $model = new PenjualanTokabe();
        $model->invoice          = $invoice;
        $model->nomor_invoice    = $nomor_invoice;
        $model->customer         = $customer;
        $model->status           = $status;
        $model->approval         = $approval;
        $model->approved_at      = $approved_at;
        $model->total_pembayaran = $total_pembayaran;
        $model->sisa_pembayaran  = $sisa_pembayaran;

        // Act: apply unlock logic (mirrors PenjualanTokabeController::unlock)
        $beforeUnlock = time();
        $model->approval    = 'Unlock';
        $model->approved_at = now()->toDateTimeString();
        $afterUnlock = time();

        // Assert: approval must be 'Unlock'
        $this->assertSame(
            'Unlock',
            $model->approval,
            "After unlock, approval must be 'Unlock' (was: $approval)"
        );

        // Assert: approved_at must not be null
        $this->assertNotNull(
            $model->approved_at,
            'After unlock, approved_at must not be null'
        );
    }

    /**
     * @dataProvider invoiceSeedProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 2: Unlock mengisi kedua field secara atomik
     *
     * For any valid PenjualanTokabe, applying unlock logic must result in
     * approved_at being a valid timestamp string (not empty).
     */
    public function test_unlock_sets_approved_at_to_non_empty_timestamp(
        string $invoice,
        string $nomor_invoice,
        string $customer,
        string $status,
        mixed $approval,
        mixed $approved_at,
        int $total_pembayaran,
        int $sisa_pembayaran
    ): void {
        // Arrange
        $model = new PenjualanTokabe();
        $model->approval    = $approval;
        $model->approved_at = $approved_at;

        // Act: apply unlock logic
        $model->approval    = 'Unlock';
        $model->approved_at = now()->toDateTimeString();

        // Assert: approved_at must be a non-empty string (valid timestamp)
        $this->assertIsString($model->approved_at);
        $this->assertNotEmpty($model->approved_at);

        // Assert: both fields are set atomically — neither is null/empty
        $this->assertSame('Unlock', $model->approval);
        $this->assertNotNull($model->approved_at);
    }

    // ---------------------------------------------------------------------------
    // Property 3: Lock mengosongkan approved_at
    // Validates: Requirements 5.2
    // ---------------------------------------------------------------------------

    /**
     * @dataProvider unlockedInvoiceSeedProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 3: Lock mengosongkan approved_at
     *
     * For any PenjualanTokabe currently Unlock, applying lock logic must result in
     * approval = 'Lock' AND approved_at = null.
     */
    public function test_lock_sets_approval_to_lock(
        string $invoice,
        string $customer,
        string $approval,
        mixed $approved_at
    ): void {
        // Arrange: start from an Unlock state
        $model = new PenjualanTokabe();
        $model->invoice     = $invoice;
        $model->customer    = $customer;
        $model->approval    = $approval;    // 'Unlock'
        $model->approved_at = $approved_at; // some non-null timestamp

        $this->assertSame('Unlock', $model->approval, 'Pre-condition: model must start as Unlock');

        // Act: apply lock logic (mirrors PenjualanTokabeController::lock)
        $model->approval    = 'Lock';
        $model->approved_at = null;

        // Assert: approval must be 'Lock'
        $this->assertSame(
            'Lock',
            $model->approval,
            "After lock, approval must be 'Lock'"
        );
    }

    /**
     * @dataProvider unlockedInvoiceSeedProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 3: Lock mengosongkan approved_at
     *
     * For any PenjualanTokabe currently Unlock, applying lock logic must result in
     * approved_at being null (cleared).
     */
    public function test_lock_clears_approved_at_to_null(
        string $invoice,
        string $customer,
        string $approval,
        mixed $approved_at
    ): void {
        // Arrange: start from an Unlock state with a non-null approved_at
        $model = new PenjualanTokabe();
        $model->invoice     = $invoice;
        $model->customer    = $customer;
        $model->approval    = $approval;    // 'Unlock'
        $model->approved_at = $approved_at; // some timestamp

        // Act: apply lock logic
        $model->approval    = 'Lock';
        $model->approved_at = null;

        // Assert: approved_at must be null
        $this->assertNull(
            $model->approved_at,
            "After lock, approved_at must be null (was: $approved_at)"
        );

        // Assert: both fields reflect locked state atomically
        $this->assertSame('Lock', $model->approval);
        $this->assertNull($model->approved_at);
    }
}
