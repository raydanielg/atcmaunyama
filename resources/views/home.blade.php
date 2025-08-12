<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'wazaelimu') }} - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/custom.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
</head>
<body class="bg-white text-gray-900">

    <!-- Top brand bar -->
    <nav class="bg-white border-gray-200">
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl p-4">
            <a href="{{ route('home') }}" class="flex items-center space-x-3 rtl:space-x-reverse" aria-label="Home">
                @php
                    $logo = $siteSettings->site_icon_path ?? null;
                    $logoUrl = $logo ? Storage::url($logo) : asset('images/logo.png');
                    $siteName = $siteSettings->site_name ?? 'wazaelimu';
                    $phone = $siteSettings->site_phone ?? '+255 000 000 000';
                @endphp
                <img src="{{ $logoUrl }}" class="h-8 w-8 object-contain" alt="{{ $siteName }} Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap">{{ $siteName }}</span>
            </a>
            <div class="flex items-center space-x-6 rtl:space-x-reverse">
                <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="text-sm text-gray-500 hover:underline">{{ $phone }}</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-sm text-blue-600 hover:underline">Register</a>
                @endif
                <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">Login</a>
            </div>
        </div>
    </nav>

    <!-- Secondary nav (brand color) -->
    <nav class="bg-green-600 text-white">
        <div class="max-w-screen-xl px-4 py-3 mx-auto">
            <div class="flex items-center">
                <ul class="flex flex-row font-medium mt-0 space-x-8 rtl:space-x-reverse text-sm">
                    <li>
                        <a href="#top" class="text-white/90 hover:text-white hover:underline" aria-current="page">Home</a>
                    </li>
                    <li>
                        <a href="#features" class="text-white/90 hover:text-white hover:underline">Features</a>
                    </li>
                    <li>
                        <a href="#download" class="text-white/90 hover:text-white hover:underline">Download</a>
                    </li>
                    <li>
                        <a href="#about" class="text-white/90 hover:text-white hover:underline">About Us</a>
                    </li>
                    <li>
                        <a href="#contact" class="text-white/90 hover:text-white hover:underline">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero placeholder -->
    <section id="top" class="bg-white">
        <div class="max-w-screen-xl mx-auto px-4 py-16 sm:py-24">
            <div class="max-w-3xl">
                <h1 class="text-4xl sm:text-5xl font-bold tracking-tight">Karibu {{ $siteSettings->site_name ?? 'Wazaelimu' }}</h1>
                <p class="mt-4 text-gray-600 text-lg">Jukwaa la kujifunza na kusimamia maudhui ya kielimu. Anza na kufahamu huduma zetu na vipengele vilivyopo.</p>
                <div class="mt-8 flex gap-3">
                    <a href="#features" class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-white hover:bg-green-700">Explore Features</a>
                    <a href="{{ route('login') }}" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50">Get Started</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Sections placeholders -->
    <section id="about" class="bg-gray-50 border-t">
        <div class="max-w-screen-xl mx-auto px-4 py-12">
            <h2 class="text-2xl font-semibold mb-3">About Us</h2>
            <p class="text-gray-600">Maelezo mafupi kuhusu {{ $siteSettings->site_name ?? 'Wazaelimu' }}.</p>
        </div>
    </section>

    <section id="download" class="bg-white border-t">
        <div class="max-w-screen-xl mx-auto px-4 py-12">
            <h2 class="text-2xl font-semibold mb-3">Download</h2>
            <p class="text-gray-600">Pakua programu ya simu au nyaraka muhimu hapa. (Placeholder)</p>
            <div class="mt-4">
                <a href="#" class="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-white hover:bg-black text-sm">Download App</a>
            </div>
        </div>
    </section>

    <section id="features" class="bg-gray-50 border-t">
        <div class="max-w-screen-xl mx-auto px-4 py-12">
            <h2 class="text-2xl font-semibold mb-6">Features</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="rounded-lg border p-5">
                    <h3 class="font-semibold">Notes & Materials</h3>
                    <p class="text-sm text-gray-600 mt-2">Pakua na onyesha maudhui ya kielimu kwa urahisi.</p>
                </div>
                <div class="rounded-lg border p-5">
                    <h3 class="font-semibold">Mobile App Settings</h3>
                    <p class="text-sm text-gray-600 mt-2">Sanidi matoleo na arifa za programu ya simu.</p>
                </div>
                <div class="rounded-lg border p-5">
                    <h3 class="font-semibold">Premium Access</h3>
                    <p class="text-sm text-gray-600 mt-2">Huduma za malipo kwa maudhui ya ziada.</p>
                </div>
            </div>
            <div class="mt-8">
                <a href="{{ route('login') }}" class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-white hover:bg-green-700">Get Started</a>
            </div>
        </div>
    </section>

    <section id="contact" class="bg-white border-t">
        <div class="max-w-screen-xl mx-auto px-4 py-12">
            <h2 class="text-2xl font-semibold mb-3">Contact</h2>
            <p class="text-gray-600">Wasiliana nasi kwa simu: <a class="text-blue-600 hover:underline" href="tel:{{ preg_replace('/\s+/', '', $phone) }}">{{ $phone }}</a> au barua pepe: {{ $siteSettings->contact_email ?? 'support@example.com' }}.</p>
        </div>
    </section>

    <footer class="border-t">
        <div class="max-w-screen-xl mx-auto px-4 py-8 text-sm text-gray-500">&copy; {{ date('Y') }} wazaelimu. All rights reserved.</div>
    </footer>
</body>
</html>
