<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * CLI Queue Worker Executor with Graceful Shutdown & Exponential Backoff
 */

namespace NodeX\Queue;

use NodeX\Container\Container;
use NodeX\Redis\RedisClient;
use RuntimeException;
use Throwable;

class Worker
{
    private RedisClient $redis;
    private bool $shouldQuit = false;
    private bool $isProcessing = false;

    public function __construct(?RedisClient $redis = null)
    {
        $this->redis = $redis ?? new RedisClient();
        $this->listenForSignals();
    }

    /**
     * Đăng ký bộ lắng nghe tín hiệu tắt hệ thống (Graceful Shutdown)
     */
    private function listenForSignals(): void
    {
        // 1. Hệ thống Linux / Unix thông qua PCNTL
        if (extension_loaded('pcntl') && function_exists('pcntl_async_signals')) {
            pcntl_async_signals(true);
            pcntl_signal(SIGINT, [$this, 'handleShutdownSignal']);
            pcntl_signal(SIGTERM, [$this, 'handleShutdownSignal']);
            pcntl_signal(SIGHUP, [$this, 'handleShutdownSignal']);
        }

        // 2. Hệ điều hành Windows (Ctrl+C / Ctrl+Break Event)
        if (function_exists('sapi_windows_set_ctrl_handler')) {
            sapi_windows_set_ctrl_handler(function (int $event): bool {
                if ($event === PHP_WINDOWS_EVENT_CTRL_C || $event === PHP_WINDOWS_EVENT_CTRL_BREAK) {
                    $this->handleShutdownSignal();
                    return true;
                }
                return false;
            });
        }
    }

    public function handleShutdownSignal(): void
    {
        if ($this->shouldQuit) {
            echo "\n\033[31m[FORCED STOP] Ngắt Worker ngay lập tức.\033[0m\n";
            exit(1);
        }

        $this->shouldQuit = true;

        if ($this->isProcessing) {
            echo "\n\033[33m⏳ [GRACEFUL SHUTDOWN] Đang chờ Job hiện tại hoàn tất trước khi dừng an toàn...\033[0m\n";
        } else {
            echo "\n\033[32m✔ [SHUTDOWN] Đã dừng Queue Worker an toàn.\033[0m\n";
            exit(0);
        }
    }

    /**
     * Chạy vòng lặp worker lắng nghe và xử lý các Job trong Queue
     */
    public function daemon(string $queue = 'default', int $sleep = 2, int $maxRetries = 3): void
    {
        $queueKey = "nodex_queue:{$queue}";
        $driver = QueueManager::getDriver();

        echo "\033[36m⚡ NodeX Studio Queue Worker started listening on queue [{$queue}]...\033[0m\n";

        // 1. Chế độ đồng bộ
        if ($driver === 'sync') {
            echo "\033[33mℹ Chế độ QUEUE_CONNECTION=sync đang được bật. Mọi Job sẽ được thực thi đồng bộ ngay khi push mà không cần worker chạy ngầm.\033[0m\n";
            echo "Press Ctrl+C to exit.\n";
            return;
        }

        // 2. Chế độ File Driver thuần túy
        if ($driver === 'file') {
            echo "\033[32m📂 Driver: [FILE QUEUE] (Giám sát thư mục: storage/queue/{$queue}/)\033[0m\n";
            echo "Press Ctrl+C to exit.\n\n";

            while (!$this->shouldQuit) {
                $this->processFileFallback($queue, $maxRetries);
                sleep($sleep);
            }
            return;
        }

        // 3. Chế độ Redis hoặc Auto
        $isRedisOnline = $this->redis->isAvailable(0.5);

        if ($isRedisOnline) {
            echo "\033[32m🟢 Kết nối Redis Server (127.0.0.1:6379) thành công. Driver: [REDIS]\033[0m\n";
        } else {
            echo "\033[33m🟡 [THÔNG BÁO] Không thể kết nối Redis Server tại tcp://127.0.0.1:6379 (Chưa khởi chạy redis-server).\033[0m\n";
            echo "\033[32m🔄 Worker tự động kích hoạt [FILE QUEUE DRIVER] (lưu trữ và xử lý tại storage/queue/{$queue}/).\033[0m\n";
            echo "\033[90m💡 Mẹo: Khởi chạy redis-server để đạt hiệu năng cao nhất, hoặc đặt QUEUE_CONNECTION=file trong file .env\033[0m\n";
        }

        echo "Press Ctrl+C to exit.\n\n";

        $lastRedisCheck = time();

        while (!$this->shouldQuit) {
            if ($isRedisOnline) {
                try {
                    // Thử lấy từ Redis queue via BRPOP (blocking 3s)
                    $pop = $this->redis->brpop($queueKey, 3);

                    if ($pop !== null && !empty($pop['payload'])) {
                        $this->processJob($pop['payload'], $maxRetries, $queue);
                    } else {
                        // Kiểm tra xem có file fallback tồn đọng nào không
                        $this->processFileFallback($queue, $maxRetries);
                    }
                } catch (Throwable $e) {
                    $isRedisOnline = false;
                    $lastRedisCheck = time();
                    echo "\033[31m[CẢNH BÁO] Mất kết nối Redis Server: {$e->getMessage()}\033[0m\n";
                    echo "\033[33m🔄 Tạm thời chuyển sang chế độ File Queue Driver...\033[0m\n";
                    $this->processFileFallback($queue, $maxRetries);
                    sleep($sleep);
                }
            } else {
                // Đang trong chế độ File Driver khi Redis offline
                $this->processFileFallback($queue, $maxRetries);
                sleep($sleep);

                // Định kỳ mỗi 30 giây kiểm tra xem Redis đã hoạt động lại hay chưa
                $now = time();
                if ($now - $lastRedisCheck >= 30) {
                    $lastRedisCheck = $now;
                    if ($this->redis->isAvailable(0.3)) {
                        $isRedisOnline = true;
                        echo "\n\033[32m🟢 [KẾT NỐI LẠI] Phát hiện Redis Server đã hoạt động trở lại! Tự động chuyển về [REDIS DRIVER].\033[0m\n\n";
                    }
                }
            }
        }

        if ($this->shouldQuit) {
            echo "\033[32m✔ [GRACEFUL SHUTDOWN] Đã kết thúc tiến trình Queue Worker an toàn.\033[0m\n";
        }
    }

    /**
     * Xử lý thực thi nội dung của Job với Exponential Backoff & Graceful Protection
     */
    public function processJob(string $payloadStr, int $maxRetries = 3, string $queue = 'default'): void
    {
        $data = json_decode($payloadStr, true);

        if (!is_array($data) || empty($data['serialized'])) {
            echo "\033[31m[FAIL] Payload Job không đúng định dạng JSON.\033[0m\n";
            return;
        }

        $jobClass = $data['job'] ?? 'UnknownJob';
        $attempts = ($data['attempts'] ?? 0) + 1;

        echo "\033[33mProcessing Job:\033[0m {$jobClass} (Lần thử {$attempts}/{$maxRetries})...\n";

        $this->isProcessing = true;

        try {
            $jobInstance = unserialize($data['serialized']);

            if (!$jobInstance instanceof ShouldQueue) {
                throw new RuntimeException("Job class [{$jobClass}] không triển khai ShouldQueue interface.");
            }

            // Gọi phương thức handle với tự động tiêm Dependency thông qua DIC Container
            $container = Container::getInstance();
            $container->call([$jobInstance, 'handle']);

            echo "\033[32m✔ [SUCCESS] Job {$jobClass} xử lý hoàn tất thành công.\033[0m\n";
        } catch (Throwable $e) {
            echo "\033[31m✘ [FAILED] Job {$jobClass} thất bại: {$e->getMessage()}\033[0m\n";

            if ($attempts < $maxRetries) {
                // Tính toán Exponential Backoff + Jitter ngẫu nhiên (tránh thundering herd)
                $backoffSeconds = min((int)pow(2, $attempts) + random_int(1, 2), 60);

                $data['attempts'] = $attempts;
                $newPayload = (string)json_encode($data, JSON_UNESCAPED_UNICODE);

                echo "\033[33m↳ [RETRY BACKOFF] Chờ {$backoffSeconds}s trước khi lên lịch thử lại lần {$attempts}/{$maxRetries} cho {$jobClass}...\033[0m\n";
                sleep($backoffSeconds);

                QueueManager::pushPayload($newPayload, $queue);
            } else {
                $this->logFailedJob($jobClass, $payloadStr, $e);
            }
        } finally {
            $this->isProcessing = false;
            if ($this->shouldQuit) {
                echo "\033[32m✔ [GRACEFUL SHUTDOWN] Job hoàn tất. Đã dừng Queue Worker an toàn.\033[0m\n";
                exit(0);
            }
        }
    }

    /**
     * Xử lý quét các file job bị đẩy vào storage/queue/ khi Redis offline hoặc chế độ File
     */
    private function processFileFallback(string $queue, int $maxRetries): bool
    {
        $dir = dirname(__DIR__, 2) . "/storage/queue/{$queue}";
        if (!is_dir($dir)) {
            return false;
        }

        $files = glob($dir . '/*.json');
        if (empty($files)) {
            return false;
        }

        $file = $files[0];
        $payload = file_get_contents($file);
        @unlink($file);

        if ($payload !== false && trim($payload) !== '') {
            $this->processJob($payload, $maxRetries, $queue);
            return true;
        }

        return false;
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
