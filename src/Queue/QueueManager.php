<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Core Queue Manager Class
 */

namespace NodeX\Queue;

use NodeX\Container\Container;
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
     * Lấy driver hàng đợi hiện tại từ cấu hình môi trường (.env)
     * Hỗ trợ: 'auto' (mặc định), 'redis', 'file', 'sync'
     */
    public static function getDriver(): string
    {
        return strtolower($_ENV['QUEUE_CONNECTION'] ?? $_ENV['QUEUE_DRIVER'] ?? 'auto');
    }

    /**
     * Đẩy một Job vào Hàng đợi Queue
     */
    public static function push(ShouldQueue $job, string $queue = 'default'): bool
    {
        $driver = self::getDriver();

        // 1. Chế độ thực thi đồng bộ ngay lập tức (Sync Mode)
        if ($driver === 'sync') {
            try {
                $container = Container::getInstance();
                $container->call([$job, 'handle']);
                return true;
            } catch (Throwable $e) {
                error_log("Queue Sync Job failed: " . $e->getMessage());
                return false;
            }
        }

        $payload = json_encode([
            'job' => get_class($job),
            'serialized' => serialize($job),
            'attempts' => 0,
            'pushed_at' => date('Y-m-d H:i:s')
        ], JSON_UNESCAPED_UNICODE);

        if ($payload === false) {
            throw new RuntimeException("Không thể serialize Job payload.");
        }

        // 2. Chế độ File Driver trực tiếp
        if ($driver === 'file') {
            return self::fallbackPush($queue, $payload);
        }

        // 3. Chế độ Redis hoặc Auto
        try {
            $redis = self::getRedis();
            // Nếu là auto mà redis không sẵn sàng, chuyển sang lưu file ngay lập tức
            if ($driver === 'auto' && !$redis->isAvailable(0.3)) {
                return self::fallbackPush($queue, $payload);
            }

            $queueKey = "nodex_queue:{$queue}";
            $redis->lpush($queueKey, $payload);
            return true;
        } catch (Throwable) {
            // Tự động fallback lưu file tạm nếu Redis offline
            return self::fallbackPush($queue, $payload);
        }
    }

    /**
     * Đẩy lại payload thô vào hàng đợi (dùng cho cơ chế Retry)
     */
    public static function pushPayload(string $payload, string $queue = 'default'): bool
    {
        $driver = self::getDriver();

        if ($driver === 'file') {
            return self::fallbackPush($queue, $payload);
        }

        try {
            $redis = self::getRedis();
            if ($driver === 'auto' && !$redis->isAvailable(0.3)) {
                return self::fallbackPush($queue, $payload);
            }

            $queueKey = "nodex_queue:{$queue}";
            $redis->lpush($queueKey, $payload);
            return true;
        } catch (Throwable) {
            return self::fallbackPush($queue, $payload);
        }
    }

    /**
     * Fallback lưu Job vào file hệ thống trong storage/queue/ khi Redis offline hoặc chạy chế độ File
     */
    public static function fallbackPush(string $queue, string $payload): bool
    {
        $dir = dirname(__DIR__, 2) . "/storage/queue/{$queue}";
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $file = $dir . '/' . microtime(true) . '_' . uniqid() . '.json';
        return file_put_contents($file, $payload) !== false;
    }
}
