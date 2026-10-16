<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Native Lightweight Pipeline Engine for NodeX Studio Framework v1.2.0
 */

namespace NodeX\Support;

use Closure;
use NodeX\Container\Container;

class Pipeline
{
    private mixed $passable = null;
    private array $pipes = [];
    private string $method = 'handle';

    public function __construct(private ?Container $container = null)
    {
        $this->container = $container ?? Container::getInstance();
    }

    /**
     * Dữ liệu đối tượng được gửi qua pipeline
     */
    public function send(mixed $passable): static
    {
        $this->passable = $passable;
        return $this;
    }

    /**
     * Danh sách các bước xử lý (Pipes / Middlewares / Closures / Classes)
     */
    public function through(array $pipes): static
    {
        $this->pipes = $pipes;
        return $this;
    }

    /**
     * Chỉ định tên phương thức được gọi trên các lớp pipe (Mặc định: 'handle')
     */
    public function via(string $method): static
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Thực thi pipeline và trả về kết quả tại trạm cuối (Destination)
     */
    public function then(Closure $destination): mixed
    {
        $pipeline = array_reduce(
            array_reverse($this->pipes),
            $this->carry(),
            static fn(mixed $passable): mixed => $destination($passable)
        );

        return $pipeline($this->passable);
    }

    /**
     * Tạo closure carry lồng nhau cho từng chặng của pipeline
     */
    private function carry(): Closure
    {
        return function (Closure $stack, mixed $pipe): Closure {
            return function (mixed $passable) use ($stack, $pipe): mixed {
                if (is_callable($pipe)) {
                    return $pipe($passable, $stack);
                }

                if (is_string($pipe)) {
                    $instance = $this->container->make($pipe);
                    return $instance->{$this->method}($passable, $stack);
                }

                if (is_object($pipe)) {
                    return $pipe->{$this->method}($passable, $stack);
                }

                return $stack($passable);
            };
        };
    }
}
