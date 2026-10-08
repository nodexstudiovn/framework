<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Base Controller Class
 */

namespace App\Controllers;

use NodeX\Http\Response;
use NodeX\View\Engine;

abstract class Controller
{
    protected function json(mixed $data, int $status = 200): Response
    {
        return Response::json($data, $status);
    }

    protected function view(string $viewPath, array $data = []): Response
    {
        return Engine::render($viewPath, $data);
    }
}
