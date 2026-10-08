<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Eloquent Database Connection Manager
 */

namespace NodeX\Database;

use Illuminate\Database\Capsule\Manager as Capsule;
use RuntimeException;

class Connection
{
    private static ?Capsule $capsule = null;

    public static function init(string $basePath): Capsule
    {
        if (self::$capsule === null) {
            $capsule = new Capsule();
            $driver = $_ENV['DB_CONNECTION'] ?? 'mysql';

            if ($driver === 'sqlite') {
                $dbPath = $_ENV['DB_DATABASE'] ?? 'storage/database.sqlite';
                if (!str_starts_with($dbPath, '/') && !str_contains($dbPath, ':')) {
                    $dbPath = $basePath . '/' . $dbPath;
                }
                $dir = dirname($dbPath);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                if (!file_exists($dbPath)) {
                    touch($dbPath);
                }

                $capsule->addConnection([
                    'driver'   => 'sqlite',
                    'database' => $dbPath,
                    'prefix'   => '',
                ]);
            } else {
                $capsule->addConnection([
                    'driver'    => $driver,
                    'host'      => $_ENV['DB_HOST'] ?? '127.0.0.1',
                    'port'      => $_ENV['DB_PORT'] ?? '3306',
                    'database'  => $_ENV['DB_NAME'] ?? $_ENV['DB_DATABASE'] ?? 'nodexvn',
                    'username'  => $_ENV['DB_USER'] ?? $_ENV['DB_USERNAME'] ?? 'root',
                    'password'  => $_ENV['DB_PASSWORD'] ?? '',
                    'charset'   => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix'    => '',
                ]);
            }

            // Make Eloquent Capsule globally accessible static methods & boot Eloquent ORM
            $capsule->setAsGlobal();
            $capsule->bootEloquent();

            self::$capsule = $capsule;
        }

        return self::$capsule;
    }

    public static function getCapsule(): Capsule
    {
        if (self::$capsule === null) {
            throw new RuntimeException("Eloquent Database has not been initialized yet.");
        }
        return self::$capsule;
    }
}
