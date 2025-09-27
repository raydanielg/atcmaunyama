@php
    $isMobile = isset($mobile) && $mobile;
    $isAdmin = auth()->check() && auth()->user()->role === 'admin';
@endphp
<aside class="{{ $isMobile ? 'block md:hidden w-72' : 'hidden md:block w-72' }} shrink-0 border-r border-gray-200 bg-white/80 backdrop-blur h-full overflow-y-auto">
    <div class="p-4">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-semibold text-gray-800">menus</span>
            @if($isMobile)
                <button @click="$dispatch('toggle-mobile-sidebar')" class="p-2 rounded hover:bg-gray-100" aria-label="Close">
                    <span class="material-symbols-outlined text-gray-600">close</span>
                </button>
            @endif
        </div>

        <nav class="text-sm">
            <div class="text-xs font-semibold text-gray-500 px-3 mb-1 mt-3">General</div>
            <ul class="space-y-1">
                <li>
                    <a href="{{ $isAdmin ? route('dashboard') : route('user.dashboard') }}"
                       class="group flex items-center gap-3 px-3 py-2 rounded-md {{ $isAdmin ? (request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50') : (request()->routeIs('user.dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50') }}">
                        <span class="material-symbols-outlined text-indigo-600 group-[.bg-indigo-50]:text-indigo-700">dashboard</span>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    @php
                        $classesHref = $isAdmin
                            ? (Route::has('learning.classes.index') ? route('learning.classes.index') : route('classes.index'))
                            : (Route::has('user.classes.index') ? route('user.classes.index') : route('classes.index'));
                        $isActiveClasses = $isAdmin
                            ? request()->routeIs('learning.classes.*')
                            : (request()->routeIs('user.classes.*') || request()->routeIs('classes.index'));
                    @endphp
                    <a href="{{ $classesHref }}"
                       class="group flex items-center gap-3 px-3 py-2 rounded-md {{ $isActiveClasses ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <span class="material-symbols-outlined">school</span>
                        <span>Classes</span>
                    </a>
                </li>
                <li class="mt-2">
                    <div class="text-xs font-semibold text-gray-500 px-3 mb-1">Learning Materials</div>
                    <ul class="space-y-1">
                        <li>
                            @php $materialsHref = Route::has('materials.index') ? route('materials.index') : url('/materials'); @endphp
                            <a href="{{ $materialsHref }}"
                               class="group flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('materials.index') || request()->is('materials') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <span class="material-symbols-outlined">inventory_2</span>
                                <span>All Materials</span>
                            </a>
                        </li>
                        <li>
                            @if(Route::has('materials.subcategories.index'))
                            <a href="{{ route('materials.subcategories.index') }}"
                               class="group flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('materials.subcategories.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <span class="material-symbols-outlined">subdirectory_arrow_right</span>
                                <span>Material Type</span>
                            </a>
                            @endif
                        </li>
                        <li>
                            @if(Route::has('materials.subsubcategories.index'))
                            <a href="{{ route('materials.subsubcategories.index') }}"
                               class="group flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('materials.subsubcategories.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <span class="material-symbols-outlined">subdirectory_arrow_right</span>
                                <span>Material Sub Type</span>
                            </a>
                            @endif
                        </li>
                        <!-- Material Level and Material Subjects entries intentionally removed -->
                    </ul>
                </li>
                <li>
                    @php $notifHref = Route::has('mobile.notifications.index') ? route('mobile.notifications.index') : url('/notifications'); @endphp
                    <a href="{{ $notifHref }}"
                       class="group flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('mobile.notifications.*') || request()->is('notifications') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <span class="material-symbols-outlined">notifications</span>
                        <span>Notifications</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('faq.index') }}"
                       class="group flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('faq.index') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <span class="material-symbols-outlined">help</span>
                        <span>FAQ</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/about') }}"
                       class="group flex items-center gap-3 px-3 py-2 rounded-md {{ request()->is('about') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <span class="material-symbols-outlined">info</span>
                        <span>About</span>
                    </a>
                </li>
            </ul>

            @if($isAdmin)
                <div class="text-xs font-semibold text-gray-500 px-3 mb-1 mt-4">Learning</div>
                <ul class="space-y-1">
                    @if(Route::has('learning.levels.index'))
                        <li>
                            <a href="{{ route('learning.levels.index') }}"
                               class="group flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('learning.levels.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <span class="material-symbols-outlined">stacked_bar_chart</span>
                                <span>Levels</span>
                            </a>
                        </li>
                    @endif
                    
                </ul>

                <div class="text-xs font-semibold text-gray-500 px-3 mb-1 mt-4">Actions</div>
                <ul class="space-y-1">
                    @if(Route::has('learning.notes.create'))
                        <li>
                            <a href="{{ route('learning.notes.create') }}"
                               class="group flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('learning.notes.create') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <span class="material-symbols-outlined text-green-600">upload</span>
                                <span>Upload Your</span>
                            </a>
                        </li>
                    @endif
                </ul>
            @endif

            <div class="text-xs font-semibold text-gray-500 px-3 mb-1 mt-4">Community</div>
            <ul class="space-y-1">
                <li>
                    @php $feedbackHref = Route::has('feedbacks.index') ? route('feedbacks.index') : url('/feedbacks'); @endphp
                    <a href="{{ $feedbackHref }}"
                       class="group flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('feedbacks.*') || request()->is('feedbacks*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <span class="material-symbols-outlined">forum</span>
                        <span>Feedbacks</span>
                    </a>
                </li>
                <li>
                    @php $forumsHref = Route::has('forums.index') ? route('forums.index') : url('/forums'); @endphp
                    <a href="{{ $forumsHref }}"
                       class="group flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('forums.*') || request()->is('forums*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <span class="material-symbols-outlined">group</span>
                        <span>Forums</span>
                    </a>
                </li>
            </ul>

            <div class="text-xs font-semibold text-gray-500 px-3 mb-1 mt-4">Our Services</div>
            <ul class="space-y-1">
                <li>
                    @php $servicesHref = Route::has('services.index') ? route('services.index') : url('/services'); @endphp
                    <a href="{{ $servicesHref }}"
                       class="group flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('services.*') || request()->is('services') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <span class="material-symbols-outlined">design_services</span>
                        <span>Our Services</span>
                    </a>
                </li>
            </ul>

            @auth
                <div class="text-xs font-semibold text-gray-500 px-3 mb-1 mt-4">Account</div>
                <ul class="space-y-1">
                    <li>
                        @php $myResHref = Route::has('resources.my') ? route('resources.my') : url('/my-resources'); @endphp
                        <a href="{{ $myResHref }}"
                           class="group flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('resources.my') || request()->is('my-resources') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                            <span class="material-symbols-outlined">collections_bookmark</span>
                            <span>My Resources</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('profile.edit') }}"
                           class="group flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('profile.edit') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                            <span class="material-symbols-outlined">settings</span>
                            <span>Settings</span>
                        </a>
                    </li>
                    <li>
                        @php $premiumHref = Route::has('premium.index') ? route('premium.index') : url('/premium'); @endphp
                        <a href="{{ $premiumHref }}"
                           class="group flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('premium.*') || request()->is('premium') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                            <span class="material-symbols-outlined">workspace_premium</span>
                            <span>Premiums</span>
                        </a>
                    </li>
                </ul>
            @endauth
        </nav>
    </div>
</aside>
