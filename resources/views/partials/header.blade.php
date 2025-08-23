<!-- Shared header: Top brand bar + Secondary red nav -->
<nav class="bg-blue-900">
    <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl text-white px-3 py-2 sm:px-4">
        @php
            $phone = '255716212896';
            $email = 'info@wazaelimu.co.tz';
        @endphp
        <div class="flex items-center gap-4 sm:gap-6">
            <a href="tel:{{ $phone }}" class="inline-flex items-center gap-2 text-sm sm:text-base font-medium hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5"><path d="M2.25 3.75A1.5 1.5 0 013.75 2.25h3A1.5 1.5 0 018.25 3.75v2.5a1.5 1.5 0 01-.44 1.06l-1.4 1.4a.75.75 0 00-.18.8 12.01 12.01 0 006.21 6.21.75.75 0 00.8-.18l1.4-1.4a1.5 1.5 0 011.06-.44h2.5a1.5 1.5 0 011.5 1.5v3a1.5 1.5 0 01-1.5 1.5h-.75c-9.112 0-16.5-7.388-16.5-16.5v-.75z"/></svg>
                {{ $phone }}
            </a>
            <a href="mailto:{{ $email }}" class="inline-flex items-center gap-2 text-sm sm:text-base font-medium hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5"><path d="M1.5 6.75A2.25 2.25 0 013.75 4.5h16.5A2.25 2.25 0 0122.5 6.75v10.5A2.25 2.25 0 0120.25 19.5H3.75A2.25 2.25 0 011.5 17.25V6.75zm1.91-.53a.75.75 0 00-.91 1.2l8.04 6.03a.75.75 0 00.92 0l8.04-6.03a.75.75 0 00-.91-1.2L12 12.73 3.41 6.22z"/></svg>
                {{ $email }}
            </a>
        </div>
        <div class="flex items-center gap-3 sm:gap-4">
            <a href="#" target="_blank" rel="noopener" aria-label="Facebook" class="hover:text-blue-300">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M13.5 9H15V6h-1.5A3.5 3.5 0 0010 9.5V11H8v3h2v6h3v-6h2.1l.4-3H13v-1.3c0-.4.3-.7.7-.7z"/></svg>
            </a>
            <a href="#" target="_blank" rel="noopener" aria-label="Instagram" class="hover:text-pink-300">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm0 2a3 3 0 00-3 3v10a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H7zm5 3.5A5.5 5.5 0 1112 20.5 5.5 5.5 0 0112 7.5zm6-0.75a1 1 0 110 2 1 1 0 010-2z"/></svg>
            </a>
            <a href="#" target="_blank" rel="noopener" aria-label="X" class="hover:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M3 3h3.6l5.1 6.7L17.1 3H21l-7.4 9.7L21 21h-3.6l-5.3-7-5.2 7H3l7.7-10L3 3z"/></svg>
            </a>
            <a href="#" target="_blank" rel="noopener" aria-label="YouTube" class="hover:text-red-300">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M23.5 7.2a4 4 0 00-2.8-2.8C18.9 4 12 4 12 4s-6.9 0-8.7.4A4 4 0 00.5 7.2C0 9 0 12 0 12s0 3 .5 4.8a4 4 0 002.8 2.8C5.1 20 12 20 12 20s6.9 0 8.7-.4a4 4 0 002.8-2.8C24 15 24 12 24 12s0-3-.5-4.8zM9.8 15.5v-7l6 3.5-6 3.5z"/></svg>
            </a>
        </div>
    </div>
</nav>

<nav class="bg-red-700 text-white">
    <div class="max-w-screen-xl mx-auto px-3 sm:px-4">
        <div class="flex items-center justify-between py-2">
            <a href="{{ url('/') }}" class="hidden sm:inline-block font-semibold tracking-wide text-white/90 hover:text-white">{{ config('app.name', 'Wazaelimu') }}</a>

            <button type="button" class="sm:hidden inline-flex items-center justify-center p-2 rounded-md hover:bg-white/10 focus:outline-none" aria-controls="main-nav-menu" aria-expanded="false" onclick="document.getElementById('main-nav-menu').classList.toggle('hidden')">
                <span class="sr-only">Open main menu</span>
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div class="hidden sm:flex items-center gap-6 text-sm">
                @php
                    $isAdmin = auth()->check() && auth()->user()->role === 'admin';
                    $classesHref = $isAdmin
                        ? (Route::has('learning.classes.index') ? route('learning.classes.index') : route('classes.index'))
                        : (auth()->check() && Route::has('user.classes.index') ? route('user.classes.index') : route('classes.index'));
                @endphp
                <a href="{{ url('/') }}" class="hover:underline">Home</a>
                <a href="{{ $classesHref }}" class="hover:underline">Classes</a>
                <a href="{{ route('faq.index') }}" class="hover:underline">FAQ</a>
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="hover:underline">Login</a>
                @endif
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-flex items-center rounded-md bg-white/10 backdrop-blur px-3 py-1.5 font-semibold hover:bg-white/20">Register</a>
                @endif
            </div>
        </div>

        <div id="main-nav-menu" class="sm:hidden hidden pb-3 border-t border-white/10">
            <ul class="flex flex-col gap-2 pt-3 text-sm">
                @php
                    $isAdmin = auth()->check() && auth()->user()->role === 'admin';
                    $classesHref = $isAdmin
                        ? (Route::has('learning.classes.index') ? route('learning.classes.index') : route('classes.index'))
                        : (auth()->check() && Route::has('user.classes.index') ? route('user.classes.index') : route('classes.index'));
                @endphp
                <li><a href="{{ url('/') }}" class="block px-2 py-2 rounded hover:bg-white/10">Home</a></li>
                <li><a href="{{ $classesHref }}" class="block px-2 py-2 rounded hover:bg-white/10">Classes</a></li>
                <li><a href="{{ route('faq.index') }}" class="block px-2 py-2 rounded hover:bg-white/10">FAQ</a></li>
                @if (Route::has('login'))
                    <li><a href="{{ route('login') }}" class="block px-2 py-2 rounded hover:bg-white/10">Login</a></li>
                @endif
                @if (Route::has('register'))
                    <li><a href="{{ route('register') }}" class="block px-2 py-2 rounded hover:bg-white/10">Register</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>
