<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Action: SendMessageAction
 */

namespace App\Actions;

use App\Models\Message;
use NodeX\Broadcasting\Broadcaster;

class SendMessageAction extends Action
{
    /**
     * Lưu tin nhắn vào MySQL qua Eloquent và phát sự kiện Realtime qua Core Broadcaster Engine
     */
    public function execute(mixed ...$args): Message
    {
        $data = $args[0] ?? [];

        // 1. Lưu tin nhắn vào MySQL bằng Eloquent ORM
        $message = Message::create([
            'user_name' => $data['user_name'] ?? 'Khách (Guest)',
            'content' => $data['content'] ?? '',
            'room_id' => (int)($data['room_id'] ?? 1)
        ]);

        // 2. Phát sự kiện Realtime bằng Broadcaster lõi của NodeX Framework
        Broadcaster::emit('chat-channel', 'new-message', [
            'id' => $message->id,
            'user_name' => $message->user_name,
            'content' => $message->content,
            'room_id' => $message->room_id,
            'created_at' => date('H:i:s d/m/Y')
        ]);

        return $message;
    }
}
