<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * HTTP Response Handler Class
 */

namespace NodeX\Http;

class Response
{
    public function __construct(
        public string $content = '',
        public int $statusCode = 200,
        public array $headers = []
    ) {}

    public static function json(mixed $data, int $statusCode = 200, array $headers = []): self
    {
        $headers['Content-Type'] = 'application/json; charset=utf-8';
        return new self(
            content: (string)json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            statusCode: $statusCode,
            headers: $headers
        );
    }

    public static function html(string $html, int $statusCode = 200, array $headers = []): self
    {
        $headers['Content-Type'] = 'text/html; charset=utf-8';
        return new self($html, $statusCode, $headers);
    }

    public static function redirect(string $url, int $statusCode = 302): self
    {
        return new self('', $statusCode, ['Location' => $url]);
    }

    public function send(): void
    {
        if (headers_sent()) {
            echo $this->content;
            return;
        }

        http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        echo $this->content;
    }
}
