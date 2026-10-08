<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Core Queue Manager Class
 */

namespace NodeX\Queue;

use NodeX\Redis\RedisClient;
use RuntimeException;
use Throwable;

class QueueManager
{
    private static ?RedisClient $redis = null;

    private static function getRedis(): RedisClient
    {
        if (self::$redis === null) {
            self::$redis = new RedisClient();
        }
        return self::$redis;
    }

    /**
     * Đẩy một Job vào Hàng đợi Queue
     */
    public static function push(ShouldQueue $job, string $queue = 'default'): bool
    {
        $payload = json_encode([
            'job' => get_class($job),
            'serialized' => serialize($job),
            'attempts' => 0,
            'pushed_at' => date('Y-m-d H:i:s')
        ], JSON_UNESCAPED_UNICODE);

        if ($payload === false) {
            throw new RuntimeException("Không thể serialize Job payload.");
        }

        try {
            $redis = self::getRedis();
            $queueKey = "nodex_queue:{$queue}";
            $redis->lpush($queueKey, $payload);
            return true;
        } catch (Throwable $e) {
            // Fallback lưu file tạm nếu Redis offline
            return self::fallbackPush($queue, $payload);
        }
    }

    /**
     * Fallback lưu Job vào file hệ thống trong storage/queue/ khi Redis offline
     */
    private static function fallbackPush(string $queue, string $payload): bool
    {
        $dir = dirname(__DIR__, 2) . "/storage/queue/{$queue}";
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $file = $dir . '/' . microtime(true) . '_' . uniqid() . '.json';
        return file_put_contents($file, $payload) !== false;
    }
}
