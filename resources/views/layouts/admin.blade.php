<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Wazaelimu') }} — Admin</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/custom.css">
</head>
<body class="h-full">
<div class="min-h-full">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <img src="/logo.png" alt="Wazaelimu" class="h-8 w-8">
                        <span class="text-lg font-semibold text-gray-800">Wazaelimu Admin</span>
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600 hidden sm:inline">{{ auth()->user()->name ?? '' }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-[220px_1fr] gap-6">
                <!-- Sidebar -->
                <aside class="bg-white border border-gray-200 rounded-lg p-4 h-max">
                    <nav class="space-y-1">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm font-medium text-gray-900 bg-gray-100">
                            <span>Overview</span>
                        </a>
                        <span class="flex items-center gap-2 rounded-md px-3 py-2 text-sm text-gray-400 cursor-not-allowed">Users (coming soon)</span>
                        <span class="flex items-center gap-2 rounded-md px-3 py-2 text-sm text-gray-400 cursor-not-allowed">Settings (coming soon)</span>
                    </nav>
                </aside>

                <!-- Main content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>
</div>
</body>
</html>
