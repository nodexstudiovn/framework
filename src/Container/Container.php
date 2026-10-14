<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * High-Performance Dependency Injection Container (DIC) & Compiled Container
 */

namespace NodeX\Container;

use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionParameter;
use RuntimeException;
use Throwable;

class Container
{
    private static ?Container $instance = null;
    private array $bindings = [];
    private array $instances = [];
    private array $compiled = [];
    private static array $paramCache = [];

    public function __construct(?string $cachePath = null)
    {
        $cacheFile = $cachePath ?? (defined('NODEX_BASE_PATH') ? NODEX_BASE_PATH . '/storage/cache/container.php' : null);
        if ($cacheFile !== null && file_exists($cacheFile)) {
            $compiled = require $cacheFile;
            if (is_array($compiled)) {
                $this->compiled = $compiled;
            }
        }
    }

    public static function getInstance(): Container
    {
        if (self::$instance === null) {
            $basePath = defined('NODEX_BASE_PATH') ? NODEX_BASE_PATH : dirname(__DIR__, 2);
            $cacheFile = $basePath . '/storage/cache/container.php';
            self::$instance = new self($cacheFile);
        }
        return self::$instance;
    }

    public static function setInstance(Container $container): void
    {
        self::$instance = $container;
    }

    /**
     * Đăng ký binding giao diện hoặc class cụ thể vào Container
     */
    public function bind(string $abstract, mixed $concrete = null, bool $shared = false): void
    {
        if ($concrete === null) {
            $concrete = $abstract;
        }

        $this->bindings[$abstract] = [
            'concrete' => $concrete,
            'shared' => $shared
        ];
    }

    /**
     * Đăng ký Singleton instance vào Container
     */
    public function singleton(string $abstract, mixed $concrete = null): void
    {
        $this->bind($abstract, $concrete, true);
    }

    /**
     * Đăng ký trực tiếp một object sẵn có vào Container
     */
    public function instance(string $abstract, mixed $instance): void
    {
        $this->instances[$abstract] = $instance;
    }

    /**
     * Khởi tạo và tự động resolve tham số cho class
     * Ưu tiên: 1) In-memory instance -> 2) Compiled closure -> 3) Reflection
     */
    public function make(string $abstract, array $parameters = []): mixed
    {
        // 1. Singleton hoặc instance đã được phân giải trong request lifecycle
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        // 2. Kiểm tra trong compiled cache (Production Optimized 0ms Reflection)
        if (isset($this->compiled[$abstract])) {
            $factory = $this->compiled[$abstract];
            $object = $factory($this, $parameters);

            if (!empty($this->bindings[$abstract]['shared'])) {
                $this->instances[$abstract] = $object;
            }

            return $object;
        }

        $concrete = $abstract;
        $isShared = false;

        if (isset($this->bindings[$abstract])) {
            $concrete = $this->bindings[$abstract]['concrete'];
            $isShared = (bool)$this->bindings[$abstract]['shared'];
        }

        if (is_callable($concrete)) {
            $object = $concrete($this, $parameters);
        } elseif (is_object($concrete)) {
            $object = $concrete;
        } else {
            $object = $this->build($concrete, $parameters);
        }

        if ($isShared) {
            $this->instances[$abstract] = $object;
        }

        return $object;
    }

    /**
     * Xây dựng đối tượng class dùng Reflection API với Param Metadata Cache
     */
    public function build(string $className, array $parameters = []): mixed
    {
        if (!class_exists($className)) {
            throw new RuntimeException("Class [{$className}] không tồn tại trong hệ thống.");
        }

        $reflector = new ReflectionClass($className);

        if (!$reflector->isInstantiable()) {
            throw new RuntimeException("Class [{$className}] không thể khởi tạo trực tiếp (Interface/Abstract).");
        }

        $constructor = $reflector->getConstructor();

        if ($constructor === null) {
            return new $className();
        }

        // Tận dụng paramCache để không phải gọi ReflectionParameters lặp lại
        if (!isset(self::$paramCache[$className])) {
            self::$paramCache[$className] = $constructor->getParameters();
        }

        $dependencies = $this->resolveDependencies(self::$paramCache[$className], $parameters);

        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * Thực thi một Callable/Method với tự động tiêm phụ thuộc (Method Injection)
     */
    public function call(callable|array|string $callback, array $defaultParameters = []): mixed
    {
        if (is_array($callback)) {
            [$instance, $method] = $callback;
            if (is_string($instance)) {
                $instance = $this->make($instance);
            }
            $reflector = new ReflectionMethod($instance, $method);
            $dependencies = $this->resolveDependencies($reflector->getParameters(), $defaultParameters);
            return $reflector->invokeArgs($instance, $dependencies);
        }

        if (is_string($callback) && str_contains($callback, '@')) {
            [$class, $method] = explode('@', $callback);
            $instance = $this->make($class);
            $reflector = new ReflectionMethod($instance, $method);
            $dependencies = $this->resolveDependencies($reflector->getParameters(), $defaultParameters);
            return $reflector->invokeArgs($instance, $dependencies);
        }

        $reflector = new ReflectionFunction($callback);
        $dependencies = $this->resolveDependencies($reflector->getParameters(), $defaultParameters);
        return $reflector->invokeArgs($dependencies);
    }

    /**
     * Giải mã và tiêm tự động các tham số dựa vào Type-hinting
     */
    private function resolveDependencies(array $reflectionParameters, array $defaultParameters = []): array
    {
        $dependencies = [];

        foreach ($reflectionParameters as $parameter) {
            $name = $parameter->getName();
            $type = $parameter->getType();

            // Ưu tiên tham số đã được truyền trực tiếp
            if (array_key_exists($name, $defaultParameters)) {
                $dependencies[] = $defaultParameters[$name];
                continue;
            }

            if ($type !== null && !$type->isBuiltin()) {
                $className = $type->getName();

                // Kiểm tra xem đối tượng có sẵn trong danh sách defaultParameters không
                foreach ($defaultParameters as $param) {
                    if (is_object($param) && is_a($param, $className)) {
                        $dependencies[] = $param;
                        continue 2;
                    }
                }

                // Tự động giải quyết Dependency từ Container
                try {
                    $dependencies[] = $this->make($className);
                    continue;
                } catch (Throwable $e) {
                    if ($parameter->isDefaultValueAvailable()) {
                        $dependencies[] = $parameter->getDefaultValue();
                        continue;
                    }
                    throw $e;
                }
            }

            if ($parameter->isDefaultValueAvailable()) {
                $dependencies[] = $parameter->getDefaultValue();
            } else {
                throw new RuntimeException("Không thể tự động giải quyết (resolve) tham số [{$name}] trong Container.");
            }
        }

        return $dependencies;
    }

    /**
     * Quét và biên dịch cây phụ thuộc ra file PHP tĩnh (Compiled Container)
     */
    public function compile(string $basePath): int
    {
        $targetDirs = [
            $basePath . '/app/Actions',
            $basePath . '/app/Controllers',
            $basePath . '/app/Jobs'
        ];

        $compiledMap = [];
        $count = 0;

        foreach ($targetDirs as $dir) {
            if (!is_dir($dir)) {
                continue;
            }

            $files = glob($dir . '/*.php');
            foreach ($files as $file) {
                $className = $this->extractClassFromFile($file);
                if ($className === null || !class_exists($className)) {
                    continue;
                }

                $reflector = new ReflectionClass($className);
                if (!$reflector->isInstantiable()) {
                    continue;
                }

                $constructor = $reflector->getConstructor();
                if ($constructor === null || empty($constructor->getParameters())) {
                    $compiledMap[$className] = "static fn(\NodeX\Container\Container \$c, array \$p = []) => new \\{$className}()";
                    $count++;
                    continue;
                }

                $args = [];
                $canCompile = true;
                foreach ($constructor->getParameters() as $param) {
                    $type = $param->getType();
                    if ($type !== null && !$type->isBuiltin()) {
                        $depClass = $type->getName();
                        $args[] = "\$c->make('{$depClass}')";
                    } elseif ($param->isDefaultValueAvailable()) {
                        $default = var_export($param->getDefaultValue(), true);
                        $args[] = $default;
                    } else {
                        $canCompile = false;
                        break;
                    }
                }

                if ($canCompile) {
                    $argsStr = implode(', ', $args);
                    $compiledMap[$className] = "static fn(\NodeX\Container\Container \$c, array \$p = []) => new \\{$className}({$argsStr})";
                    $count++;
                }
            }
        }

        $cacheDir = $basePath . '/storage/cache';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }

        $code = "<?php\n\ndeclare(strict_types=1);\n\n/**\n * NodeX Studio - Compiled Container Optimization Map\n * Generated at: " . date('Y-m-d H:i:s') . "\n */\n\nreturn [\n";
        foreach ($compiledMap as $class => $factory) {
            $code .= "    '{$class}' => {$factory},\n";
        }
        $code .= "];\n";

        file_put_contents($cacheDir . '/container.php', $code);

        return $count;
    }

    private function extractClassFromFile(string $file): ?string
    {
        $content = file_get_contents($file);
        if ($content === false) {
            return null;
        }

        $namespace = '';
        if (preg_match('/namespace\s+([^;]+);/', $content, $m)) {
            $namespace = trim($m[1]);
        }

        if (preg_match('/class\s+([A-Za-z0-9_]+)/', $content, $m)) {
            $class = trim($m[1]);
            return $namespace ? $namespace . '\\' . $class : $class;
        }

        return null;
    }
}
