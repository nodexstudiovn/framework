<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Laravel Blade Template Engine Integration with Strict View Hygiene Inspector
 */

namespace NodeX\View;

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\FileEngine;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use NodeX\Http\Response;
use NodeX\Security\CsrfToken;
use NodeX\View\Vite as ViteHelper;
use RuntimeException;

class Engine
{
    private static ?Factory $factory = null;

    public static function init(string $basePath): Factory
    {
        if (self::$factory === null) {
            $viewPaths = [
                $basePath . '/resources/views',
                $basePath . '/app/Views',
                $basePath,
            ];

            $cachePath = $basePath . '/storage/cache/views';
            if (!is_dir($cachePath)) {
                mkdir($cachePath, 0755, true);
            }

            $filesystem = new Filesystem();

            // Create Blade Compiler
            $bladeCompiler = new BladeCompiler($filesystem, $cachePath);

            // Register custom Blade directives (e.g., @vite, @csrf, @csrf_meta)
            $bladeCompiler->directive('vite', static function (string $expression): string {
                return "<?php echo \\" . ViteHelper::class . "::use({$expression}); ?>";
            });

            $bladeCompiler->directive('csrf', static function (): string {
                return "<?php echo \\" . CsrfToken::class . "::field(); ?>";
            });

            $bladeCompiler->directive('csrf_meta', static function (): string {
                return "<?php echo \\" . CsrfToken::class . "::meta(); ?>";
            });

            // Register Engine Resolver
            $resolver = new EngineResolver();
            $resolver->register('blade', static function () use ($bladeCompiler) {
                return new CompilerEngine($bladeCompiler);
            });

            $resolver->register('php', static function () use ($filesystem) {
                return new PhpEngine($filesystem);
            });

            $resolver->register('file', static function () use ($filesystem) {
                return new FileEngine($filesystem);
            });

            $finder = new FileViewFinder($filesystem, $viewPaths);
            $eventDispatcher = new Dispatcher(new Container());

            $factory = new Factory($resolver, $finder, $eventDispatcher);

            self::$factory = $factory;
        }

        return self::$factory;
    }

    /**
     * Render View và thực thi kiểm tra Strict View Hygiene ở tầng ứng dụng
     */
    public static function render(string $viewPath, array $data = []): Response
    {
        $basePath = dirname(__DIR__, 2);
        $factory = self::init($basePath);

        // Chuẩn hóa tên view
        $viewName = str_replace(['.blade.php', '.php', '.html'], '', $viewPath);
        $viewName = str_replace('/', '.', trim($viewName, '/'));

        // Tìm vị trí file thực tế và đối soát Strict View Hygiene
        $possibleFiles = [
            $basePath . '/resources/views/' . str_replace('.', '/', $viewName) . '.blade.php',
            $basePath . '/resources/views/' . str_replace('.', '/', $viewName) . '.php',
            $basePath . '/' . ltrim($viewPath, '/')
        ];

        foreach ($possibleFiles as $file) {
            if (file_exists($file)) {
                $rawContent = (string)file_get_contents($file);
                // Thực thi kiểm tra quy chuẩn Vệ sinh View ở tầng ứng dụng
                ViewHygieneChecker::check($file, $rawContent);
                break;
            }
        }

        if ($factory->exists($viewName)) {
            $content = $factory->make($viewName, $data)->render();
            return Response::html($content);
        }

        // Fallback cho file HTML thuần
        $file = $basePath . '/' . ltrim($viewPath, '/');
        if (file_exists($file)) {
            extract($data);
            ob_start();
            include $file;
            $content = ob_get_clean();
            return Response::html($content !== false ? $content : '');
        }

        throw new RuntimeException("View [{$viewPath}] không tồn tại trong resources/views hoặc app/Views.");
    }
}
