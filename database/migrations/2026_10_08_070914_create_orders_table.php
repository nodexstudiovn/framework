<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Migration: create_orders_table
 */

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

return new class {
    public function up(): void
    {
        // DB::schema()->create('table_name', static function (Blueprint $table): void {
        //     $table->id();
        //     $table->timestamps();
        // });
    }

    public function down(): void
    {
        // DB::schema()->dropIfExists('table_name');
    }
};