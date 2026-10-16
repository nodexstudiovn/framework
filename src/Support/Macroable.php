<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Macroable Trait for Fluent Extension in NodeX Framework
 */

namespace NodeX\Support;

use BadMethodCallException;
use Closure;

trait Macroable
{
    /**
     * Mảng chứa các macro phương thức mở rộng
     */
    protected static array $macros = [];

    /**
     * Đăng ký một macro tùy chỉnh vào class
     */
    public static function macro(string $name, object|callable $macro): void
    {
        static::$macros[$name] = $macro;
    }

    /**
     * Kiểm tra xem macro đã được đăng ký hay chưa
     */
    public static function hasMacro(string $name): bool
    {
        return isset(static::$macros[$name]);
    }

    /**
     * Xử lý gọi phương thức động (Dynamic Method Calls)
     */
    public function __call(string $method, array $parameters): mixed
    {
        if (!static::hasMacro($method)) {
            throw new BadMethodCallException(sprintf(
                'Phương thức %s::%s() không tồn tại trong hệ thống.',
                static::class,
                $method
            ));
        }

        $macro = static::$macros[$method];

        if ($macro instanceof Closure) {
            $macro = $macro->bindTo($this, static::class);
        }

        return $macro(...$parameters);
    }

    /**
     * Xử lý gọi phương thức tĩnh động (Static Dynamic Calls)
     */
    public static function __callStatic(string $method, array $parameters): mixed
    {
        if (!static::hasMacro($method)) {
            throw new BadMethodCallException(sprintf(
                'Phương thức tĩnh %s::%s() không tồn tại trong hệ thống.',
                static::class,
                $method
            ));
        }

        $macro = static::$macros[$method];

        if ($macro instanceof Closure) {
            $macro = $macro->bindTo(null, static::class);
        }

        return $macro(...$parameters);
    }
}
