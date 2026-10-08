<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Core Broadcaster Engine for Realtime WebSockets & Redis Pub/Sub
 */

namespace NodeX\Broadcasting;

use NodeX\Redis\RedisClient;
use Throwable;

class Broadcaster
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
     * Phát sự kiện Realtime lên kênh Redis Pub/Sub tới WebSocket Server
     */
    public static function emit(string $channel, string $event, array $data = []): bool
    {
        try {
            $payload = json_encode([
                'event' => $event,
                'channel' => $channel,
                'data' => $data,
                'emitted_at' => date('Y-m-d H:i:s')
            ], JSON_UNESCAPED_UNICODE);

            if ($payload === false) {
                return false;
            }

            $redis = self::getRedis();
            $redis->publish($channel, $payload);
            return true;
        } catch (Throwable $e) {
            error_log("Broadcaster warning: " . $e->getMessage());
            return false;
        }
    }
}
