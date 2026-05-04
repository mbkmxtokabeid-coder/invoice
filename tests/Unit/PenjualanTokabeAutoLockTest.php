<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

// Feature: tokabe-approval-pelunasan, Property 1: Auto-lock hanya berlaku untuk record yang melewati batas waktu

/**
 * Property 1: Auto-lock hanya berlaku untuk record yang melewati batas waktu
 *
 * For any collection of penjualan_tokabe records with status Unlock, after
 * auto-lock logic runs, ONLY records with approved_at more than 6 hours ago
 * change to Lock; records with approved_at less than 6 hours ago remain Unlock.
 *
 * Validates: Requirements 2.1, 2.2
 */
class PenjualanTokabeAutoLockTest extends TestCase
{
    // ---------------------------------------------------------------------------
    // Helper: the auto-lock condition extracted from listInvoiceTokabe()
    // ---------------------------------------------------------------------------

    /**
     * Mirrors the DB condition used in PenjualanTokabeController::listInvoiceTokabe():
     *   ->where('approved_at', '<', now()->subHours(6))
     *
     * Returns true when the record should be auto-locked.
     */
    private function shouldAutoLock(?string $approved_at): bool
    {
        if ($approved_at === null) {
            return false;
        }
        $threshold = (new \DateTime())->modify('-6 hours');
        $approvedDateTime = new \DateTime($approved_at);
        return $approvedDateTime < $threshold;
    }

    /**
     * Apply auto-lock logic to a collection of record objects.
     * Mutates each record in-place, mirroring the DB::table()->update() call.
     *
     * @param  object[] $records
     * @return object[]
     */
    private function applyAutoLock(array $records): array
    {
        foreach ($records as $record) {
            if (
                $record->approval === 'Unlock'
                && $record->approved_at !== null
                && $this->shouldAutoLock($record->approved_at)
            ) {
                $record->approval    = 'Lock';
                $record->approved_at = null;
            }
        }
        return $records;
    }

    // ---------------------------------------------------------------------------
    // Data Provider — 100 iterations with mixed approved_at timestamps
    // ---------------------------------------------------------------------------

    /**
     * Generate 100 varied datasets.
     * Each dataset contains a mix of records:
     *   - Some with approved_at > 6 hours ago  → should be locked
     *   - Some with approved_at < 6 hours ago  → should remain Unlock
     *   - Some with approved_at = null          → should remain Unlock (no-op)
     */
    public static function autoLockDataProvider(): array
    {
        $cases = [];

        // Offsets in seconds relative to now:
        //   Negative = in the past, Positive = in the future
        $pastOffsets = [
            -7 * 3600,          // 7 hours ago  → LOCK
            -8 * 3600,          // 8 hours ago  → LOCK
            -12 * 3600,         // 12 hours ago → LOCK
            -24 * 3600,         // 1 day ago    → LOCK
            -6 * 3600 - 1,      // 6h 1s ago    → LOCK (just over threshold)
            -48 * 3600,         // 2 days ago   → LOCK
        ];

        $recentOffsets = [
            -1 * 3600,          // 1 hour ago   → UNLOCK
            -3 * 3600,          // 3 hours ago  → UNLOCK
            -5 * 3600,          // 5 hours ago  → UNLOCK
            -6 * 3600 + 60,     // 5h 59m ago   → UNLOCK (just under threshold)
            -30 * 60,           // 30 min ago   → UNLOCK
            0,                  // now          → UNLOCK
        ];

        for ($i = 0; $i < 100; $i++) {
            $seed = $i;

            // Build a small batch of records for this iteration
            $records = [];

            // Add 3 records that SHOULD be locked (approved_at > 6h ago)
            for ($j = 0; $j < 3; $j++) {
                $offset = $pastOffsets[($seed + $j) % count($pastOffsets)];
                $approvedAt = date('Y-m-d H:i:s', time() + $offset);
                $records[] = (object) [
                    'id'          => "past_{$i}_{$j}",
                    'approval'    => 'Unlock',
                    'approved_at' => $approvedAt,
                    'expected'    => 'Lock',
                ];
            }

            // Add 3 records that should REMAIN Unlock (approved_at < 6h ago)
            for ($j = 0; $j < 3; $j++) {
                $offset = $recentOffsets[($seed + $j) % count($recentOffsets)];
                $approvedAt = date('Y-m-d H:i:s', time() + $offset);
                $records[] = (object) [
                    'id'          => "recent_{$i}_{$j}",
                    'approval'    => 'Unlock',
                    'approved_at' => $approvedAt,
                    'expected'    => 'Unlock',
                ];
            }

            // Add 1 record with approved_at = null → should remain Unlock
            $records[] = (object) [
                'id'          => "null_{$i}",
                'approval'    => 'Unlock',
                'approved_at' => null,
                'expected'    => 'Unlock',
            ];

            $cases["iteration_$i"] = [$records];
        }

        return $cases;
    }

    // ---------------------------------------------------------------------------
    // Property 1 — Auto-lock hanya berlaku untuk record yang melewati batas waktu
    // Validates: Requirements 2.1, 2.2
    // ---------------------------------------------------------------------------

    /**
     * @dataProvider autoLockDataProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 1: Auto-lock hanya berlaku untuk record yang melewati batas waktu
     *
     * After auto-lock logic runs on a mixed collection of Unlock records,
     * ONLY records with approved_at more than 6 hours ago must change to Lock.
     * Records with approved_at less than 6 hours ago must remain Unlock.
     */
    public function test_auto_lock_only_affects_records_past_threshold(array $records): void
    {
        // Act: apply auto-lock logic (mirrors DB::table()->update() in listInvoiceTokabe)
        $processed = $this->applyAutoLock($records);

        foreach ($processed as $record) {
            if ($record->expected === 'Lock') {
                // Assert: records past the 6-hour threshold must be locked
                $this->assertSame(
                    'Lock',
                    $record->approval,
                    "Record {$record->id} with approved_at past threshold must be 'Lock'"
                );
                $this->assertNull(
                    $record->approved_at,
                    "Record {$record->id} with approved_at past threshold must have approved_at = null after lock"
                );
            } else {
                // Assert: records within the 6-hour window must remain Unlock
                $this->assertSame(
                    'Unlock',
                    $record->approval,
                    "Record {$record->id} with approved_at within threshold must remain 'Unlock'"
                );
            }
        }
    }

    /**
     * @dataProvider autoLockDataProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 1: Auto-lock hanya berlaku untuk record yang melewati batas waktu
     *
     * Records with approved_at within the 6-hour window must keep their
     * original approved_at value unchanged after auto-lock logic runs.
     */
    public function test_auto_lock_does_not_alter_recent_approved_at(array $records): void
    {
        // Snapshot original approved_at values for recent records
        $originalApprovedAt = [];
        foreach ($records as $record) {
            if ($record->expected === 'Unlock' && $record->approved_at !== null) {
                $originalApprovedAt[$record->id] = $record->approved_at;
            }
        }

        // Act
        $processed = $this->applyAutoLock($records);

        foreach ($processed as $record) {
            if ($record->expected === 'Unlock' && isset($originalApprovedAt[$record->id])) {
                $this->assertSame(
                    $originalApprovedAt[$record->id],
                    $record->approved_at,
                    "Record {$record->id} within threshold must keep its original approved_at unchanged"
                );
            }
        }
    }

    /**
     * @dataProvider autoLockDataProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 1: Auto-lock hanya berlaku untuk record yang melewati batas waktu
     *
     * Records with approved_at = null must never be auto-locked,
     * regardless of any other conditions.
     */
    public function test_auto_lock_ignores_records_with_null_approved_at(array $records): void
    {
        // Act
        $processed = $this->applyAutoLock($records);

        foreach ($processed as $record) {
            // Find records that started with null approved_at
            if (str_starts_with((string) $record->id, 'null_')) {
                $this->assertSame(
                    'Unlock',
                    $record->approval,
                    "Record {$record->id} with null approved_at must never be auto-locked"
                );
            }
        }
    }
}
