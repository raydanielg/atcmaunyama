<nav x-data="{ open: false }" class="backdrop-blur bg-white/60 border-b border-white/30 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                        <img src="{{ asset('logo.png') }}" alt="Wazaelimu" class="h-9 w-auto" />
                        <span class="font-semibold text-gray-800 group-hover:text-gray-900">Wazaelimu</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        @php($isAdmin = auth()->user()?->role === 'admin')
                        @if($isAdmin)
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Admin') }}
                            </x-nav-link>
                        @else
                            <x-nav-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                        @endif
                        <x-nav-link :href="route('classes.index')" :active="request()->routeIs('classes.index')">
                            {{ __('Classes') }}
                        </x-nav-link>
                        <x-nav-link :href="route('faq.index')" :active="request()->routeIs('faq.index')">
                            {{ __('FAQ') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-3 px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-full text-gray-700 bg-white/70 hover:bg-white focus:outline-none transition ease-in-out duration-150 shadow">
                            @php($u = Auth::user())
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-indigo-600 text-white font-semibold">
                                {{ strtoupper(mb_substr($u?->name ?? 'U', 0, 1)) }}
                            </span>
                            <div class="hidden md:block max-w-[160px] truncate">{{ $u?->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            <span class="inline-flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h6.75m-6.75 4.5h6.75m-6.75 4.5H16m-8.25-9H7.5m.75 4.5H7.5m.75 4.5H7.5m3.75 3.75h6a2.25 2.25 0 0 0 2.25-2.25v-12A2.25 2.25 0 0 0 17.25 3h-6A2.25 2.25 0 0 0 9 5.25v12A2.25 2.25 0 0 0 11.25 19.5Z"/></svg>
                                {{ __('Settings') }}
                            </span>
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('profile.edit')">
                            <span class="inline-flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75A2.25 2.25 0 0 0 14.25 4.5h-6A2.25 2.25 0 0 0 6 6.75v10.5A2.25 2.25 0 0 0 8.25 19.5h6a2.25 2.25 0 0 0 2.25-2.25V13.5m-6 0 7.5-7.5m0 0H15m3 0V9"/></svg>
                                {{ __('Change Password') }}
                            </span>
                        </x-dropdown-link>

                        @php($isAdmin = Auth::user()?->role === 'admin')
                        @if($isAdmin)
                            <x-dropdown-link :href="route('mobile.api')">
                                <span class="inline-flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                                    {{ __('API') }}
                                </span>
                            </x-dropdown-link>
                        @endif

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <span class="inline-flex items-center gap-2 text-red-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                                    {{ __('Logout') }}
                                </span>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="$dispatch('toggle-mobile-sidebar'); open = false" aria-label="Open menu" class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-gray-800 hover:bg-white/60 focus:outline-none focus:bg-white/70 focus:text-gray-800 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                @php($isAdmin = auth()->user()?->role === 'admin')
                @if($isAdmin)
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Admin') }}
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                @endif
                <x-responsive-nav-link :href="route('classes.index')" :active="request()->routeIs('classes.index')">
                    {{ __('Classes') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('faq.index')" :active="request()->routeIs('faq.index')">
                    {{ __('FAQ') }}
                </x-responsive-nav-link>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4 flex items-center gap-3">
                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-indigo-600 text-white font-semibold">
                    {{ strtoupper(mb_substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </span>
                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    <span class="inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h6.75m-6.75 4.5h6.75m-6.75 4.5H16m-8.25-9H7.5m.75 4.5H7.5m.75 4.5H7.5m3.75 3.75h6a2.25 2.25 0 0 0 2.25-2.25v-12A2.25 2.25 0 0 0 17.25 3h-6A2.25 2.25 0 0 0 9 5.25v12A2.25 2.25 0 0 0 11.25 19.5Z"/></svg>
                        {{ __('Settings') }}
                    </span>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('profile.edit')">
                    <span class="inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75A2.25 2.25 0 0 0 14.25 4.5h-6A2.25 2.25 0 0 0 6 6.75v10.5A2.25 2.25 0 0 0 8.25 19.5h6a2.25 2.25 0 0 0 2.25-2.25V13.5m-6 0 7.5-7.5m0 0H15m3 0V9"/></svg>
                        {{ __('Change Password') }}
                    </span>
                </x-responsive-nav-link>
                @php($isAdmin = Auth::user()?->role === 'admin')
                @if($isAdmin)
                    <x-responsive-nav-link :href="route('mobile.api')">
                        <span class="inline-flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                            {{ __('API') }}
                        </span>
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        <span class="inline-flex items-center gap-2 text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                            {{ __('Logout') }}
                        </span>
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
