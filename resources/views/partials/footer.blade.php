<!-- Shared footer (Quick Links + Other Links + Follow + Visitors) -->
<footer class="relative border-t bg-white overflow-hidden">
    <img src="{{ asset('african-woman-teaching-children-class_23-2148892563.jpg') }}" alt="footer background" class="pointer-events-none select-none absolute inset-0 w-full h-full object-cover object-center opacity-10" />
    <div class="absolute inset-0 bg-gradient-to-t from-white/80 via-white/70 to-white/90"></div>

    <div class="relative max-w-screen-xl mx-auto px-4 py-8">
        <div class="grid gap-8 grid-cols-2 sm:grid-cols-3">
            <!-- Quick Links -->
            <div>
                <h4 class="text-sm font-semibold text-gray-900 inline-block border-b-2 border-red-500 pb-1">Quick Links</h4>
                <ul class="mt-3 space-y-2 text-sm text-gray-600">
                    <li><a class="hover:underline" href="{{ url('/') }}">Home</a></li>
                    <li><a class="hover:underline" href="{{ route('classes.index') }}">Classes</a></li>
                    <li><a class="hover:underline" href="{{ route('faq.index') }}">FAQ</a></li>
                </ul>
            </div>

            <!-- Other Links (match home) -->
            <div>
                <h4 class="text-sm font-semibold text-gray-900 inline-block border-b-2 border-blue-500 pb-1">Other Links</h4>
                <ul class="mt-3 space-y-2 text-sm text-gray-600">
                    <li><a class="hover:underline" href="https://lms.wazaelimu.co.tz" target="_blank" rel="noopener">Wazaelimu LMS</a></li>
                    <li><a class="hover:underline" href="https://ajira.wazaelimu.co.tz" target="_blank" rel="noopener">Waelimu Ajira</a></li>
                    <li><a class="hover:underline" href="https://news.wazaelimu.co.tz" target="_blank" rel="noopener">Wazaelimu News</a></li>
                </ul>
            </div>

            <!-- Social / Visitors -->
            <div class="col-span-2 sm:col-span-1">
                <h4 class="text-sm font-semibold text-gray-900 inline-block border-b-2 border-gray-800 pb-1">Follow Us</h4>
                <div class="mt-3 flex items-center gap-4 text-gray-700">
                    <a href="#" aria-label="Facebook" class="hover:text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M13.5 9H15V6h-1.5A3.5 3.5 0 0010 9.5V11H8v3h2v6h3v-6h2.1l.4-3H13v-1.3c0-.4.3-.7.7-.7z"/></svg>
                    </a>
                    <a href="#" aria-label="Instagram" class="hover:text-pink-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm0 2a3 3 0 00-3 3v10a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H7zm5 3.5A5.5 5.5 0 1112 20.5 5.5 5.5 0 0112 7.5zm6-0.75a1 1 0 110 2 1 1 0 010-2z"/></svg>
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
            <div>&copy; {{ date('Y') }} ELMS-ATC. All rights reserved.</div>
            <div class="mt-2 sm:mt-0 space-x-4">
                <a href="{{ url('/') }}#features" class="hover:underline">Features</a>
                <a href="{{ url('/') }}#why" class="hover:underline">Why</a>
                <a href="{{ route('faq.index') }}" class="hover:underline">FAQ</a>
                <a href="{{ url('/') }}#contact" class="hover:underline">Contact</a>
            </div>
        </div>
    </div>
</footer>
