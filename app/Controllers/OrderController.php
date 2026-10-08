<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Controller: OrderController
 */

namespace App\Controllers;

use NodeX\Http\Request;
use NodeX\Http\Response;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {
        return $this->json(['message' => 'Hello from OrderController!']);
    }
}