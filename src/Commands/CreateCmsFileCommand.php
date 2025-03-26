<?php

namespace Danaei\ShopCMS\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateCmsFileCommand extends Command
{
    protected $signature = 'cms:create {name : The name of the file} {type : The type of the file (e.g. view, controller)}';
    protected $description = 'Create CMS files like views, controllers, and more based on the given type';

    public function handle()
    {
        $name = $this->argument('name');
        $type = $this->argument('type');

        switch ($type) {
            case 'view':
                $this->createView($name);
                break;

            case 'controller':
                $this->createController($name);
                break;

            default:
                $this->error("The type '{$type}' is not supported. Supported types: view, controller.");
                break;
        }
    }

    /**
     * create view
     *
     * @param string $name
     * @return void
     */
    private function createView($name)
    {
        $path = resource_path("views/pages/$name.blade.php");

        if (File::exists($path)) {
            $this->error("The file $path already exists.");
            return;
        }

        $content = <<<'BLADE'
            @extends('layouts.main')

            <!-- Style section (optional)  -->
            <!-- Uncomment and add custom styles if needed -->
            <!-- @push('styles') 
            <link rel="stylesheet" href="{{ asset('css/custom.css') }}"> 
            @endpush  -->

            <!-- Header section  -->
            @section('header')
                 <!-- Add your header content here or leave it empty  -->
            @endsection

            <!-- Main content  -->
            @section('content')
                <div class="content">
                    <h1>Welcome to the new page!</h1>
                    <p>This is the main content area. Customize as needed.</p>
                </div>
            @endsection

            <!-- Footer section  -->
            @section('footer')
                 <!-- Add your footer content here or leave it empty  -->
            @endsection

            <!-- Script section (optional)  -->
            <!-- Uncomment and add custom scripts if needed 
            @push('scripts') 
            <script src="{{ asset('js/custom.js') }}"></script> 
            @endpush -->
        BLADE;

        File::put($path, $content);
        $this->info("Blade template $name.blade.php created successfully at $path.");
    }

    /**
     * ایجاد کنترلر
     *
     * @param string $name
     * @return void
     */
    private function createController($name)
    {
        $path = app_path("Http/Controllers/{$name}Controller.php");

        if (File::exists($path)) {
            $this->error("The controller $path already exists.");
            return;
        }

        $stub = "<?php\n\nnamespace App\Http\Controllers;\n\nuse Illuminate\Http\Request;\n\nclass {$name}Controller extends Controller\n{\n    // Controller methods go here\n}";
        File::put($path, $stub);

        $this->info("Controller {$name}Controller.php created successfully at $path.");
    }
}
