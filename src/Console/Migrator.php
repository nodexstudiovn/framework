<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * NodeX Studio Migration Runner Engine
 */

namespace NodeX\Console;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

class Migrator
{
    private string $migrationPath;

    public function __construct(string $basePath)
    {
        $this->migrationPath = $basePath . '/database/migrations';
        if (!is_dir($this->migrationPath)) {
            mkdir($this->migrationPath, 0755, true);
        }
        $this->ensureMigrationTable();
    }

    private function ensureMigrationTable(): void
    {
        if (!DB::schema()->hasTable('migrations')) {
            DB::schema()->create('migrations', static function (Blueprint $table): void {
                $table->id();
                $table->string('migration');
                $table->integer('batch');
                $table->timestamp('executed_at')->useCurrent();
            });
        }
    }

    public function run(): void
    {
        $files = glob($this->migrationPath . '/*.php');
        sort($files);

        $executed = DB::table('migrations')->pluck('migration')->toArray();
        $batch = (int) DB::table('migrations')->max('batch') + 1;

        $pending = [];
        foreach ($files as $file) {
            $name = basename($file, '.php');
            if (!in_array($name, $executed, true)) {
                $pending[$name] = $file;
            }
        }

        if (empty($pending)) {
            echo "\033[33m⚡ Database is already up to date. Nothing to migrate.\033[0m\n";
            return;
        }

        echo "\033[36m🚀 NodeX Migration Engine starting...\033[0m (Batch #{$batch})\n";
        echo str_repeat('-', 60) . "\n";

        foreach ($pending as $name => $file) {
            $startTime = microtime(true);
            $instance = require $file;

            if (is_object($instance) && method_exists($instance, 'up')) {
                $instance->up();
                DB::table('migrations')->insert([
                    'migration' => $name,
                    'batch' => $batch,
                    'executed_at' => date('Y-m-d H:i:s')
                ]);

                $duration = round((microtime(true) - $startTime) * 1000, 2);
                printf("  \033[32m✔ DONE\033[0m  %-42s \033[90m(%s ms)\033[0m\n", $name, $duration);
            }
        }

        echo str_repeat('-', 60) . "\n";
        echo "\033[32m✨ Successfully executed " . count($pending) . " migration(s).\033[0m\n";
    }

    public function rollback(): void
    {
        $lastBatch = DB::table('migrations')->max('batch');
        if (!$lastBatch) {
            echo "\033[33m⚡ No migrations found to rollback.\033[0m\n";
            return;
        }

        $records = DB::table('migrations')->where('batch', $lastBatch)->orderBy('id', 'desc')->get();
        echo "\033[35m⏪ Rolling back Batch #{$lastBatch}...\033[0m\n";
        echo str_repeat('-', 60) . "\n";

        foreach ($records as $record) {
            $file = $this->migrationPath . '/' . $record->migration . '.php';
            if (file_exists($file)) {
                $instance = require $file;
                if (is_object($instance) && method_exists($instance, 'down')) {
                    $instance->down();
                }
            }
            DB::table('migrations')->where('id', $record->id)->delete();
            printf("  \033[31m✘ ROLLED BACK\033[0m  %-40s\n", $record->migration);
        }

        echo str_repeat('-', 60) . "\n";
        echo "\033[32m✨ Rollback completed for Batch #{$lastBatch}.\033[0m\n";
    }

    public function status(): void
    {
        $files = glob($this->migrationPath . '/*.php');
        sort($files);

        $executed = DB::table('migrations')->get()->keyBy('migration');

        echo "\033[33mNodeX Database Migration Status:\033[0m\n";
        echo str_repeat('-', 70) . "\n";
        printf("%-7s %-45s %-12s\n", "RAN?", "MIGRATION", "BATCH");
        echo str_repeat('-', 70) . "\n";

        foreach ($files as $file) {
            $name = basename($file, '.php');
            if (isset($executed[$name])) {
                $status = "\033[32mYES\033[0m";
                $batch = (string)$executed[$name]->batch;
            } else {
                $status = "\033[31mNO\033[0m ";
                $batch = "-";
            }
            printf("[%-5s] %-45s %-12s\n", $status, $name, $batch);
        }
        echo str_repeat('-', 70) . "\n";
    }
}
