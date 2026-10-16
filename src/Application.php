<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Core Application Bootstrap Container
 */

namespace NodeX;

use NodeX\Container\Container;
use NodeX\Database\Connection;
use NodeX\Http\Request;
use NodeX\Http\Response;
use NodeX\Http\Router;
use NodeX\Security\CsrfToken;
use RuntimeException;
use Throwable;

class Application
{
    public const string NAME = 'NodeX Studio';
    public const string VERSION = '1.2.0';

    public private(set) string $basePath;
    public private(set) Router $router;
    public private(set) Request $request;
    public private(set) Container $container;
    
    private static ?Application $instance = null;

    public function __construct(string $basePath)
    {
        $this->basePath = rtrim($basePath, '/\\');
        if (!defined('NODEX_BASE_PATH')) {
            define('NODEX_BASE_PATH', $this->basePath);
        }
        self::$instance = $this;
        $this->bootstrap();
    }

    public static function getInstance(): Application
    {
        if (self::$instance === null) {
            throw new RuntimeException("NodeX Studio Application has not been initialized.");
        }
        return self::$instance;
    }

    private function bootstrap(): void
    {
        $this->loadEnvironment();

        // Nạp bộ Global Fluent Helpers
        require_once __DIR__ . '/Support/helpers.php';
        
        // Khởi tạo Session mặc định cho CSRF & State management
        CsrfToken::initSession();

        // Đăng ký Application & DIC Container
        $this->container = Container::getInstance();
        $this->container->instance(self::class, $this);
        $this->container->instance(Container::class, $this->container);

        Connection::init($this->basePath);
        
        $this->router = new Router();
        $this->container->instance(Router::class, $this->router);

        $this->loadRoutes();
    }

    private function loadEnvironment(): void
    {
        $envFile = $this->basePath . '/.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (str_starts_with(trim($line), '#')) {
                    continue;
                }
                if (str_contains($line, '=')) {
                    [$key, $value] = explode('=', $line, 2);
                    $key = trim($key);
                    $value = trim($value, " \t\n\r\0\x0B\"'");
                    $_ENV[$key] = $value;
                    putenv("{$key}={$value}");
                }
            }
        }
    }

    private function loadRoutes(): void
    {
        $webRoutes = $this->basePath . '/routes/web.php';
        $apiRoutes = $this->basePath . '/routes/api.php';

        if (file_exists($webRoutes)) {
            $router = $this->router;
            require_once $webRoutes;
        }

        if (file_exists($apiRoutes)) {
            $router = $this->router;
            require_once $apiRoutes;
        }
    }

    public function handle(?Request $request = null): Response
    {
        $this->request = $request ?? Request::capture();
        $this->container->instance(Request::class, $this->request);
        
        try {
            return $this->router->dispatch($this->request);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    private function handleException(Throwable $e): Response
    {
        return \NodeX\Exceptions\ExceptionHandler::handle($e, $this->request ?? null);
    }
}
