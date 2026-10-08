<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * NodeX Studio Code Scaffolding Generator
 */

namespace NodeX\Console;

class Generator
{
    public function __construct(private string $basePath) {}

    public function makeMigration(string $name): void
    {
        $dir = $this->basePath . '/database/migrations';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $timestamp = date('Y_m_d_His');
        $fileName = "{$timestamp}_{$name}.php";
        $filePath = "{$dir}/{$fileName}";

        $template = <<<PHP
<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Migration: {$name}
 */

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

return new class {
    public function up(): void
    {
        // DB::schema()->create('table_name', static function (Blueprint \$table): void {
        //     \$table->id();
        //     \$table->timestamps();
        // });
    }

    public function down(): void
    {
        // DB::schema()->dropIfExists('table_name');
    }
};
PHP;

        file_put_contents($filePath, $template);
        echo "\033[32m✔ Created Migration:\033[0m database/migrations/{$fileName}\n";
    }

    public function makeAction(string $name): void
    {
        $dir = $this->basePath . '/app/Actions';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $name = str_replace('Action', '', $name) . 'Action';
        $filePath = "{$dir}/{$name}.php";

        if (file_exists($filePath)) {
            echo "\033[31m✘ Action [{$name}] already exists.\033[0m\n";
            return;
        }

        $template = <<<PHP
<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Action: {$name}
 */

namespace App\Actions;

class {$name} extends Action
{
    public function execute(mixed ...\$args): mixed
    {
        // Implement business logic here
        return true;
    }
}
PHP;

        file_put_contents($filePath, $template);
        echo "\033[32m✔ Created Action:\033[0m app/Actions/{$name}.php\n";
    }

    public function makeModel(string $name): void
    {
        $dir = $this->basePath . '/app/Models';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filePath = "{$dir}/{$name}.php";
        if (file_exists($filePath)) {
            echo "\033[31m✘ Model [{$name}] already exists.\033[0m\n";
            return;
        }

        $table = strtolower($name) . 's';

        $template = <<<PHP
<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Model: {$name}
 */

namespace App\Models;

class {$name} extends Model
{
    protected \$table = '{$table}';

    protected \$guarded = [];
}
PHP;

        file_put_contents($filePath, $template);
        echo "\033[32m✔ Created Model:\033[0m app/Models/{$name}.php\n";
    }

    public function makeController(string $name): void
    {
        $dir = $this->basePath . '/app/Controllers';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $name = str_replace('Controller', '', $name) . 'Controller';
        $filePath = "{$dir}/{$name}.php";

        if (file_exists($filePath)) {
            echo "\033[31m✘ Controller [{$name}] already exists.\033[0m\n";
            return;
        }

        $template = <<<PHP
<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Controller: {$name}
 */

namespace App\Controllers;

use NodeX\Http\Request;
use NodeX\Http\Response;

class {$name} extends Controller
{
    public function index(Request \$request): Response
    {
        return \$this->json(['message' => 'Hello from {$name}!']);
    }
}
PHP;

        file_put_contents($filePath, $template);
        echo "\033[32m✔ Created Controller:\033[0m app/Controllers/{$name}.php\n";
    }

    public function makeRule(string $name): void
    {
        $dir = $this->basePath . '/app/Rules';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $name = str_replace('Rule', '', $name) . 'Rule';
        $filePath = "{$dir}/{$name}.php";

        if (file_exists($filePath)) {
            echo "\033[31m✘ Rule [{$name}] already exists.\033[0m\n";
            return;
        }

        $template = <<<PHP
<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Validation Rule: {$name}
 */

namespace App\Rules;

class {$name} implements Rule
{
    public function passes(string \$attribute, mixed \$value): bool
    {
        return !empty(\$value);
    }

    public function message(): string
    {
        return "The :attribute validation failed.";
    }
}
PHP;

        file_put_contents($filePath, $template);
        echo "\033[32m✔ Created Rule:\033[0m app/Rules/{$name}.php\n";
    }
}
