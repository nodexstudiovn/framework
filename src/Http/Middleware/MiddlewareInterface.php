<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * HTTP Middleware Interface
 */

namespace NodeX\Http\Middleware;

use NodeX\Http\Request;
use NodeX\Http\Response;

interface MiddlewareInterface
{
    /**
     * Xử lý HTTP Request thông qua chuỗi Middleware Pipeline
     */
    public function handle(Request $request, callable $next): Response;
}
