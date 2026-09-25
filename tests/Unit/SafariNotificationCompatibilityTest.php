<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class SafariNotificationCompatibilityTest extends TestCase
{
    public function test_notification_script_checks_browser_capabilities_before_initializing_firebase(): void
    {
        $script = file_get_contents(dirname(__DIR__, 2) . '/public/js/notif.js');

        $this->assertStringContainsString('window.isSecureContext', $script);
        $this->assertStringContainsString("'serviceWorker' in navigator", $script);
        $this->assertStringContainsString("'Notification' in window", $script);
        $this->assertStringContainsString("'PushManager' in window", $script);
        $this->assertStringContainsString('if (supportsMessaging)', $script);
    }

    public function test_notification_request_uses_csrf_token_and_current_application_path(): void
    {
        $script = file_get_contents(dirname(__DIR__, 2) . '/public/js/notif.js');

        $this->assertStringContainsString('meta[name="csrf-token"]', $script);
        $this->assertStringContainsString("window.location.origin + '/token-notif'", $script);
        $this->assertStringNotContainsString("'/invoice/token-notif'", $script);
    }
}
