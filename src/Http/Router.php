<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Routing Engine Class with DIC & Middleware Pipeline Support
 */

namespace NodeX\Http;

use InvalidArgumentException;
use NodeX\Container\Container;
use NodeX\Http\Middleware\CsrfMiddleware;
use NodeX\Http\Middleware\MiddlewareInterface;

class Router
{
    private array $routes = [];
    private array $globalMiddleware = [];

    public function __construct()
    {
        // Đăng ký CSRF Middleware mặc định cho toàn hệ thống Router
        $this->useGlobalMiddleware(new CsrfMiddleware());
    }

    /**
     * Thêm Global Middleware cho tất cả các đường dẫn
     */
    public function useGlobalMiddleware(MiddlewareInterface|string $middleware): self
    {
        $this->globalMiddleware[] = $middleware;
        return $this;
    }

    public function get(string $path, callable|array|string $handler): self
    {
        return $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable|array|string $handler): self
    {
        return $this->addRoute('POST', $path, $handler);
    }

    public function put(string $path, callable|array|string $handler): self
    {
        return $this->addRoute('PUT', $path, $handler);
    }

    public function delete(string $path, callable|array|string $handler): self
    {
        return $this->addRoute('DELETE', $path, $handler);
    }

    public function addRoute(string $method, string $path, callable|array|string $handler): self
    {
        $path = '/' . trim($path, '/');
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }

        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler,
            'middleware' => []
        ];

        return $this;
    }

    /**
     * Gắn Middleware cho route vừa đăng ký gần nhất
     */
    public function middleware(MiddlewareInterface|string|array $middleware): self
    {
        if (empty($this->routes)) {
            return $this;
        }

        $lastIndex = count($this->routes) - 1;
        $middlewares = is_array($middleware) ? $middleware : [$middleware];

        foreach ($middlewares as $mw) {
            $this->routes[$lastIndex]['middleware'][] = $mw;
        }

        return $this;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Điều hướng và thực thi Request qua Pipeline Middleware & DIC
     */
    public function dispatch(Request $request): Response
    {
        $requestMethod = $request->method;
        $requestUri = '/' . trim($request->uri, '/');

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route['path']);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $requestUri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                // Gom toàn bộ Middleware (Global + Route Specific)
                $pipeline = array_merge($this->globalMiddleware, $route['middleware']);

                return $this->runPipeline($pipeline, $request, function (Request $req) use ($route, $params) {
                    return $this->callHandler($route['handler'], $req, $params);
                });
            }
        }

        return Response::json([
            'status' => 'error',
            'message' => "Route không tồn tại: {$requestMethod} {$requestUri}",
            'code' => 404
        ], 404);
    }

    /**
     * Chạy chuỗi Pipeline Middleware lần lượt
     */
    private function runPipeline(array $pipeline, Request $request, callable $target): Response
    {
        $container = Container::getInstance();

        $pipelineHandler = array_reduce(
            array_reverse($pipeline),
            function (callable $next, mixed $middleware) use ($container) {
                return function (Request $req) use ($next, $middleware, $container): Response {
                    if (is_string($middleware)) {
                        $middleware = $container->make($middleware);
                    }

                    if ($middleware instanceof MiddlewareInterface) {
                        return $middleware->handle($req, $next);
                    }

                    if (is_callable($middleware)) {
                        return $middleware($req, $next);
                    }

                    throw new InvalidArgumentException("Middleware không hợp lệ.");
                };
            },
            $target
        );

        return $pipelineHandler($request);
    }

    /**
     * Gọi Controller / Handler thông qua DIC Container để tự động Inject dependencies
     */
    private function callHandler(callable|array|string $handler, Request $request, array $params): Response
    {
        $container = Container::getInstance();
        $container->instance(Request::class, $request);

        // Gộp $request và $params thành tham số mặc định cho Reflection
        $defaultParams = array_merge(['request' => $request], $params);

        $result = $container->call($handler, $defaultParams);

        if ($result instanceof Response) {
            return $result;
        }

        if (is_array($result) || is_object($result)) {
            return Response::json($result);
        }

        return Response::html((string)$result);
    }
}
