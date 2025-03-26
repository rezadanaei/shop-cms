<!DOCTYPE html>
<html lang="fa" dir="{{ app()->getLocale() == 'fa' ? 'ltr' : 'rtl' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- Meta tag settings --}}
    <meta name="description" content="{{ $description ?? $settings->meta_description ?? 'Default meta description' }}">
    <meta name="keywords" content="{{ $keywords ?? $settings->meta_keywords ?? 'default, keywords' }}">
    
    {{-- Page title --}}
    <title>{{ $title ?? $settings->title ?? 'Default Title' }}</title>

    {{-- Meta tags for page indexing --}}
    <meta name="robots" content="{{ $robots_index ?? $settings->robots_index ?? 'index, follow' }}">


    {{-- Link to styles --}}
<link rel="stylesheet" href="@yield('style', asset('css/app.css'))">
    @stack('styles')

    {{-- Additional meta tags and resources --}}
    @if(isset($additional_meta))
        {!! $additional_meta !!}
    @endif

</head>
<body>
    {{-- Header content --}}
    @hasSection('header')
        @yield('header')
    @endif
    {{-- Main content --}}
    <div class="container">
        @yield('content')
    </div>

    {{-- Footer --}}
    @hasSection('footer')
        @yield('footer')
    @endif
  
    {{-- Scripts --}}
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
