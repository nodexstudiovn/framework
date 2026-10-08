<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Lightweight Redis Socket & Native Client for NodeX Framework
 */

namespace NodeX\Redis;

use RuntimeException;
use Throwable;

class RedisClient
{
    private mixed $socket = null;
    private string $host;
    private int $port;
    private float $timeout;

    public function __construct(?string $host = null, ?int $port = null, float $timeout = 2.5)
    {
        $this->host = $host ?? $_ENV['REDIS_HOST'] ?? '127.0.0.1';
        $this->port = $port ?? (int)($_ENV['REDIS_PORT'] ?? 6379);
        $this->timeout = $timeout;
    }

    /**
     * Kết nối Socket tới Redis Server
     */
    private function connect(): mixed
    {
        if ($this->socket !== null && is_resource($this->socket) && !feof($this->socket)) {
            return $this->socket;
        }

        $address = sprintf('tcp://%s:%d', $this->host, $this->port);
        $errno = 0;
        $errstr = '';

        $socket = @stream_socket_client(
            $address,
            $errno,
            $errstr,
            $this->timeout,
            STREAM_CLIENT_CONNECT
        );

        if (!$socket) {
            throw new RuntimeException("Không thể kết nối đến Redis Server tại {$address}: [{$errno}] {$errstr}");
        }

        stream_set_timeout($socket, (int)ceil($this->timeout));
        $this->socket = $socket;

        return $this->socket;
    }

    /**
     * Thực thi lệnh Redis theo chuẩn RESP Protocol
     */
    public function execute(array $args): mixed
    {
        try {
            $stream = $this->connect();
            $command = '*' . count($args) . "\r\n";
            foreach ($args as $arg) {
                $argStr = (string)$arg;
                $command .= '$' . strlen($argStr) . "\r\n" . $argStr . "\r\n";
            }

            fwrite($stream, $command);
            return $this->readResponse($stream);
        } catch (Throwable $e) {
            $this->socket = null;
            throw $e;
        }
    }

    /**
     * Đọc phản hồi RESP từ Redis stream
     */
    private function readResponse(mixed $stream): mixed
    {
        $line = fgets($stream);
        if ($line === false) {
            return null;
        }

        $type = $line[0];
        $value = substr($line, 1, -2);

        return match ($type) {
            '+' => $value, // Simple String
            '-' => throw new RuntimeException("Redis Error: {$value}"),
            ':' => (int)$value, // Integer
            '$' => $this->readBulkString($stream, (int)$value), // Bulk String
            '*' => $this->readArray($stream, (int)$value), // Array
            default => null
        };
    }

    private function readBulkString(mixed $stream, int $length): ?string
    {
        if ($length === -1) {
            return null;
        }

        $data = '';
        $read = 0;

        while ($read < $length) {
            $chunk = fread($stream, min($length - $read, 8192));
            if ($chunk === false || strlen($chunk) === 0) {
                break;
            }
            $data .= $chunk;
            $read += strlen($chunk);
        }

        fread($stream, 2); // Đọc bỏ \r\n
        return $data;
    }

    private function readArray(mixed $stream, int $count): ?array
    {
        if ($count === -1) {
            return null;
        }

        $result = [];
        for ($i = 0; $i < $count; $i++) {
            $result[] = $this->readResponse($stream);
        }

        return $result;
    }

    /**
     * Phát tin nhắn lên kênh Redis Pub/Sub
     */
    public function publish(string $channel, string $message): int
    {
        return (int)$this->execute(['PUBLISH', $channel, $message]);
    }

    /**
     * Đẩy công việc vào hàng đợi Queue (LPUSH)
     */
    public function lpush(string $queue, string $value): int
    {
        return (int)$this->execute(['LPUSH', $queue, $value]);
    }

    /**
     * Lấy công việc từ hàng đợi Queue với cơ chế Blocking (BRPOP)
     */
    public function brpop(string|array $queues, int $timeout = 5): ?array
    {
        $queues = (array)$queues;
        $args = array_merge(['BRPOP'], $queues, [$timeout]);
        
        $response = $this->execute($args);

        if (is_array($response) && count($response) === 2) {
            return [
                'queue' => $response[0],
                'payload' => $response[1]
            ];
        }

        return null;
    }

    public function __destruct()
    {
        if ($this->socket !== null && is_resource($this->socket)) {
            @fclose($this->socket);
        }
    }
}
