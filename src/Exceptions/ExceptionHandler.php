<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Professional Exception Handler & Debug View Renderer for NodeX Framework v1.2.0
 */

namespace NodeX\Exceptions;

use NodeX\Application;
use NodeX\Http\Request;
use NodeX\Http\Response;
use NodeX\View\Engine as ViewEngine;
use Throwable;

class ExceptionHandler
{
    /**
     * Tiếp nhận và điều phối hiển thị ngoại lệ dựa vào cấu hình môi trường
     */
    public static function handle(Throwable $e, ?Request $request = null): Response
    {
        $debug = filter_var($_ENV['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOLEAN);

        // Ghi log ngoại lệ vào file nhật ký
        self::logException($e);

        // Trả về JSON nếu là API request hoặc client yêu cầu JSON
        if (self::isJsonRequest($request)) {
            return self::renderJson($e, $debug);
        }

        // Môi trường Development với APP_DEBUG=true: Hiển thị giao diện Debug chuyên nghiệp
        if ($debug) {
            return self::renderDebugView($e, $request);
        }

        // Môi trường Production: Hiển thị trang lỗi 500 tinh gọn, bảo mật thông tin
        return self::renderProductionView($e, $request);
    }

    /**
     * Render giao diện Debug màn hình đỏ/đen chuẩn mực kiểu Laravel Ignition / Whoops
     */
    private static function renderDebugView(Throwable $e, ?Request $request): Response
    {
        try {
            $snippet = self::getCodeSnippet($e->getFile(), $e->getLine());
            
            $formattedTrace = [];
            foreach ($e->getTrace() as $index => $step) {
                $file = $step['file'] ?? '[Nội bộ PHP / Closure]';
                $line = $step['line'] ?? 0;
                $class = $step['class'] ?? '';
                $type = $step['type'] ?? '';
                $function = $step['function'] ?? '';
                $caller = $class ? "{$class}{$type}{$function}()" : "{$function}()";

                $formattedTrace[] = [
                    'index' => $index + 1,
                    'caller' => $caller,
                    'file' => $file,
                    'line' => $line,
                    'snippet' => ($file !== '[Nội bộ PHP / Closure]' && file_exists($file)) 
                        ? self::getCodeSnippet($file, $line, 3) 
                        : []
                ];
            }

            $data = [
                'exception' => $e,
                'class' => get_class($e),
                'shortClass' => basename(str_replace('\\', '/', get_class($e))),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'snippet' => $snippet,
                'trace' => $formattedTrace,
                'request' => $request,
                'phpVersion' => PHP_VERSION,
                'nodeXVersion' => Application::VERSION,
                'memory' => round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB',
                'time' => date('H:i:s d/m/Y')
            ];

            $response = ViewEngine::render('errors.exception', $data);
            $response->statusCode = 500;
            return $response;
        } catch (Throwable $renderError) {
            return self::renderFallbackHtml($e, $renderError);
        }
    }

    /**
     * Render trang lỗi 500 thân thiện cho người dùng trên Production
     */
    private static function renderProductionView(Throwable $e, ?Request $request): Response
    {
        try {
            $response = ViewEngine::render('errors.500', [
                'message' => 'Hệ thống đang gặp sự cố gián đoạn. Đội ngũ kỹ thuật đã được thông báo.'
            ]);
            $response->statusCode = 500;
            return $response;
        } catch (Throwable) {
            return Response::html(
                '<!DOCTYPE html><html lang="vi"><head><meta charset="UTF-8"><title>500 — Server Error</title>' .
                '<style>body{background:#090d16;color:#f1f5f9;font-family:sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;text-align:center;}' .
                'h1{font-size:3rem;margin:0;color:#f43f5e;}p{opacity:.8;}</style></head>' .
                '<body><div><h1>500</h1><p>Máy chủ đang gặp sự cố gián đoạn. Vui lòng thử lại sau.</p></div></body></html>',
                500
            );
        }
    }

    /**
     * Trả về JSON phản hồi chuẩn
     */
    private static function renderJson(Throwable $e, bool $debug): Response
    {
        if ($debug) {
            return Response::json([
                'error' => [
                    'class' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => explode("\n", $e->getTraceAsString())
                ]
            ], 500);
        }

        return Response::json([
            'error' => [
                'code' => 500,
                'message' => 'Lỗi máy chủ nội bộ. Vui lòng liên hệ quản trị viên.'
            ]
        ], 500);
    }

    /**
     * Trích xuất các dòng code xung quanh vị trí phát sinh lỗi
     */
    public static function getCodeSnippet(string $file, int $errorLine, int $padding = 6): array
    {
        if (!file_exists($file) || !is_readable($file)) {
            return [];
        }

        $lines = file($file);
        if ($lines === false) {
            return [];
        }

        $totalLines = count($lines);
        $start = max(1, $errorLine - $padding);
        $end = min($totalLines, $errorLine + $padding);

        $snippet = [];
        for ($i = $start; $i <= $end; $i++) {
            $snippet[] = [
                'number' => $i,
                'code' => rtrim($lines[$i - 1], "\r\n"),
                'is_current' => ($i === $errorLine)
            ];
        }

        return $snippet;
    }

    /**
     * Kiểm tra xem request có phải yêu cầu JSON hay không
     */
    private static function isJsonRequest(?Request $request): bool
    {
        if ($request === null) {
            return false;
        }

        $uri = $request->uri;
        if (str_starts_with($uri, '/api')) {
            return true;
        }

        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        return str_contains($accept, 'application/json');
    }

    /**
     * Ghi nhật ký lỗi vào storage/logs/exceptions.log
     */
    private static function logException(Throwable $e): void
    {
        $logDir = dirname(__DIR__, 2) . '/storage/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $log = sprintf(
            "[%s] %s: %s in %s:%d\nStack Trace:\n%s\n%s\n",
            date('Y-m-d H:i:s'),
            get_class($e),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString(),
            str_repeat('-', 80)
        );

        @file_put_contents($logDir . '/exceptions.log', $log, FILE_APPEND);
    }

    /**
     * Fallback HTML trong trường hợp View Engine bị gián đoạn
     */
    private static function renderFallbackHtml(Throwable $e, Throwable $renderError): Response
    {
        $safeMsg = htmlspecialchars($e->getMessage(), ENT_QUOTES);
        $safeFile = htmlspecialchars($e->getFile(), ENT_QUOTES);
        $safeTrace = htmlspecialchars($e->getTraceAsString(), ENT_QUOTES);

        $html = <<<HTML
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>NodeX Studio Exception</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-900 text-gray-100 p-8 font-mono">
    <div class="max-w-4xl mx-auto space-y-4">
        <div class="bg-red-900 border border-red-700 p-4 rounded-xl text-red-200">
            <h1 class="text-xl font-bold">{$safeMsg}</h1>
            <p class="text-xs opacity-75 mt-1">in {$safeFile}:{$e->getLine()}</p>
        </div>
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-4 overflow-x-auto text-xs">
            <pre>{$safeTrace}</pre>
        </div>
    </div>
</body>
</html>
HTML;
        return Response::html($html, 500);
    }
}
