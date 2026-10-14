<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * API Routes Definition File
 */

use NodeX\Http\Request;
use NodeX\Http\Response;
use NodeX\Http\Router;

/** @var Router $router */

$router->get('/api/v1/info', static function (Request $request): Response {
    return Response::json([
        'framework' => 'NodeX Studio',
        'version' => '1.1.0',
        'php_version' => PHP_VERSION,
        'status' => 'operational',
        'environment' => $_ENV['APP_ENV'] ?? 'development'
    ]);
});
