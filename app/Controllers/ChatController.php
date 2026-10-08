<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Controller: ChatController
 */

namespace App\Controllers;

use App\Actions\SendMessageAction;
use App\Models\Message;
use NodeX\Http\Request;
use NodeX\Http\Response;

class ChatController extends Controller
{
    /**
     * Hiển thị trang giao diện Realtime Chat
     */
    public function index(Request $request): Response
    {
        return $this->view('chat.index');
    }

    /**
     * Tải danh sách lịch sử tin nhắn
     */
    public function messages(Request $request): Response
    {
        $messages = Message::orderBy('id', 'desc')->take(50)->get()->reverse()->values();

        return $this->json([
            'status' => 'success',
            'data' => $messages
        ]);
    }

    /**
     * Xử lý gửi tin nhắn mới via Action Pattern & DIC Injection
     */
    public function store(Request $request, SendMessageAction $action): Response
    {
        $content = trim((string)$request->input('content', ''));
        $userName = trim((string)$request->input('user_name', 'Lập trình viên'));

        if (empty($content)) {
            return $this->json([
                'error' => [
                    'code' => 'INVALID_INPUT',
                    'message' => 'Nội dung tin nhắn không được để trống.'
                ]
            ], 422);
        }

        $message = $action->execute([
            'user_name' => $userName,
            'content' => $content,
            'room_id' => 1
        ]);

        return $this->json([
            'status' => 'success',
            'data' => $message
        ]);
    }
}
