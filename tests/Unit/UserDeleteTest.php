<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class UserDeleteTest extends TestCase
{
    /**
     * Check if a given role is allowed to see the "Hapus Karyawan" button
    * Mirrors Blade logic: strtolower($role) === 'pemilik'
     */
    private function shouldShowHapusKaryawan(string $role): bool
    {
        return strtolower($role) === 'pemilik';
    }

    /**
     * Check if a user can delete another user or themselves
     * Returns true if deletion is permitted, false if self-deletion is attempted
     */
    private function canDeleteUser(int $currentUserId, int $targetUserId): bool
    {
        return $currentUserId !== $targetUserId;
    }

    public function test_pemilik_can_see_hapus_karyawan_button(): void
    {
        $this->assertTrue($this->shouldShowHapusKaryawan('Pemilik'));
        $this->assertTrue($this->shouldShowHapusKaryawan('pemilik'));
    }

    public function test_non_pemilik_cannot_see_hapus_karyawan_button(): void
    {
        $nonPemilikRoles = ['Admin', 'AdminTKB', 'Produksi', 'Stockist', 'Magang', 'Karyawan'];
        foreach ($nonPemilikRoles as $role) {
            $this->assertFalse(
                $this->shouldShowHapusKaryawan($role),
                "Role {$role} should not be allowed to see Hapus Karyawan button"
            );
        }
    }

    public function test_self_deletion_is_prohibited(): void
    {
        $currentUserId = 1;
        $this->assertFalse($this->canDeleteUser($currentUserId, 1), "User should not be able to delete their own account");
        $this->assertTrue($this->canDeleteUser($currentUserId, 2), "User should be able to delete another user");
    }
}
