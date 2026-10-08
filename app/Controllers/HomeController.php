<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Home Controller Handler
 */

namespace App\Controllers;

use NodeX\Http\Request;
use NodeX\Http\Response;

class HomeController extends Controller
{
    public function index(Request $request): Response
    {
        return $this->view('index.blade.php');
    }

    public function status(Request $request): Response
    {
        return $this->json([
            'framework' => 'NodeX Studio',
            'version' => '1.1.0',
            'php_version' => PHP_VERSION,
            'status' => 'online',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
}
