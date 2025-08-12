<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php($__admin = \App\Models\AdminSetting::query()->first())
        @php($__siteTitle = $__admin->site_name ?? config('app.name', 'wazaelimu'))
        @php($__siteUrl = $__admin->site_url ?? config('app.url'))
        @php($__favicon = ($__admin && $__admin->favicon_path) ? Storage::url($__admin->favicon_path) : '/favicon.ico')
        @php($__logo = ($__admin && $__admin->site_icon_path) ? Storage::url($__admin->site_icon_path) : '/logo.png')
        @php($__meta = (array) ($__admin->meta ?? []))
        @php($__desc = $__meta['description'] ?? 'Sign in to access the WazaElimu admin panel and dashboard.')
        @php($__keywords = $__meta['keywords'] ?? 'education, admin, dashboard, wazaelimu, learning')

        <title>{{ $__siteTitle }} — {{ $__meta['title_suffix'] ?? 'Login' }}</title>
        <meta name="description" content="{{ $__desc }}">
        <meta name="keywords" content="{{ $__keywords }}">
        <meta name="author" content="{{ $__admin->contact_email ?? 'admin@wazaelimu' }}">
        <meta name="robots" content="index,follow">
        <link rel="canonical" href="{{ request()->url() }}">
        <meta name="theme-color" content="#111827">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:title" content="{{ $__siteTitle }}">
        <meta property="og:description" content="{{ $__desc }}">
        <meta property="og:url" content="{{ $__siteUrl ?? request()->root() }}">
        <meta property="og:image" content="{{ $__logo }}">

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $__siteTitle }}">
        <meta name="twitter:description" content="{{ $__desc }}">
        <meta name="twitter:image" content="{{ $__logo }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Favicon -->
        <link rel="icon" href="{{ $__favicon }}" sizes="any">
        <link rel="apple-touch-icon" href="{{ $__favicon }}">

        <!-- Styles (no Vite) -->
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="/css/custom.css">
    </head>
    <body class="h-full font-sans antialiased">
        <!-- Global Page Loader -->
        <div id="globalPageLoader" class="fixed inset-0 z-[9999] flex items-center justify-center bg-white/60 backdrop-blur-sm">
            <span class="loading loading-ring loading-xs text-green-600"></span>
            <span class="sr-only">Loading...</span>
        </div>
        <div class="login-page">
            <div class="login-bg"></div>
            <div class="login-overlay"></div>
            <div class="login-foreground">
                <div class="login-wrapper">
                    <div class="login-card">
                        <a href="/">
                            <img src="{{ $__logo }}" alt="Logo" class="login-logo" />
                        </a>
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
        <script>
        (function(){
            const loader = document.getElementById('globalPageLoader');
            function show(){ loader?.classList.remove('hidden'); loader?.classList.add('flex'); }
            function hide(){ loader?.classList.add('hidden'); loader?.classList.remove('flex'); }
            window.addEventListener('load', hide);
            document.addEventListener('click', function(e){
                const a = e.target.closest('a');
                if (!a) return;
                const href = a.getAttribute('href');
                if (!href || href.startsWith('#') || a.target === '_blank') return;
                const url = new URL(href, window.location.origin);
                if (url.origin !== window.location.origin) return;
                if (e.defaultPrevented || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0) return;
                show();
            }, true);
            document.addEventListener('submit', function(){ show(); }, true);
        })();
        </script>
    </body>
    
</html>
