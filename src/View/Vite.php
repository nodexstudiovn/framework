<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Vite Asset Bundler Integration Class
 */

namespace NodeX\View;

class Vite
{
    private static string $devServerUrl = 'http://127.0.0.1:5173';
    private static string $manifestPath = 'public/build/manifest.json';

    private static ?bool $devServerAvailable = null;

    public static function use(string|array $entrypoints): string
    {
        $entrypoints = (array) $entrypoints;
        $basePath = dirname(__DIR__, 2);

        $hotFile = $basePath . '/public/hot';
        $isDev = file_exists($hotFile);

        // Tự động kiểm tra xem Vite Dev Server (npm run dev) ở cổng 5173 có đang bật hay không
        if (!$isDev) {
            if (self::$devServerAvailable === null) {
                $connection = @fsockopen('127.0.0.1', 5173, $errno, $errstr, 0.02);
                if (is_resource($connection)) {
                    fclose($connection);
                    self::$devServerAvailable = true;
                } else {
                    self::$devServerAvailable = false;
                }
            }
            $isDev = self::$devServerAvailable;
        }

        if ($isDev) {
            $url = file_exists($hotFile) ? trim((string)file_get_contents($hotFile)) : self::$devServerUrl;
            $html = '<script type="module" src="' . rtrim($url, '/') . '/@vite/client"></script>' . "\n";

            foreach ($entrypoints as $entry) {
                $entryUrl = rtrim($url, '/') . '/' . ltrim($entry, '/');
                if (str_ends_with($entry, '.css')) {
                    $html .= '<link rel="stylesheet" href="' . $entryUrl . '">' . "\n";
                } else {
                    $html .= '<script type="module" src="' . $entryUrl . '"></script>' . "\n";
                }
            }

            return $html;
        }

        // Production Build Mode via manifest.json
        $fullManifestPath = $basePath . '/' . self::$manifestPath;
        if (!file_exists($fullManifestPath)) {
            // Fallback nếu chưa chạy npm run build
            $html = '';
            foreach ($entrypoints as $entry) {
                if (str_ends_with($entry, '.css')) {
                    $html .= '<link rel="stylesheet" href="/' . ltrim($entry, '/') . '">' . "\n";
                } else {
                    $html .= '<script type="module" src="/' . ltrim($entry, '/') . '"></script>' . "\n";
                }
            }
            return $html;
        }

        $manifest = json_decode((string)file_get_contents($fullManifestPath), true);
        if (!is_array($manifest)) {
            return '';
        }

        $html = '';
        foreach ($entrypoints as $entry) {
            $entryKey = ltrim($entry, '/');
            if (isset($manifest[$entryKey])) {
                $file = $manifest[$entryKey]['file'];
                $cssFiles = $manifest[$entryKey]['css'] ?? [];

                foreach ($cssFiles as $css) {
                    $html .= '<link rel="stylesheet" href="/build/' . $css . '">' . "\n";
                }

                if (str_ends_with($file, '.css')) {
                    $html .= '<link rel="stylesheet" href="/build/' . $file . '">' . "\n";
                } else {
                    $html .= '<script type="module" src="/build/' . $file . '"></script>' . "\n";
                }
            }
        }

        return $html;
    }
}
