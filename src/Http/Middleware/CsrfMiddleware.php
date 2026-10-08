<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * CSRF Protection Security Middleware
 */

namespace NodeX\Http\Middleware;

use NodeX\Http\Request;
use NodeX\Http\Response;
use NodeX\Security\CsrfToken;

class CsrfMiddleware implements MiddlewareInterface
{
    /**
     * Kiểm tra và bảo mật CSRF Token cho các Request biến đổi dữ liệu (POST, PUT, DELETE, PATCH)
     */
    public function handle(Request $request, callable $next): Response
    {
        // Khởi tạo token CSRF mặc định cho session
        CsrfToken::getToken();

        // Các phương thức an toàn (Safe HTTP Methods) được bỏ qua đối soát CSRF
        if (in_array($request->method, ['GET', 'HEAD', 'OPTIONS'], true)) {
            return $next($request);
        }

        // Bỏ qua kiểm tra CSRF cho các route API tĩnh bắt đầu bằng /api/
        if (str_starts_with($request->uri, '/api/')) {
            return $next($request);
        }

        // Lấy CSRF token từ Body Form hoặc HTTP Headers
        $token = $request->input('_csrf_token') 
            ?? $request->input('_token') 
            ?? $request->headers['X-CSRF-TOKEN'] 
            ?? $request->headers['x-csrf-token'] 
            ?? $request->headers['X-XSRF-TOKEN'] 
            ?? null;

        // Nếu token không khớp hoặc thiếu -> Trả về lỗi 403 Forbidden
        if (!CsrfToken::validate($token)) {
            if ($request->isJson()) {
                return Response::json([
                    'error' => [
                        'code' => 'CSRF_MISMATCH',
                        'message' => 'Mã xác thực CSRF Token không hợp lệ hoặc đã hết hạn.'
                    ]
                ], 403);
            }

            $htmlError = "
            <!DOCTYPE html>
            <html lang='vi' class='dark'>
            <head>
                <meta charset='UTF-8'>
                <title>403 Forbidden — CSRF Mismatch</title>
                <script src='https://cdn.tailwindcss.com'></script>
            </head>
            <body class='bg-zinc-950 text-zinc-100 flex items-center justify-center h-screen font-sans antialiased'>
                <div class='max-w-md w-full p-8 bg-zinc-900 border border-zinc-800 rounded-2xl shadow-2xl text-center space-y-4'>
                    <div class='w-12 h-12 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 flex items-center justify-center mx-auto'>
                        <svg class='w-6 h-6' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'></path></svg>
                    </div>
                    <h1 class='text-xl font-semibold text-zinc-100'>403 Forbidden — CSRF Token Mismatch</h1>
                    <p class='text-xs text-zinc-400 leading-relaxed'>Mã bảo mật CSRF Token không hợp lệ hoặc bị thiếu trong Request. Vui lòng đính kèm directive <code>@csrf</code> vào form hoặc gửi kèm header <code>X-CSRF-TOKEN</code>.</p>
                </div>
            </body>
            </html>
            ";

            return Response::html($htmlError, 403);
        }

        return $next($request);
    }
}
