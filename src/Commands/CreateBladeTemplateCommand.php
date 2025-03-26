<?php

namespace Danaei\ShopCMS\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateBladeTemplateCommand extends Command
{
    protected $signature = 'cms:create-blade {name : The name of the blade file}';
    protected $description = 'Generate a blade template extending the main layout with comments to guide developers.';

    public function handle()
    {
        $name = $this->argument('name');
        $path = resource_path("views/pages/$name.blade.php");

        if (File::exists($path)) {
            $this->error("The file $path already exists.");
            return;
        }

        // محتویات فایل Blade که باید ساخته شود
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
}
