<?php

namespace Danaei\ShopCMS\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallCMSCommand extends Command
{
    protected $signature = 'cms:install';
    protected $description = 'Install the CMS and create the default architecture in the main project';

    public function __construct(private Filesystem $files)
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Starting CMS installation...');

        // 1. Copy view stubs
        $viewsPath = resource_path('views');
        $stubsPath = __DIR__ . '/../stubs/views';
        $this->copyStubFiles($stubsPath, $viewsPath);

        // 2. Create bootstrap/app.php if not exists
        $this->createBootstrapAppFile();

        // 3. Register middleware in bootstrap/app.php
        $this->registerMiddlewareInBootstrap();

        // 4. Add middleware to web routes
        $this->addMiddlewareToWebRoutes();

        $this->info('CMS installed successfully!');
    }

    protected function copyStubFiles($source, $destination)
    {
        $this->info("Copying views to: $destination");

        if (!$this->files->isDirectory($source)) {
            $this->error("Source stub files not found: $source");
            return;
        }

        $this->files->copyDirectory($source, $destination);
        $this->info('View structure copied successfully!');
    }

    protected function createBootstrapAppFile()
    {
        $bootstrapPath = base_path('bootstrap');
        $appFilePath = $bootstrapPath . '/app.php';

        // Create bootstrap directory if not exists
        if (!$this->files->exists($bootstrapPath)) {
            $this->files->makeDirectory($bootstrapPath);
        }

        // Create app.php if not exists
        if (!$this->files->exists($appFilePath)) {
            $content = <<<'PHP'
<?php

use Illuminate\Foundation\Application;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function ($middleware) {
        // Middleware configurations will be added here
    })
    ->withExceptions(function ($exceptions) {
        // Exception handling configurations
    })->create();
PHP;
            $this->files->put($appFilePath, $content);
            $this->info('bootstrap/app.php created successfully!');
        }
    }

    protected function registerMiddlewareInBootstrap()
    {
        $appFilePath = base_path('bootstrap/app.php');

        if (!$this->files->exists($appFilePath)) {
            $this->error("bootstrap/app.php not found!");
            return;
        }

        $content = $this->files->get($appFilePath);

        // Check if middleware already registered
        if (strpos($content, 'MetaTagsMiddleware') !== false) {
            $this->info('MetaTagsMiddleware is already registered in bootstrap/app.php');
            return;
        }

        // Add middleware registration
        $middlewareSnippet = <<<'PHP'
    ->withMiddleware(function ($middleware) {
        $middleware->append(\App\Http\Middleware\MetaTagsMiddleware::class);
    })
PHP;

        // Insert before the final '->create()' call
        $updatedContent = preg_replace(
            '/->create\(\);$/',
            $middlewareSnippet . "\n    ->create();",
            $content
        );

        $this->files->put($appFilePath, $updatedContent);
        $this->info('MetaTagsMiddleware registered in bootstrap/app.php successfully!');
    }

    protected function addMiddlewareToWebRoutes()
    {
        $webFilePath = base_path('routes/web.php');

        if (!$this->files->exists($webFilePath)) {
            $this->error("Web routes file not found: $webFilePath");
            return;
        }

        $content = $this->files->get($webFilePath);

        if (strpos($content, 'MetaTagsMiddleware') !== false) {
            $this->info('MetaTagsMiddleware routes already exist in web.php');
            return;
        }

        $routesSnippet = <<<'PHP'

// CMS Routes with MetaTagsMiddleware
Route::middleware(\App\Http\Middleware\MetaTagsMiddleware::class)->group(function () {
    Route::get('/cms-dashboard', function () {
        return view('cms.dashboard');
    });
});
PHP;

        $this->files->append($webFilePath, $routesSnippet);
        $this->info('CMS routes with MetaTagsMiddleware added to web.php');
    }
}