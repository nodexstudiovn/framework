<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Fluent Response Factory for NodeX Studio Framework v1.2.0
 */

namespace NodeX\Http;

use NodeX\View\Engine as ViewEngine;

class ResponseFactory
{
    /**
     * Khởi tạo Response tiêu chuẩn
     */
    public function make(string $content = '', int $statusCode = 200, array $headers = []): Response
    {
        return new Response($content, $statusCode, $headers);
    }

    /**
     * Trả về JSON Response
     */
    public function json(mixed $data, int $statusCode = 200, array $headers = []): Response
    {
        return Response::json($data, $statusCode, $headers);
    }

    /**
     * Trả về HTML Response
     */
    public function html(string $html, int $statusCode = 200, array $headers = []): Response
    {
        return Response::html($html, $statusCode, $headers);
    }

    /**
     * Chuyển hướng URL (Redirect Response)
     */
    public function redirect(string $url, int $statusCode = 302): Response
    {
        return Response::redirect($url, $statusCode);
    }

    /**
     * Render Blade View với Strict View Hygiene Inspector
     */
    public function view(string $view, array $data = []): Response
    {
        return ViewEngine::render($view, $data);
    }
}
