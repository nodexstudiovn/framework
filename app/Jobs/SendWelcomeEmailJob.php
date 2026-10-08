<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Job Example: SendWelcomeEmailJob
 */

namespace App\Jobs;

use NodeX\Queue\ShouldQueue;

class SendWelcomeEmailJob implements ShouldQueue
{
    public function __construct(
        public readonly string $email,
        public readonly string $name
    ) {}

    public function handle(): void
    {
        // Giả lập logic gửi Email chào mừng ngầm
        echo "📧 [EMAIL QUEUE] Đang gửi email chào mừng tới thành viên {$this->name} ({$this->email})...\n";
        sleep(1);
        echo "✅ [EMAIL QUEUE] Đã gửi email chào mừng thành công tới {$this->email}.\n";
    }
}
