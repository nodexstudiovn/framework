<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Eloquent Model: Message
 */

namespace App\Models;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = [
        'user_name',
        'content',
        'room_id'
    ];
}
