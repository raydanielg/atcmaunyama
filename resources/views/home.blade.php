<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ELMS-ATC') }} - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/custom.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
</head>
<body class="bg-white text-gray-900">

    <!-- Top brand bar -->
    <nav class="bg-blue-900">
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl text-white px-3 py-2 sm:px-4">
            @php
                // Explicit hotline & email per request
                $phone = '255716212896';
                $email = 'info@elms-atc.co.tz';
            @endphp
            <!-- Left: Phone and Email -->
            <div class="flex items-center gap-4 sm:gap-6">
                <a href="tel:{{ $phone }}" class="inline-flex items-center gap-2 text-sm sm:text-base font-medium hover:underline">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                        <path d="M2.25 3.75A1.5 1.5 0 013.75 2.25h3A1.5 1.5 0 018.25 3.75v2.5a1.5 1.5 0 01-.44 1.06l-1.4 1.4a.75.75 0 00-.18.8 12.01 12.01 0 006.21 6.21.75.75 0 00.8-.18l1.4-1.4a1.5 1.5 0 011.06-.44h2.5a1.5 1.5 0 011.5 1.5v3a1.5 1.5 0 01-1.5 1.5h-.75c-9.112 0-16.5-7.388-16.5-16.5v-.75z"/>
                    </svg>
                    {{ $phone }}
                </a>
                <a href="mailto:{{ $email }}" class="inline-flex items-center gap-2 text-sm sm:text-base font-medium hover:underline">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                        <path d="M1.5 6.75A2.25 2.25 0 013.75 4.5h16.5A2.25 2.25 0 0122.5 6.75v10.5A2.25 2.25 0 0120.25 19.5H3.75A2.25 2.25 0 011.5 17.25V6.75zm1.91-.53a.75.75 0 00-.91 1.2l8.04 6.03a.75.75 0 00.92 0l8.04-6.03a.75.75 0 00-.91-1.2L12 12.73 3.41 6.22z"/>
                    </svg>
                    {{ $email }}
                </a>
            </div>

            <!-- Right: Socials -->
            <div class="flex items-center gap-3 sm:gap-4">
                <a href="#" target="_blank" rel="noopener" aria-label="Facebook" class="hover:text-blue-300">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                        <path d="M13.5 9H15V6h-1.5A3.5 3.5 0 0010 9.5V11H8v3h2v6h3v-6h2.1l.4-3H13v-1.3c0-.4.3-.7.7-.7z"/>
                    </svg>
                </a>
                <a href="#" target="_blank" rel="noopener" aria-label="Instagram" class="hover:text-pink-300">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                        <path d="M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm0 2a3 3 0 00-3 3v10a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H7zm5 3.5A5.5 5.5 0 1112 20.5 5.5 5.5 0 0112 7.5zm0 2A3.5 3.5 0 1015.5 13 3.5 3.5 0 0012 9.5zM18 6.75a1 1 0 110 2 1 1 0 010-2z"/>
                    </svg>
                </a>
                <a href="#" target="_blank" rel="noopener" aria-label="X" class="hover:text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                        <path d="M3 3h3.6l5.1 6.7L17.1 3H21l-7.4 9.7L21 21h-3.6l-5.3-7-5.2 7H3l7.7-10L3 3z"/>
                    </svg>
                </a>
                <a href="#" target="_blank" rel="noopener" aria-label="YouTube" class="hover:text-red-300">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                        <path d="M23.5 7.2a4 4 0 00-2.8-2.8C18.9 4 12 4 12 4s-6.9 0-8.7.4A4 4 0 00.5 7.2C0 9 0 12 0 12s0 3 .5 4.8a4 4 0 002.8 2.8C5.1 20 12 20 12 20s6.9 0 8.7-.4a4 4 0 002.8-2.8C24 15 24 12 24 12s0-3-.5-4.8zM9.8 15.5v-7l6 3.5-6 3.5z"/>
                    </svg>
                </a>
            </div>
        </div>
    </nav>

    <!-- Secondary nav (red, responsive with toggle) -->
    <nav class="bg-red-700 text-white">
        <div class="max-w-screen-xl mx-auto px-3 sm:px-4">
            <div class="flex items-center justify-between py-2">
                <!-- Left: brand placeholder (kept minimal) -->
                <a href="#top" class="hidden sm:inline-block font-semibold tracking-wide text-white/90 hover:text-white">wazaelimu tanzania</a>

                <!-- Toggle Btn on mobile -->
                <button type="button" class="sm:hidden inline-flex items-center justify-center p-2 rounded-md hover:bg-white/10 focus:outline-none" aria-controls="main-menu" aria-expanded="false" onclick="document.getElementById('main-menu').classList.toggle('hidden')">
                    <span class="sr-only">Open main menu</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Desktop actions (right) -->
                <div class="hidden sm:flex items-center gap-6 text-sm">
                    <a href="#top" class="hover:underline">Home</a>
                    <a href="#features" class="hover:underline">Features</a>
                    <a href="{{ route('faq.index') }}" class="hover:underline">FAQ</a>
                    <a href="#contact" class="hover:underline">Contact</a>
                    <a href="#why" class="hover:underline">Why Choose Us</a>
                    <a href="#feedback" class="hover:underline">Feedback</a>
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="hover:underline">Login</a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex items-center rounded-md bg-white text-red-700 px-3 py-1.5 font-semibold hover:bg-gray-100">Register</a>
                    @endif
                </div>
            </div>

            <!-- Mobile menu -->
            <div id="main-menu" class="sm:hidden hidden pb-3 border-t border-white/10">
                <ul class="flex flex-col gap-2 pt-3 text-sm">
                    <li><a href="#top" class="block px-2 py-2 rounded hover:bg-white/10">Home</a></li>
                    <li><a href="#features" class="block px-2 py-2 rounded hover:bg-white/10">Features</a></li>
                    <li><a href="{{ route('faq.index') }}" class="block px-2 py-2 rounded hover:bg-white/10">FAQ</a></li>
                    <li><a href="#contact" class="block px-2 py-2 rounded hover:bg-white/10">Contact</a></li>
                    <li><a href="#why" class="block px-2 py-2 rounded hover:bg-white/10">Why Choose Us</a></li>
                    <li><a href="#feedback" class="block px-2 py-2 rounded hover:bg-white/10">Feedback</a></li>
                    @if (Route::has('login'))
                        <li><a href="{{ route('login') }}" class="block px-2 py-2 rounded hover:bg-white/10">Login</a></li>
                    @endif
                    @if (Route::has('register'))
                        <li><a href="{{ route('register') }}" class="block px-2 py-2 rounded bg-white text-red-700 font-semibold hover:bg-gray-100">Register</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero with gradient overlay, top-right auth buttons, and background slider -->
    <section id="top" class="relative overflow-hidden">
        <!-- Background slider images -->
        <div class="absolute inset-0 -z-10" id="hero-slides">
            <img src="{{ asset('teacher-helping-kids-class_23-2148892533.jpg') }}" alt="slide 1" class="w-full h-full object-cover absolute inset-0 opacity-100 transition-opacity duration-700" />
            <img src="{{ asset('group-african-kids-paying-attention-class_23-2148892518.jpg') }}" alt="slide 2" class="w-full h-full object-cover absolute inset-0 opacity-0 transition-opacity duration-700" />
            <img src="{{ asset('group-african-kids-paying-attention-class_23-2148892516.jpg') }}" alt="slide 3" class="w-full h-full object-cover absolute inset-0 opacity-0 transition-opacity duration-700" />
            <img src="{{ asset('close-up-father-teaching-kid-write_23-2148761575.jpg') }}" alt="slide 4" class="w-full h-full object-cover absolute inset-0 opacity-0 transition-opacity duration-700" />
            <img src="{{ asset('african-woman-teaching-children-class_23-2148892564.jpg') }}" alt="slide 5" class="w-full h-full object-cover absolute inset-0 opacity-0 transition-opacity duration-700" />
            <img src="{{ asset('african-woman-teaching-children-class_23-2148892563.jpg') }}" alt="slide 6" class="w-full h-full object-cover absolute inset-0 opacity-0 transition-opacity duration-700" />
        </div>
        <!-- Gradient overlay -->
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-red-800/70 via-blue-900/60 to-blue-900/80"></div>

        <div class="max-w-screen-xl mx-auto px-4 py-14 sm:py-20 text-white">
            <!-- Top-right auth buttons -->
            <div class="flex justify-end gap-3">
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="inline-flex items-center rounded-md bg-white/10 backdrop-blur px-4 py-2 text-sm font-medium hover:bg-white/20">Login</a>
                @endif
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-flex items-center rounded-md bg-white text-red-700 px-4 py-2 text-sm font-semibold hover:bg-gray-100">Register</a>
                @endif
            </div>

            <!-- Headline and CTA -->
            <div class="mt-10 sm:mt-14 max-w-3xl">
                <h1 class="text-3xl sm:text-5xl font-bold tracking-tight">Welcome to {{ $siteSettings->site_name ?? 'Wazaelimu' }}</h1>
                <p class="mt-4 text-white/90 text-base sm:text-lg max-w-2xl">A platform to learn and manage educational content. Join today, explore the features, and start your learning journey.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('classes.index') }}" class="inline-flex items-center rounded-md bg-red-600 px-5 py-2.5 text-white hover:bg-red-700">Explore Classes</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex items-center rounded-md bg-white text-red-700 px-5 py-2.5 text-sm font-semibold hover:bg-gray-100">Get Started</a>
                    @endif
                </div>
            </div>
        </div>

        
    </section>

    <!-- Features placed immediately after hero -->
    <section id="features" class="bg-gray-50 border-t">
        <div class="max-w-screen-xl mx-auto px-4 py-14 sm:py-20">
            <div class="max-w-3xl">
                <h2 class="text-3xl font-bold tracking-tight">Features of Wazaelimu App</h2>
                <p class="mt-3 text-gray-600">Everything you need to publish, manage and learn with confidence.</p>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Card 1 -->
                <div class="feature-card rounded-xl border bg-white p-5 transition-all duration-700 opacity-0 translate-y-4" style="transition-delay: 50ms;">
                    <div class="flex items-start gap-4">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-red-100 text-red-700">
                            <!-- book icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M6 4.5A2.5 2.5 0 0 1 8.5 2h9A1.5 1.5 0 0 1 19 3.5V18a2 2 0 0 1-2 2H8a2 2 0 0 0-2 2V4.5z"/></svg>
                        </span>
                        <div>
                            <h3 class="font-semibold">Notes & Materials</h3>
                            <p class="mt-1 text-sm text-gray-600">Upload, organize and present study notes and learning resources.</p>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="feature-card rounded-xl border bg-white p-5 transition-all duration-700 opacity-0 translate-y-4" style="transition-delay: 100ms;">
                    <div class="flex items-start gap-4">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-red-100 text-red-700">
                            <!-- eye icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M12 5C7 5 2.73 8.11 1 12c1.73 3.89 6 7 11 7s9.27-3.11 11-7c-1.73-3.89-6-7-11-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/></svg>
                        </span>
                        <div>
                            <h3 class="font-semibold">Public Previews</h3>
                            <p class="mt-1 text-sm text-gray-600">Share preview pages for materials and notes on the web.</p>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="feature-card rounded-xl border bg-white p-5 transition-all duration-700 opacity-0 translate-y-4" style="transition-delay: 150ms;">
                    <div class="flex items-start gap-4">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-red-100 text-red-700">
                            <!-- download icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M12 3v10.59l3.3-3.3 1.4 1.42L12 17.41 7.3 12.7l1.4-1.41 3.3 3.3V3h2z"/><path d="M5 19h14v2H5z"/></svg>
                        </span>
                        <div>
                            <h3 class="font-semibold">Premium Downloads</h3>
                            <p class="mt-1 text-sm text-gray-600">Offer paid downloads with access control for premium content.</p>
                        </div>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="feature-card rounded-xl border bg-white p-5 transition-all duration-700 opacity-0 translate-y-4" style="transition-delay: 200ms;">
                    <div class="flex items-start gap-4">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-red-100 text-red-700">
                            <!-- phone icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.01-.24c1.11.37 2.3.57 3.58.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.4 21 3 13.6 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.28.2 2.47.57 3.58a1 1 0 0 1-.24 1.01l-2.2 2.2z"/></svg>
                        </span>
                        <div>
                            <h3 class="font-semibold">Mobile App Updates</h3>
                            <p class="mt-1 text-sm text-gray-600">Manage app versions and configuration for mobile users.</p>
                        </div>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="feature-card rounded-xl border bg-white p-5 transition-all duration-700 opacity-0 translate-y-4" style="transition-delay: 250ms;">
                    <div class="flex items-start gap-4">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-red-100 text-red-700">
                            <!-- bell icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M12 22a2.5 2.5 0 0 0 2.45-2h-4.9A2.5 2.5 0 0 0 12 22z"/><path d="M18 16v-5a6 6 0 1 0-12 0v5l-2 2v1h16v-1l-2-2z"/></svg>
                        </span>
                        <div>
                            <h3 class="font-semibold">Push Notifications</h3>
                            <p class="mt-1 text-sm text-gray-600">Send updates and announcements to students and teachers.</p>
                        </div>
                    </div>
                </div>

                <!-- Card 6 -->
                <div class="feature-card rounded-xl border bg-white p-5 transition-all duration-700 opacity-0 translate-y-4" style="transition-delay: 300ms;">
                    <div class="flex items-start gap-4">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-red-100 text-red-700">
                            <!-- tag icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M10.59 2.59 2 11.17V22h10.83l8.59-8.59L10.59 2.59zM7 17a2 2 0 1 1 .001-4.001A2 2 0 0 1 7 17z"/></svg>
                        </span>
                        <div>
                            <h3 class="font-semibold">Categories & Tags</h3>
                            <p class="mt-1 text-sm text-gray-600">Structure content for easy browsing and discovery.</p>
                        </div>
                    </div>
                </div>

                <!-- Card 7 -->
                <div class="feature-card rounded-xl border bg-white p-5 transition-all duration-700 opacity-0 translate-y-4" style="transition-delay: 350ms;">
                    <div class="flex items-start gap-4">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-red-100 text-red-700">
                            <!-- search icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zM9.5 14A4.5 4.5 0 1 1 14 9.5 4.5 4.5 0 0 1 9.5 14z"/></svg>
                        </span>
                        <div>
                            <h3 class="font-semibold">Search & Filters</h3>
                            <p class="mt-1 text-sm text-gray-600">Find the right content fast with powerful filters.</p>
                        </div>
                    </div>
                </div>

                <!-- Card 8 -->
                <div class="feature-card rounded-xl border bg-white p-5 transition-all duration-700 opacity-0 translate-y-4" style="transition-delay: 400ms;">
                    <div class="flex items-start gap-4">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-red-100 text-red-700">
                            <!-- shield icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M12 2 4 5v6c0 5.55 3.84 10.74 8 12 4.16-1.26 8-6.45 8-12V5l-8-3z"/></svg>
                        </span>
                        <div>
                            <h3 class="font-semibold">Secure Authentication</h3>
                            <p class="mt-1 text-sm text-gray-600">Protect user accounts with robust auth and roles.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section id="why" class="bg-white border-t">
        <div class="max-w-screen-xl mx-auto px-4 py-14 sm:py-20">
            <div class="max-w-3xl">
                <h2 class="text-3xl font-bold tracking-tight">Why choose us</h2>
                <p class="mt-3 text-gray-600">We are focused on accessible, high-quality learning for students and teachers across Tanzania.</p>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Point 1 -->
                <div class="feature-card rounded-xl border bg-white p-5 transition-all duration-700 opacity-0 translate-y-4" style="transition-delay: 60ms;">
                    <div class="flex items-start gap-4">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-red-100 text-red-700">
                            <!-- star icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </span>
                        <div>
                            <h3 class="font-semibold">Quality Content</h3>
                            <p class="mt-1 text-sm text-gray-600">Curated notes and materials aligned to local curricula.</p>
                        </div>
                    </div>
                </div>

                <!-- Point 2 -->
                <div class="feature-card rounded-xl border bg-white p-5 transition-all duration-700 opacity-0 translate-y-4" style="transition-delay: 120ms;">
                    <div class="flex items-start gap-4">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-red-100 text-red-700">
                            <!-- mobile icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M7 2h10a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zM7 4v16h10V4H7z"/></svg>
                        </span>
                        <div>
                            <h3 class="font-semibold">Mobile-first</h3>
                            <p class="mt-1 text-sm text-gray-600">Fast and responsive on any device, even low bandwidth.</p>
                        </div>
                    </div>
                </div>

                <!-- Point 3 -->
                <div class="feature-card rounded-xl border bg-white p-5 transition-all duration-700 opacity-0 translate-y-4" style="transition-delay: 180ms;">
                    <div class="flex items-start gap-4">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-red-100 text-red-700">
                            <!-- lock icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1a5 5 0 0 0-5 5v3H5a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-2V6a5 5 0 0 0-5-5zm3 8H9V6a3 3 0 0 1 6 0v3z"/></svg>
                        </span>
                        <div>
                            <h3 class="font-semibold">Secure & Reliable</h3>
                            <p class="mt-1 text-sm text-gray-600">Modern authentication and stable infrastructure.</p>
                        </div>
                    </div>
                </div>

                <!-- Point 4 -->
                <div class="feature-card rounded-xl border bg-white p-5 transition-all duration-700 opacity-0 translate-y-4" style="transition-delay: 240ms;">
                    <div class="flex items-start gap-4">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-red-100 text-red-700">
                            <!-- users icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                        </span>
                        <div>
                            <h3 class="font-semibold">Built for Tanzania</h3>
                            <p class="mt-1 text-sm text-gray-600">Local context, languages, and support for real classrooms.</p>
                        </div>
                    </div>
                </div>

                <!-- Point 5 -->
                <div class="feature-card rounded-xl border bg-white p-5 transition-all duration-700 opacity-0 translate-y-4" style="transition-delay: 300ms;">
                    <div class="flex items-start gap-4">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-red-100 text-red-700">
                            <!-- heart icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M12.1 21.35 10 19.28C5.4 14.36 2 11.28 2 7.5A4.5 4.5 0 0 1 6.5 3 5 5 0 0 1 12 6a5 5 0 0 1 5.5-3A4.5 4.5 0 0 1 22 7.5c0 3.78-3.4 6.86-8 11.78l-1.9 2.07z"/></svg>
                        </span>
                        <div>
                            <h3 class="font-semibold">Community & Feedback</h3>
                            <p class="mt-1 text-sm text-gray-600">Listen, iterate, and improve with teachers and learners.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="download" class="relative overflow-hidden bg-gradient-to-br from-red-50 via-white to-blue-50 border-t">
        <div class="absolute inset-0 pointer-events-none opacity-20" aria-hidden="true">
            <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-red-200 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full bg-blue-200 blur-3xl"></div>
        </div>
        <div class="relative max-w-screen-xl mx-auto px-4 py-16 sm:py-20">
            <div class="max-w-3xl text-center mx-auto">
                <h2 class="text-3xl sm:text-4xl font-bold tracking-tight">Explore our app</h2>
                <p class="mt-3 text-gray-600">Experience Wazaelimu on mobile. Optimized for speed, offline-friendly notes, and instant updates for students and teachers.</p>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-2">
                <!-- Android card -->
                <div class="group rounded-2xl border bg-white/80 backdrop-blur p-6 transition hover:shadow-xl hover:-translate-y-0.5">
                    <div class="flex items-start gap-4">
                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-green-100 text-green-700">
                            <!-- Android icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7"><path d="M17.6 9.48h.9c.83 0 1.5.67 1.5 1.5v4.2c0 .83-.67 1.5-1.5 1.5h-.9v2.1c0 .73-.6 1.32-1.33 1.32-.73 0-1.32-.59-1.32-1.32v-2.1H9.06v2.1c0 .73-.6 1.32-1.33 1.32s-1.32-.59-1.32-1.32v-2.1h-.9c-.83 0-1.5-.67-1.5-1.5v-4.2c0-.83.67-1.5 1.5-1.5h.9V8.73c0-.69.56-1.25 1.25-1.25h9.19c.69 0 1.25.56 1.25 1.25v.75zm-9.94-6.1.9-1.56a.5.5 0 0 1 .86.5l-.87 1.52m7.56-.46.9-1.56a.5.5 0 0 1 .86.5l-.87 1.52"/></svg>
                        </span>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold">Android</h3>
                            <p class="mt-1 text-sm text-gray-600">Best for most devices. Smooth performance and native sharing.</p>
                            <div class="mt-4 flex flex-wrap gap-3">
                                <a href="#" class="inline-flex items-center gap-2 rounded-md bg-red-600 px-4 py-2 text-white text-sm font-medium hover:bg-red-700">
                                    <!-- play icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    Get it on Play Store
                                </a>
                                <a href="#" class="inline-flex items-center gap-2 rounded-md border px-4 py-2 text-sm font-medium hover:bg-gray-50">
                                    APK download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- iOS card -->
                <div class="group rounded-2xl border bg-white/80 backdrop-blur p-6 transition hover:shadow-xl hover:-translate-y-0.5">
                    <div class="flex items-start gap-4">
                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gray-100 text-gray-800">
                            <!-- Apple icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7"><path d="M16.37 1.64a4.3 4.3 0 0 1-1.03 3.22 3.86 3.86 0 0 1-3.05 1.49 4.3 4.3 0 0 1 1.06-3.27A3.96 3.96 0 0 1 16.37 1.64zM20.78 17.3c-.37.86-.8 1.71-1.31 2.55-.69 1.12-1.23 1.9-1.64 2.43-.62.89-1.28 1.35-1.98 1.38-.51.02-1.13-.15-1.85-.52-.73-.37-1.41-.55-2.06-.56-.63-.01-1.33.18-2.09.56-.76.37-1.37.55-1.83.53-.67-.03-1.34-.46-2.02-1.3-.43-.53-.99-1.34-1.67-2.44a17.46 17.46 0 0 1-1.49-3.12c-.4-1.05-.6-2.06-.6-3.04 0-1.12.24-2.09.71-2.92a4.9 4.9 0 0 1 1.71-1.79 4.64 4.64 0 0 1 2.57-.78c.5 0 1.15.2 1.95.58.8.39 1.32.58 1.56.58.18 0 .72-.22 1.63-.66.88-.4 1.63-.57 2.23-.51 1.64.13 2.87.78 3.69 1.95-1.46.89-2.19 2.14-2.2 3.76 0 1.25.46 2.29 1.38 3.13a4.7 4.7 0 0 0 1.26.82 12.7 12.7 0 0 1-.41 1.62z"/></svg>
                        </span>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold">iOS</h3>
                            <p class="mt-1 text-sm text-gray-600">Optimized for iPhone & iPad. Seamless sync and notifications.</p>
                            <div class="mt-4 flex flex-wrap gap-3">
                                <a href="#" class="inline-flex items-center gap-2 rounded-md bg-gray-900 px-4 py-2 text-white text-sm font-medium hover:bg-black">
                                    <!-- apple store icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M20 17.27 18.73 20H5.27L4 17.27 9.46 5h5.08L20 17.27zM13 13h-2v2h2v-2zm0-6h-2v5h2V7z"/></svg>
                                    Download on App Store
                                </a>
                                <a href="#" class="inline-flex items-center gap-2 rounded-md border px-4 py-2 text-sm font-medium hover:bg-gray-50">
                                    TestFlight
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <p class="mt-8 text-center text-xs text-gray-500">Coming soon to more platforms. By downloading, you agree to our terms and privacy policy.</p>
        </div>
    </section>

    

    <section id="contact" class="bg-white border-t">
        <div class="max-w-screen-xl mx-auto px-4 py-12">
            <div class="max-w-3xl">
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight">Contact Us</h2>
                <p class="mt-2 text-gray-600">Questions, feedback, or support? We are ready to help.</p>
            </div>

            @if (session('subscribed'))
                <div class="mt-6 rounded-md bg-green-50 border border-green-200 p-4 text-sm text-green-800">
                    Thank you! Your subscription has been received.
                </div>
            @endif

            <div class="mt-8 grid gap-6 sm:grid-cols-2">
                <!-- Contact details card -->
                <div class="rounded-xl border bg-white p-6">
                    <h3 class="text-lg font-semibold">Contact Details</h3>
                    <ul class="mt-4 space-y-3 text-sm">
                        <li class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600" viewBox="0 0 24 24" fill="currentColor"><path d="M2.25 3.75A1.5 1.5 0 013.75 2.25h3A1.5 1.5 0 018.25 3.75v2.5a1.5 1.5 0 01-.44 1.06l-1.4 1.4a.75.75 0 00-.18.8 12.01 12.01 0 006.21 6.21.75.75 0 00.8-.18l1.4-1.4a1.5 1.5 0 011.06-.44h2.5a1.5 1.5 0 011.5 1.5v3a1.5 1.5 0 01-1.5 1.5h-.75c-9.112 0-16.5-7.388-16.5-16.5v-.75z"/></svg>
                            <a class="text-blue-600 hover:underline" href="tel:{{ preg_replace('/\s+/', '', $phone) }}">{{ $phone }}</a>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600" viewBox="0 0 24 24" fill="currentColor"><path d="M1.5 6.75A2.25 2.25 0 013.75 4.5h16.5A2.25 2.25 0 0122.5 6.75v10.5A2.25 2.25 0 0120.25 19.5H3.75A2.25 2.25 0 011.5 17.25V6.75zm1.91-.53a.75.75 0 00-.91 1.2l8.04 6.03a.75.75 0 00.92 0l8.04-6.03a.75.75 0 00-.91-1.2L12 12.73 3.41 6.22z"/></svg>
                            <a class="text-blue-600 hover:underline" href="mailto:{{ $email }}">{{ $email }}</a>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c-4.97 0-9 4.03-9 9 0 7.5 9 11 9 11s9-3.5 9-11c0-4.97-4.03-9-9-9zm0 12.5a3.5 3.5 0 110-7 3.5 3.5 0 010 7z"/></svg>
                            Dar es Salaam, Tanzania
                        </li>
                    </ul>
                </div>

                <!-- Subscribe card -->
                <div class="rounded-xl border bg-gradient-to-br from-red-50 to-blue-50 p-6">
                    <h3 class="text-lg font-semibold">Subscribe — Get Notified</h3>
                    <p class="mt-1 text-sm text-gray-600">Get updates about the app and new lessons.</p>
                    <form method="POST" action="{{ route('subscribe') }}" class="mt-4">
                        @csrf
                        <div class="flex gap-2">
                            <input type="email" name="email" placeholder="Your email" value="{{ old('email') }}" required class="w-full rounded-md border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" />
                            <button type="submit" class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-white text-sm font-medium hover:bg-red-700">Subscribe</button>
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer class="relative border-t bg-white overflow-hidden">
        <!-- single large background image -->
        <img src="{{ asset('african-woman-teaching-children-class_23-2148892563.jpg') }}" alt="footer background" class="pointer-events-none select-none absolute inset-0 w-full h-full object-cover object-center opacity-10" />
        <div class="absolute inset-0 bg-gradient-to-t from-white/80 via-white/70 to-white/90"></div>
        <div class="relative max-w-screen-xl mx-auto px-4 py-8">
            <div class="grid gap-8 grid-cols-2 sm:grid-cols-3">
                <!-- Quick Links -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 inline-block border-b-2 border-red-500 pb-1">Quick Links</h4>
                    <ul class="mt-3 space-y-2 text-sm text-gray-600">
                        <li><a class="hover:underline" href="#top">Home</a></li>
                        <li><a class="hover:underline" href="#features">Features</a></li>
                        <li><a class="hover:underline" href="#why">Why Choose Us</a></li>
                        <li><a class="hover:underline" href="#download">Download</a></li>
                        <li><a class="hover:underline" href="#contact">Contact</a></li>
                        <li><a class="hover:underline" href="{{ route('faq.index') }}">FAQ</a></li>
                    </ul>
                </div>

                <!-- Other Links -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 inline-block border-b-2 border-blue-500 pb-1">Other Links</h4>
                    <ul class="mt-3 space-y-2 text-sm text-gray-600">
                        <li><a class="hover:underline" href="https://lms.wazaelimu.co.tz" target="_blank" rel="noopener">Wazaelimu LMS</a></li>
                        <li><a class="hover:underline" href="https://ajira.wazaelimu.co.tz" target="_blank" rel="noopener">Waelimu Ajira</a></li>
                        <li><a class="hover:underline" href="https://news.wazaelimu.co.tz" target="_blank" rel="noopener">Wazaelimu News</a></li>
                    </ul>
                </div>

                <!-- Social Media / Contact -->
                <div class="col-span-2 sm:col-span-1">
                    <h4 class="text-sm font-semibold text-gray-900 inline-block border-b-2 border-gray-800 pb-1">Follow Us</h4>
                    <div class="mt-3 flex items-center gap-4 text-gray-700">
                        <a href="#" aria-label="Facebook" class="hover:text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M13.5 9H15V6h-1.5A3.5 3.5 0 0010 9.5V11H8v3h2v6h3v-6h2.1l.4-3H13v-1.3c0-.4.3-.7.7-.7z"/></svg>
                        </a>
                        <a href="#" aria-label="Instagram" class="hover:text-pink-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm0 2a3 3 0 00-3 3v10a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H7zm5 3.5A5.5 5.5 0 1112 20.5 5.5 5.5 0 0112 7.5zm0 2A3.5 3.5 0 1015.5 13 3.5 3.5 0 0012 9.5zM18 6.75a1 1 0 110 2 1 1 0 010-2z"/></svg>
                        </a>
                        <a href="#" aria-label="X" class="hover:text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M3 3h3.6l5.1 6.7L17.1 3H21l-7.4 9.7L21 21h-3.6l-5.3-7-5.2 7H3l7.7-10L3 3z"/></svg>
                        </a>
                        <a href="#" aria-label="YouTube" class="hover:text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M23.5 7.2a4 4 0 00-2.8-2.8C18.9 4 12 4 12 4s-6.9 0-8.7.4A4 4 0 00.5 7.2C0 9 0 12 0 12s0 3 .5 4.8a4 4 0 002.8 2.8C5.1 20 12 20 12 20s6.9 0 8.7-.4a4 4 0 002.8-2.8C24 15 24 12 24 12s0-3-.5-4.8zM9.8 15.5v-7l6 3.5-6 3.5z"/></svg>
                        </a>
                    </div>
                    <div class="mt-4 text-sm text-gray-700 sm:text-right">Visitors: <span class="font-medium">{{ number_format($visitorsCount ?? 0) }}</span></div>
                </div>
            </div>

            <div class="mt-6 border-t border-gray-200/70 pt-3 flex flex-col sm:flex-row items-center justify-between text-xs sm:text-sm text-gray-600">
                <div>&copy; {{ date('Y') }} Wazaelimu. All rights reserved.</div>
                <div class="mt-2 sm:mt-0 space-x-4">
                    <a href="#features" class="hover:underline">Features</a>
                    <a href="#why" class="hover:underline">Why</a>
                    <a href="{{ route('faq.index') }}" class="hover:underline">FAQ</a>
                    <a href="#contact" class="hover:underline">Contact</a>
                </div>
            </div>
        </div>
    </footer>
    <script>
        (function() {
            const container = document.getElementById('hero-slides');
            if (!container) return;
            const slides = Array.from(container.querySelectorAll('img'));
            if (slides.length < 2) return;

            let i = 0;
            const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const intervalMs = 5000;

            function show(idx) {
                slides.forEach((img, j) => {
                    img.style.opacity = (j === idx) ? '1' : '0';
                });
            }

            show(i);
            if (prefersReduced) return;

            setInterval(() => {
                i = (i + 1) % slides.length;
                show(i);
            }, intervalMs);
        })();
        
        // Reveal feature cards on scroll
        (function() {
            const cards = Array.from(document.querySelectorAll('.feature-card'));
            if (!cards.length) return;
            const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const reveal = (el) => {
                el.classList.remove('opacity-0', 'translate-y-4');
            };
            if (prefersReduced) {
                cards.forEach(reveal);
                return;
            }
            const io = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        reveal(entry.target);
                        io.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.2 });
            cards.forEach(card => io.observe(card));
        })();
    </script>
</body>
</html>
