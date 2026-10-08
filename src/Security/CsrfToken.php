<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * CSRF Token Protection Class
 */

namespace NodeX\Security;

class CsrfToken
{
    private const string SESSION_KEY = '_csrf_token';

    /**
     * Khởi tạo Session nếu chưa bắt đầu
     */
    public static function initSession(): void
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }
    }

    /**
     * Lấy CSRF token hiện tại hoặc tự sinh mã token mới
     */
    public static function getToken(): string
    {
        self::initSession();

        if (empty($_SESSION[self::SESSION_KEY]) || !is_string($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::SESSION_KEY];
    }

    /**
     * Đối soát CSRF Token truyền lên từ Client
     */
    public static function validate(?string $token): bool
    {
        self::initSession();

        $sessionToken = $_SESSION[self::SESSION_KEY] ?? null;

        if (!is_string($sessionToken) || !is_string($token) || $token === '') {
            return false;
        }

        return hash_equals($sessionToken, $token);
    }

    /**
     * Sinh thẻ HTML Input hidden chứa CSRF Token dùng cho Form Blade
     */
    public static function field(): string
    {
        $token = self::getToken();
        return sprintf('<input type="hidden" name="_csrf_token" value="%s">', htmlspecialchars($token, ENT_QUOTES, 'UTF-8'));
    }

    /**
     * Sinh thẻ HTML Meta chứa CSRF Token cho JavaScript/AJAX Header
     */
    public static function meta(): string
    {
        $token = self::getToken();
        return sprintf('<meta name="csrf-token" content="%s">', htmlspecialchars($token, ENT_QUOTES, 'UTF-8'));
    }
}
