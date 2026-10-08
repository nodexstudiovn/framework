<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Front Controller Entry Point
 */

use NodeX\Application;

define('NODEX_START', microtime(true));

$basePath = dirname(__DIR__);

// Load Composer Autoloader if exists
if (file_exists($basePath . '/vendor/autoload.php')) {
    require_once $basePath . '/vendor/autoload.php';
} else {
    // Custom PSR-4 Fallback Autoloader for NodeX and App namespaces
    spl_autoload_register(static function (string $class) use ($basePath): void {
        $prefixes = [
            'NodeX\\' => $basePath . '/src/',
            'App\\'   => $basePath . '/app/'
        ];

        foreach ($prefixes as $prefix => $baseDir) {
            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                continue;
            }

            $relativeClass = substr($class, $len);
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    });
}

// Bootstrap NodeX Studio Application
$app = new Application($basePath);

// Handle HTTP Request and Send Response
$response = $app->handle();
$response->send();
