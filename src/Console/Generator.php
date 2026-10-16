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
 *
 * Cách gọi thực thi:
 *   1. Chạy nhanh: {$name}::run(\$data);
 *   2. Fluent Chain: {$name}::make()->execute(\$data);
 *   3. Helper toàn cục: action({$name}::class, \$data);
 */

namespace App\Actions;

class {$name} extends Action
{
    /**
     * Tận dụng PHP 8.4 Constructor Property Promotion & Asymmetric Visibility nếu cần tiêm phụ thuộc
     * public function __construct(
     *     public private(set) ?SomeService \$service = null
     * ) {}
     */

    public function execute(mixed ...\$args): mixed
    {
        // Viết nghiệp vụ tại đây (Sử dụng rescue() nếu muốn bọc lỗi gọn gàng)
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
 * Eloquent Model: {$name}
 */

namespace App\Models;

class {$name} extends Model
{
    protected \$table = '{$table}';

    protected \$guarded = [];

    /**
     * Tận dụng Property Hooks (PHP 8.4+) thay thế getter/setter truyền thống:
     * public string \$formatted_title {
     *     get => ucfirst(\$this->title ?? '');
     * }
     */
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

    public function makeJob(string $name): void
    {
        $dir = $this->basePath . '/app/Jobs';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $name = str_replace('Job', '', $name) . 'Job';
        $filePath = "{$dir}/{$name}.php";

        if (file_exists($filePath)) {
            echo "\033[31m✘ Job [{$name}] already exists.\033[0m\n";
            return;
        }

        $template = <<<PHP
<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Queue Job: {$name}
 */

namespace App\Jobs;

use NodeX\Queue\ShouldQueue;

class {$name} implements ShouldQueue
{
    /**
     * Tận dụng PHP 8.4 Constructor Property Promotion
     */
    public function __construct(
        public readonly string \$payload = ''
    ) {}

    /**
     * Xử lý tác vụ ngầm khi Worker lấy job ra khỏi hàng đợi
     */
    public function handle(): void
    {
        // Thực thi nghiệp vụ ngầm tại đây (Gửi email, xuất PDF, đồng bộ API...)
    }
}
PHP;

        file_put_contents($filePath, $template);
        echo "\033[32m✔ Created Job:\033[0m app/Jobs/{$name}.php\n";
    }

    public function makeTest(string $name): void
    {
        $dir = $this->basePath . '/tests/Feature';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $name = str_replace('Test', '', $name) . 'Test';
        $filePath = "{$dir}/{$name}.php";

        if (file_exists($filePath)) {
            echo "\033[31m✘ Test [{$name}] already exists.\033[0m\n";
            return;
        }

        $template = <<<PHP
<?php

declare(strict_types=1);

/**
 * NodeX Studio - Feature Test Case
 * Test: {$name}
 */

test('{$name} executes successfully', function () {
    expect(true)->toBeTrue();
});
PHP;

        file_put_contents($filePath, $template);
        echo "\033[32m✔ Created Test Case:\033[0m tests/Feature/{$name}.php\n";
    }
}
