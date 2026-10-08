<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * CLI Queue Worker Executor
 */

namespace NodeX\Queue;

use NodeX\Container\Container;
use NodeX\Redis\RedisClient;
use Throwable;

class Worker
{
    private RedisClient $redis;

    public function __construct(?RedisClient $redis = null)
    {
        $this->redis = $redis ?? new RedisClient();
    }

    /**
     * Chạy vòng lặp worker lắng nghe và xử lý các Job trong Queue
     */
    public function daemon(string $queue = 'default', int $sleep = 2, int $maxRetries = 3): void
    {
        $queueKey = "nodex_queue:{$queue}";
        echo "\033[36m⚡ NodeX Studio Queue Worker started listening on queue [{$queue}]...\033[0m\n";
        echo "Press Ctrl+C to exit.\n\n";

        while (true) {
            try {
                // Thử lấy từ Redis queue via BRPOP (blocking 3s)
                $pop = $this->redis->brpop($queueKey, 3);

                if ($pop !== null && !empty($pop['payload'])) {
                    $this->processJob($pop['payload'], $maxRetries);
                } else {
                    // Kiểm tra xem có file fallback nào trong storage/queue không
                    $this->processFileFallback($queue, $maxRetries);
                }
            } catch (Throwable $e) {
                echo "\033[31m[ERROR] Redis connection issue: {$e->getMessage()}\033[0m\n";
                // Xử lý fallback file nếu Redis mất kết nối
                $this->processFileFallback($queue, $maxRetries);
                sleep($sleep);
            }
        }
    }

    /**
     * Xử lý thực thi nội dung của Job
     */
    public function processJob(string $payloadStr, int $maxRetries = 3): void
    {
        $data = json_decode($payloadStr, true);

        if (!is_array($data) || empty($data['serialized'])) {
            echo "\033[31m[FAIL] Payload Job không đúng định dạng JSON.\033[0m\n";
            return;
        }

        $jobClass = $data['job'] ?? 'UnknownJob';
        $attempts = ($data['attempts'] ?? 0) + 1;

        echo "\033[33mProcessing Job:\033[0m {$jobClass} (Lần thử {$attempts}/{$maxRetries})...\n";

        try {
            $jobInstance = unserialize($data['serialized']);

            if (!$jobInstance instanceof ShouldQueue) {
                throw new \RuntimeException("Job class [{$jobClass}] không triển khai ShouldQueue interface.");
            }

            // Gọi phương thức handle với tự động tiêm Dependency thông qua DIC Container
            $container = Container::getInstance();
            $container->call([$jobInstance, 'handle']);

            echo "\033[32m✔ [SUCCESS] Job {$jobClass} xử lý hoàn tất thành công.\033[0m\n";
        } catch (Throwable $e) {
            echo "\033[31m✘ [FAILED] Job {$jobClass} thất bại: {$e->getMessage()}\033[0m\n";

            if ($attempts < $maxRetries) {
                // Đẩy lại vào queue thử lại
                $data['attempts'] = $attempts;
                $this->redis->lpush("nodex_queue:default", (string)json_encode($data));
            } else {
                $this->logFailedJob($jobClass, $payloadStr, $e);
            }
        }
    }

    /**
     * Xử lý quét các file job bị đẩy vào storage/queue/ khi Redis offline
     */
    private function processFileFallback(string $queue, int $maxRetries): void
    {
        $dir = dirname(__DIR__, 2) . "/storage/queue/{$queue}";
        if (!is_dir($dir)) {
            return;
        }

        $files = glob($dir . '/*.json');
        if (empty($files)) {
            return;
        }

        $file = $files[0];
        $payload = file_get_contents($file);
        @unlink($file);

        if ($payload !== false) {
            $this->processJob($payload, $maxRetries);
        }
    }

    /**
     * Ghi nhật ký các Job thất bại vượt quá số lần retry vào storage/logs/failed_jobs.log
     */
    private function logFailedJob(string $jobClass, string $payload, Throwable $e): void
    {
        $logDir = dirname(__DIR__, 2) . '/storage/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $logMessage = sprintf(
            "[%s] FAILED JOB: %s | Exception: %s in %s:%d\nPayload: %s\nTrace:\n%s\n%s\n",
            date('Y-m-d H:i:s'),
            $jobClass,
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $payload,
            $e->getTraceAsString(),
            str_repeat('-', 80)
        );

        file_put_contents($logDir . '/failed_jobs.log', $logMessage, FILE_APPEND);
        echo "\033[31m[FAILED_LOGGED] Đã ghi nhận log Job thất bại tại storage/logs/failed_jobs.log\033[0m\n";
    }
}
