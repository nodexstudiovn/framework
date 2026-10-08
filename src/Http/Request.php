<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * HTTP Request Handler Class
 */

namespace NodeX\Http;

class Request
{
    public function __construct(
        public readonly string $method,
        public readonly string $uri,
        public readonly array $query = [],
        public readonly array $body = [],
        public readonly array $headers = [],
        public readonly array $server = []
    ) {}

    public static function capture(): self
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        
        $headers = [];
        if (function_exists('getallheaders')) {
            $headers = getallheaders() ?: [];
        } else {
            foreach ($_SERVER as $name => $value) {
                if (str_starts_with($name, 'HTTP_')) {
                    $headerName = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                    $headers[$headerName] = $value;
                }
            }
        }

        $body = $_POST;
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (str_contains($contentType, 'application/json')) {
            $rawInput = file_get_contents('php://input');
            if ($rawInput !== false) {
                $json = json_decode($rawInput, true);
                if (is_array($json)) {
                    $body = array_merge($body, $json);
                }
            }
        }

        return new self(
            method: $method,
            uri: $uri,
            query: $_GET,
            body: $body,
            headers: $headers,
            server: $_SERVER
        );
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->body[$key] ?? $this->query[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($this->query, $this->body);
    }

    public function isJson(): bool
    {
        $contentType = $this->headers['Content-Type'] ?? $this->headers['content-type'] ?? '';
        return str_contains((string)$contentType, 'application/json');
    }
}
