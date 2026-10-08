<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * API Routes Definition File
 */

use App\Controllers\ChatController;
use App\Models\User;
use Illuminate\Database\Capsule\Manager as DB;
use NodeX\Http\Request;
use NodeX\Http\Response;
use NodeX\Http\Router;

/** @var Router $router */

$router->get('/api/v1/info', static function (Request $request): Response {
    return Response::json([
        'name' => 'NodeX Studio Framework',
        'version' => '1.1.0',
        'author' => 'Nguyễn Anh Kiệt',
        'php' => PHP_VERSION,
        'eloquent_orm' => 'Active (Illuminate/Database v13.x)',
        'environment' => $_ENV['APP_ENV'] ?? 'development'
    ]);
});

// Chat API Routes
$router->get('/api/chat/messages', [ChatController::class, 'messages']);
$router->post('/api/chat/messages', [ChatController::class, 'store']);

$router->get('/api/v1/test-db', static function (Request $request): Response {
    if (!DB::schema()->hasTable('users')) {
        DB::schema()->create('users', static function ($table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamps();
        });

        User::create([
            'name' => 'Nguyễn Anh Kiệt',
            'email' => 'kiet.nguyen@nodex.studio'
        ]);
    }

    $users = User::all();

    return Response::json([
        'status' => 'success',
        'database' => $_ENV['DB_CONNECTION'] ?? 'sqlite',
        'users_count' => $users->count(),
        'data' => $users
    ]);
});
