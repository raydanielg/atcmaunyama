<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ELMS-ATC') }} - Blog</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/custom.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
</head>
<body class="bg-white text-gray-900">
@include('partials.header')

<header class="relative overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('blog-hero.jpg') }}" class="w-full h-full object-cover opacity-20" alt="blog bg" />
        <div class="absolute inset-0 bg-gradient-to-b from-white via-white/80 to-white"></div>
    </div>
    <div class="relative max-w-screen-xl mx-auto px-4 py-10">
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Latest Articles</h1>
        <p class="mt-2 text-gray-600">Read updates, tips, and stories.</p>
        <form method="GET" class="mt-4 flex items-center gap-2">
            <input type="text" name="q" value="{{ $s ?? '' }}" placeholder="Search posts..." class="w-full md:w-80 border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
            <button class="px-3 py-2 rounded-lg border border-gray-300 text-sm">Search</button>
        </form>
    </div>
</header>

<main class="max-w-screen-xl mx-auto px-4 py-10">
    @if($posts->isEmpty())
        <div class="rounded-md border bg-yellow-50 text-yellow-800 p-4 text-sm">No posts yet.</div>
    @else
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($posts as $post)
                <a href="{{ route('blog.show', $post->slug) }}" class="group rounded-xl border bg-white hover:shadow transition overflow-hidden">
                    <div class="aspect-[16/9] bg-gray-100">
                        @if($post->image_path)
                            <img src="{{ asset('storage/'.$post->image_path) }}" alt="{{ $post->title }}" class="w-full h-full object-cover" />
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 group-hover:text-indigo-700 line-clamp-2">{{ $post->title }}</h3>
                        @if($post->excerpt)
                            <p class="mt-2 text-sm text-gray-600 line-clamp-3">{{ $post->excerpt }}</p>
                        @endif
                        <div class="mt-3 text-xs text-gray-500 flex items-center gap-3">
                            <span>{{ $post->created_at?->format('d M Y') }}</span>
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 4.354a4.354 4.354 0 00-4.354 4.354c0 3.507 4.354 8.163 4.354 8.163s4.354-4.656 4.354-8.163A4.354 4.354 0 0012 4.354z"/></svg>
                                {{ $post->views }} views
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-6">{{ $posts->links() }}</div>
    @endif
</main>

@include('partials.footer')
</body>
</html>
