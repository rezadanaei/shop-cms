<?php

namespace Danaei\ShopCMS\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCMSCommand extends Command
{
    protected $signature = 'cms:install';
    protected $description = 'Install the CMS and create the default architecture in the main project';

    public function handle()
    {
        $this->info('Starting CMS installation...');

        $viewsPath = resource_path('views');
        $stubsPath = __DIR__ . '/../stubs/views';

        // Copy layout, component, and page files
        $this->copyStubFiles($stubsPath, $viewsPath);
        $this->createMiddlewareGroup();
        $this->addMiddlewareToWebRoutes();
        
        $this->info('CMS installed successfully!');
    }

    protected function copyStubFiles($source, $destination)
    {
        $this->info("Copying views to: $destination");
        File::copyDirectory($source, $destination);
        $this->info('View structure copied successfully!');
    }

    protected function createMiddlewareGroup()
    {
        $appPath = base_path('bootstrap/app.php');

        // Check if the app.php file exists
        if (!File::exists($appPath)) {
            $this->error("App configuration file not found: $appPath");
            return;
        }

        $content = File::get($appPath);

        // Add the MetaTagsMiddleware to the middleware group
        $middlewareGroupSnippet = <<<'PHP'

        // Middleware group for Meta Tags handling
        $app->middleware([
            \App\Http\Middleware\MetaTagsMiddleware::class,
        ]);
PHP;

        // Check if middleware is already added
        if (strpos($content, 'MetaTagsMiddleware') !== false) {
            $this->info('MetaTagsMiddleware already added in app.php.');
            return;
        }

        // Append middleware group to the app.php
        $content .= "\n" . $middlewareGroupSnippet;
        File::put($appPath, $content);

        $this->info('MetaTagsMiddleware added to app.php.');
    }

    protected function addMiddlewareToWebRoutes()
    {
        $webFilePath = base_path('routes/web.php');

        if (!File::exists($webFilePath)) {
            $this->error("Web routes file not found: $webFilePath");
            return;
        }

        $content = File::get($webFilePath);

        // Append the middleware group to the end of web.php with a note for the developer
        $middlewareGroupSnippet = <<<'PHP'

/*
|--------------------------------------------------------------------------
| Site-wide Middleware (MetaTagsMiddleware)
|--------------------------------------------------------------------------
|
| Below is the middleware group `site-meta` added to the last section of the routes file.
| Please make sure to add any routes that need specific meta tags here.
|
| Example: Routes created via `cms:create --view` command can be added here.
|
*/
Route::middleware(['meta-tags'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
});
PHP;

        // Check if the snippet is already present
        if (strpos($content, 'Site-wide Middleware (MetaTagsMiddleware)') !== false) {
            $this->info('Middleware group `site-meta` already added to web.php.');
            return;
        }

        // Append middleware group to the end of the file
        $content .= $middlewareGroupSnippet;
        File::put($webFilePath, $content);

        $this->info('MetaTagsMiddleware group added to the end of web.php.');
    }
}
