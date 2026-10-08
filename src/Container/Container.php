<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Dependency Injection Container (DIC) & Reflection Resolver
 */

namespace NodeX\Container;

use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;
use RuntimeException;
use Throwable;

class Container
{
    private static ?Container $instance = null;
    private array $bindings = [];
    private array $instances = [];

    public static function getInstance(): Container
    {
        if (self::$instance === null) {
            self::$instance = new self();
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
     * Khởi tạo và tự động resolve tham số cho class thông qua Reflection
     */
    public function make(string $abstract, array $parameters = []): mixed
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $concrete = $abstract;
        $isShared = false;

        if (isset($this->bindings[$abstract])) {
            $concrete = $this->bindings[$abstract]['concrete'];
            $isShared = $this->bindings[$abstract]['shared'];
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
     * Xây dựng đối tượng class dùng Reflection API
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

        $dependencies = $this->resolveDependencies($constructor->getParameters(), $parameters);

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
}
