<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Web Routes Definition File
 */

use App\Controllers\HomeController;
use NodeX\Http\Router;

/** @var Router $router */

$router->get('/', [HomeController::class, 'index']);
