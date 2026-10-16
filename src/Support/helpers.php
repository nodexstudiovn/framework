<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Global Fluent Helper Functions for NodeX Studio Framework v1.2.0
 */

use NodeX\Broadcasting\Broadcaster;
use NodeX\Container\Container;
use NodeX\Http\Response;
use NodeX\Http\ResponseFactory;
use NodeX\Queue\QueueManager;
use NodeX\Queue\ShouldQueue;
use NodeX\Security\CsrfToken;
use NodeX\Support\Pipeline;
use NodeX\View\Engine as ViewEngine;

if (!function_exists('response')) {
    /**
     * Khởi tạo hoặc trả về Fluent Response Factory
     * Ví dụ:
     *   return response()->json(['status' => 'success']);
     *   return response()->redirect('/home');
     *   return response('Hello World', 200);
     */
    function response(mixed $content = null, int $statusCode = 200, array $headers = []): Response|ResponseFactory
    {
        $factory = new ResponseFactory();
        if ($content === null && func_num_args() === 0) {
            return $factory;
        }
        return $factory->make((string)$content, $statusCode, $headers);
    }
}

if (!function_exists('view')) {
    /**
     * Render Blade View cực gọn chuẩn Strict View Hygiene
     * Ví dụ:
     *   return view('user.profile', compact('user'));
     */
    function view(string $view, array $data = []): Response
    {
        return ViewEngine::render($view, $data);
    }
}

if (!function_exists('app')) {
    /**
     * Truy xuất Application Container hoặc resolve dependency tự động
     * Ví dụ:
     *   $container = app();
     *   $service = app(OrderService::class);
     */
    function app(?string $abstract = null, array $parameters = []): mixed
    {
        $container = Container::getInstance();
        if ($abstract === null) {
            return $container;
        }
        return $container->make($abstract, $parameters);
    }
}

if (!function_exists('config')) {
    /**
     * Truy xuất giá trị cấu hình hoặc biến môi trường .env
     * Ví dụ:
     *   $dbHost = config('DB_HOST', '127.0.0.1');
     */
    function config(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? getenv($key) ?: $default;
    }
}

if (!function_exists('action')) {
    /**
     * Thực thi nhanh một Action qua DIC Container chỉ trong 1 dòng
     * Ví dụ:
     *   $user = action(RegisterUserAction::class, $requestData);
     */
    function action(string $actionClass, mixed ...$args): mixed
    {
        $instance = Container::getInstance()->make($actionClass);
        if (!method_exists($instance, 'execute')) {
            throw new RuntimeException("Action [{$actionClass}] phải có phương thức execute().");
        }
        return $instance->execute(...$args);
    }
}

if (!function_exists('rescue')) {
    /**
     * Bọc thực thi callback triệt tiêu try-catch rườm rà
     * Ví dụ:
     *   $rate = rescue(fn() => fetchExchangeRate(), default: 25000);
     */
    function rescue(callable $callback, mixed $rescue = null, bool $report = true): mixed
    {
        try {
            return $callback();
        } catch (Throwable $e) {
            if ($report) {
                error_log(sprintf(
                    '[RESCUE] %s in %s:%d',
                    $e->getMessage(),
                    $e->getFile(),
                    $e->getLine()
                ));
            }
            return is_callable($rescue) ? $rescue($e) : $rescue;
        }
    }
}

if (!function_exists('dispatch')) {
    /**
     * Đẩy một Job vào Hàng đợi ngầm (Queue Engine)
     * Ví dụ:
     *   dispatch(new SendWelcomeEmailJob('user@example.com', 'Kiet'));
     */
    function dispatch(ShouldQueue $job, string $queue = 'default'): bool
    {
        return QueueManager::push($job, $queue);
    }
}

if (!function_exists('broadcast')) {
    /**
     * Phát sự kiện thời gian thực (Micro-Reverb WebSockets)
     * Ví dụ:
     *   broadcast('orders', 'new-order', ['id' => 101]);
     */
    function broadcast(string $channel, string $event, array $data = []): bool
    {
        return Broadcaster::emit($channel, $event, $data);
    }
}

if (!function_exists('pipeline')) {
    /**
     * Khởi tạo Fluent Pipeline Pattern xử lý qua các middleware/passables
     * Ví dụ:
     *   $res = pipeline()->send($order)->through([Validate::class, Charge::class])->then(fn($o) => $o);
     */
    function pipeline(): Pipeline
    {
        return new Pipeline(Container::getInstance());
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Lấy chuỗi CSRF Token của phiên làm việc hiện tại
     */
    function csrf_token(): string
    {
        return CsrfToken::get();
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Trả về HTML input ẩn chứa CSRF Token
     */
    function csrf_field(): string
    {
        return CsrfToken::field();
    }
}

if (!function_exists('csrf_meta')) {
    /**
     * Trả về thẻ meta chứa CSRF Token cho AJAX / Fetch
     */
    function csrf_meta(): string
    {
        return CsrfToken::meta();
    }
}
