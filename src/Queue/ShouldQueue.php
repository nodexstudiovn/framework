<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Interface đánh dấu class có thể đưa vào Queue Hàng Đợi
 */

namespace NodeX\Queue;

interface ShouldQueue
{
    /**
     * Phương thức thực thi công việc ngầm
     */
    public function handle(): void;
}
