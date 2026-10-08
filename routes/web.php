<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Web Routes Definition File
 */

use App\Controllers\ChatController;
use App\Controllers\HomeController;
use NodeX\Http\Request;
use NodeX\Http\Response;
use NodeX\Http\Router;
use NodeX\View\Engine;

/** @var Router $router */

$router->get('/', [HomeController::class, 'index']);
$router->get('/status', [HomeController::class, 'status']);
$router->get('/chat', [ChatController::class, 'index']);
$router->get('/blade', static function (Request $request): Response {
    return Engine::render('demo.blade.php');
});
