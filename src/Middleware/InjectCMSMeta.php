<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MetaTagsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('slug') ?? '/';

        $pageSettings = Page::where('slug', $slug)->first();
        
        if ($pageSettings) {
            view()->share('title', $pageSettings->title ?? null);
            view()->share('description', $pageSettings->description ?? null);
            view()->share('keywords', $pageSettings->keywords ?? null);
            view()->share('robots_index', $pageSettings->robots_index ?? 'index, follow');
            $additional_meta = '';

            if (isset($pageSettings->og_title)) {
                $additional_meta .= '<meta property="og:title" content="' . e($pageSettings->og_title) . '">';
            }
            if (isset($pageSettings->og_description)) {
                $additional_meta .= '<meta property="og:description" content="' . e($pageSettings->og_description) . '">';
            }
            if (isset($pageSettings->og_image)) {
                $additional_meta .= '<meta property="og:image" content="' . asset($pageSettings->og_image) . '">';
            }
            if (isset($pageSettings->twitter_card)) {
                $additional_meta .= '<meta name="twitter:card" content="' . e($pageSettings->twitter_card) . '">';
            }
            if (isset($pageSettings->canonical_url)) {
                $additional_meta .= '<link rel="canonical" href="' . e($pageSettings->canonical_url) . '">';
            }
            if (isset($pageSettings->hreflang)) {
                $additional_meta .= '<link rel="alternate" href="' . e($pageSettings->hreflang) . '">';
            }
            if (isset($pageSettings->schema_markup)) {
                $additional_meta .= $pageSettings->schema_markup; 
            }
        
            view()->share('additional_meta', $additional_meta);
        
            }
            
            return $next($request);
    }
  
}
