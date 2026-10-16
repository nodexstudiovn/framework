<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Base Action class with Fluent Runner & Macroable support for NodeX Studio Framework v1.2.0
 */

namespace App\Actions;

use NodeX\Container\Container;
use NodeX\Support\Macroable;
use Throwable;

abstract class Action
{
    use Macroable;

    /**
     * Khởi tạo Action instance qua Container (Fluent Factory)
     */
    public static function make(mixed ...$args): static
    {
        return Container::getInstance()->make(static::class, $args);
    }

    /**
     * Thực thi nhanh Action với tự động inject tham số qua Container
     * Ví dụ:
     *   $user = RegisterUserAction::run($userData);
     */
    public static function run(mixed ...$args): mixed
    {
        $action = static::make();
        return $action->execute(...$args);
    }

    /**
     * Phương thức thực thi nghiệp vụ cốt lõi của Action
     */
    abstract public function execute(mixed ...$args): mixed;

    /**
     * Helper bọc an toàn nghiệp vụ, triệt tiêu try-catch rườm rà
     */
    protected function rescue(callable $callback, mixed $fallback = null, bool $report = true): mixed
    {
        try {
            return $callback();
        } catch (Throwable $e) {
            if ($report) {
                error_log(sprintf('[Action Rescue: %s] %s', static::class, $e->getMessage()));
            }
            return is_callable($fallback) ? $fallback($e) : $fallback;
        }
    }
}
