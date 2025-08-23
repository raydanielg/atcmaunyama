<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles (no Vite) -->
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="/css/custom.css">
        <!-- Google Material Symbols -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,400,0,0" />
        <style>[x-cloak]{ display: none !important; }</style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100" x-data="{ mobileSidebar: false }" x-on:toggle-mobile-sidebar.window="mobileSidebar = !mobileSidebar">
            @include('layouts.navigation')

            <!-- Mobile Sidebar Off-canvas -->
            <div x-show="mobileSidebar" x-cloak class="fixed inset-0 z-40">
                <div class="absolute inset-0 bg-black/40" @click="mobileSidebar=false" aria-hidden="true"></div>
                <div class="absolute inset-y-0 left-0 w-64 bg-white shadow-xl" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
                    @include('partials.sidebar', ['mobile' => true])
                </div>
            </div>

            <div class="flex">
                @include('partials.sidebar')
                <div class="flex-1 min-w-0">
                    <!-- Page Heading -->
                    @isset($header)
                        <header class="bg-white shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <!-- Page Content -->
                    <main class="p-4">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
        <!-- Alpine.js for dropdowns and responsive nav -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </body>
</html>
