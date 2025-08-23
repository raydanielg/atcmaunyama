<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'wazaelimu') }} - Classes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/custom.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
</head>
<body class="bg-white text-gray-900">
    @include('partials.header')

    <!-- Page header -->
    <header class="relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('african-woman-teaching-children-class_23-2148892564.jpg') }}" class="w-full h-full object-cover opacity-20" alt="classes bg" />
            <div class="absolute inset-0 bg-gradient-to-b from-white via-white/80 to-white"></div>
        </div>
        <div class="relative max-w-screen-xl mx-auto px-4 py-10">
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Explore Classes</h1>
            <p class="mt-2 text-gray-600">Browse available classes and start learning today.</p>
            <!-- Breadcrumbs (colored with icons) -->
            <nav class="mt-4 text-sm" aria-label="Breadcrumb">
                <ol class="flex items-center gap-2 text-gray-700">
                    <li>
                        <a href="{{ url('/') }}" class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-blue-50 text-blue-700 hover:bg-blue-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3l8 7h-2v8h-5v-5H11v5H6v-8H4l8-7z"/></svg>
                            Home
                        </a>
                    </li>
                    <li class="text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M9 18l6-6-6-6"/></svg>
                    </li>
                    <li>
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-red-50 text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M4 6a2 2 0 012-2h10a2 2 0 012 2v12.5a.5.5 0 01-.77.42L12 15.5l-7.23 3.42A.5.5 0 014 18.5V6z"/></svg>
                            Classes
                        </span>
                    </li>
                </ol>
            </nav>
        </div>
    </header>

    <!-- Classes grid -->
    <main class="max-w-screen-xl mx-auto px-4 py-10">
        @if($classes->isEmpty())
            <div class="rounded-md border bg-yellow-50 text-yellow-800 p-4 text-sm">No classes available yet. Please check back later.</div>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($classes as $idx => $class)
                    @php
                        $colors = [
                            ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'iconBg' => 'bg-red-100'],
                            ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'iconBg' => 'bg-blue-100'],
                            ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'iconBg' => 'bg-emerald-100'],
                            ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'iconBg' => 'bg-purple-100'],
                            ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'iconBg' => 'bg-amber-100'],
                        ];
                        $c = $colors[$idx % count($colors)];
                    @endphp
                    <div class="rounded-xl border {{ $c['bg'] }} p-5 hover:shadow transition">
                        <div class="flex items-start gap-4">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg {{ $c['iconBg'] }} {{ $c['text'] }}">
                                <!-- class icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M4 6a2 2 0 012-2h10a2 2 0 012 2v12.5a.5.5 0 01-.77.42L12 15.5l-7.23 3.42A.5.5 0 014 18.5V6z"/></svg>
                            </span>
                            <div class="min-w-0">
                                <h3 class="font-semibold text-gray-900 truncate">{{ $class->name }}</h3>
                                @if($class->subject)
                                    <p class="mt-0.5 text-xs text-gray-600">Primary Subject: <span class="font-medium">{{ $class->subject->name }}</span></p>
                                @endif
                                @if($class->subjects && $class->subjects->count())
                                    @php
                                        $subjectNames = $class->subjects->pluck('name');
                                        $extraCount = max($class->subjects->count() - 3, 0);
                                    @endphp
                                    <p class="mt-0.5 text-xs text-gray-600">
                                        Additional: {{ $subjectNames->take(3)->join(', ') }}
                                        @if($extraCount > 0)
                                            , +{{ $extraCount }} more
                                        @endif
                                    </p>
                                @endif
                                @if($class->description)
                                    <p class="mt-2 text-sm text-gray-700 line-clamp-3">{{ $class->description }}</p>
                                @else
                                    <p class="mt-2 text-sm text-gray-700">No description provided yet.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    @include('partials.footer')
</body>
</html>
