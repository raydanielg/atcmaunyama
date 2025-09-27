 <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php($__admin = \App\Models\AdminSetting::query()->first())
    <title>{{ $__admin->site_name ?? config('app.name', 'ELMS-ATC') }} — Admin</title>
    @php($__favicon = '/favicon.ico')
    <link rel="icon" type="image/x-icon" href="{{ $__favicon }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/custom.css">
    <!-- Material Symbols (Google Icons) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,300..600,0..1,-50..200" />
</head>
<body class="h-full">
<!-- Global Page Loader -->
<div id="globalPageLoader" class="fixed inset-0 z-[9999] flex items-center justify-center bg-white/60 backdrop-blur-sm">
    <span class="loading loading-ring loading-md text-green-600"></span>
    <span class="sr-only">Loading...</span>
</div>

<div class="min-h-full">
    <!-- Primary Header -->
    @php($fixed = false)
    <header class="bg-white border-b border-gray-200 text-gray-900 ">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-3 h-14 items-center gap-3">
                <div class="flex items-center gap-3">
                    <button type="button" class="md:hidden inline-flex items-center justify-center rounded-md p-2 hover:bg-gray-100 focus:outline-none" onclick="window.__toggleSidebar()">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 5h14a1 1 0 000-2H3a1 1 0 100 2zm14 4H3a1 1 0 000 2h14a1 1 0 100-2zm0 6H3a1 1 0 000 2h14a1 1 0 100-2z" clip-rule="evenodd"/></svg>
                    </button>
                    <a href="{{ route('dashboard') }}" class="hidden md:flex items-center gap-2">
                        @php($__logo = '/logo.png')
                        <img src="{{ $__logo }}" alt="logo" class="h-8 w-8 rounded">
                        <span class="text-base font-semibold leading-none">{{ $__admin->site_name ?? 'ELMS-ATC' }}</span>
                    </a>
                </div>

    @if(session('status'))
        <div id="toast-status" class="fixed top-3 right-3 z-[80] bg-white border border-green-200 text-green-800 shadow-lg rounded-lg px-4 py-3 flex items-center gap-2">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <span class="text-sm">{{ session('status') }}</span>
        </div>
        <script>
            setTimeout(() => {
                const t = document.getElementById('toast-status');
                if (t) t.classList.add('hidden');
            }, 3000);
        </script>
    @endif
                <!-- Mobile centered title -->
                <div class="md:hidden flex items-center justify-center">
                    <span class="text-sm font-semibold text-gray-900 truncate">{{ $__admin->site_name ?? 'ELMS-ATC' }}</span>
                </div>
                <div class="hidden md:flex items-center">
                    <div class="relative w-full max-w-md" id="menuSearchRoot">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.414-1.414l3.387 3.387a1 1 0 01-1.414 1.414l-3.387-3.387zM14 8a6 6 0 11-12 0 6 6 0 0112 0z" clip-rule="evenodd"/></svg>
                        </span>
                        <input id="menuSearchInput" type="text" placeholder="Search menus..." class="w-full rounded-lg border border-gray-300 pl-9 pr-3 py-1.5 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500" autocomplete="off" />
                        <div id="menuSearchResults" class="absolute mt-1 left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg max-h-80 overflow-auto hidden z-50"></div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-4">
                    <button class="relative text-gray-500 hover:text-gray-700" title="Notifications">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z"/><path d="M8 16a2 2 0 104 0H8z"/></svg>
                        <span class="sr-only">Notifications</span>
                    </button>
                    @php($__avatar = null)
                    <div class="relative" id="userMenuRoot">
                        <button type="button" class="flex items-center gap-2 group" onclick="window.__toggleUserMenu(event)">
                            <div class="w-9 h-9 rounded-full ring-2 ring-offset-2 ring-green-500 ring-offset-white overflow-hidden bg-gray-100 grid place-items-center">
                                @if($__avatar)
                                    <img src="{{ $__avatar }}" alt="avatar" class="w-full h-full object-cover">
                                @else
                                    <img src="https://img.daisyui.com/images/profile/demo/spiderperson@192.webp" alt="avatar" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <!-- Name hidden; shown inside dropdown after tap -->
                            <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                        </button>
                        <div id="userMenu" class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-lg py-1 hidden">
                            <div class="px-3 py-3 border-b border-gray-200">
                                <div class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name ?? 'User' }}</div>
                                @if(auth()->user()?->email)
                                    <div class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</div>
                                @endif
                            </div>
                            <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <span class="material-symbols-outlined text-[18px]">settings</span>
                                <span>Settings</span>
                            </a>
                            <button type="button" class="w-full flex items-center gap-3 px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50" onclick="window.__openPwdModal()">
                                <span class="material-symbols-outlined text-[18px]">lock</span>
                                <span>Change Password</span>
                            </button>
                            <div class="my-1 border-t border-gray-200"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-left text-sm text-red-600 hover:bg-red-50">
                                    <span class="material-symbols-outlined text-[18px]">logout</span>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Change Password Modal -->
    <div id="pwdModal" class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/40" onclick="window.__closePwdModal()"></div>
        <div class="relative min-h-full w-full grid place-items-center p-4">
            <div class="bg-white w-full max-w-md rounded-xl shadow-lg overflow-hidden">
                <div class="px-4 py-3 border-b flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">Change Password</h3>
                    <button type="button" class="p-1 rounded hover:bg-gray-100" onclick="window.__closePwdModal()">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.profile.password') }}" class="p-4 space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Current Password</label>
                        <input type="password" name="current_password" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required />
                        @error('current_password')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" name="password" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required />
                        @error('password')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required />
                    </div>
                    <div class="pt-2 flex items-center justify-end gap-2">
                        <button type="button" class="px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50" onclick="window.__closePwdModal()">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="py-4">
        <!-- Mobile sidebar overlay -->
        <div id="admin-sidebar-overlay" class="fixed inset-0 bg-black/30 z-30 hidden md:hidden" onclick="window.__closeSidebar()"></div>
        <div class="w-full">
            <div class="grid grid-cols-1 md:grid-cols-[260px_1fr] gap-4 h-full">
            <!-- Sidebar (dark) -->
            <aside id="admin-sidebar" class="sidebar-panel hidden md:flex flex-col bg-white border-r border-gray-200 text-gray-700 {{ $fixed ? 'fixed top-14 left-0 w-[260px] h-[calc(100vh-56px)] z-30' : 'sticky top-0' }}">
                <div class="h-12 flex items-center px-4 text-gray-500 text-xs uppercase tracking-wide">Main Menu</div>
                <nav class="px-2 pb-6 space-y-4 flex-1 overflow-y-auto">
                    <!-- Dashboard -->
                    <div class="space-y-1">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                            <span class="material-symbols-outlined text-green-600 text-[20px]">dashboard</span>
                            <span>Dashboard</span>
                        </a>
                        </div>

                        <!-- User Management -->
                        <div>
                            <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-gray-50" onclick="window.__toggleSection('um')">
                                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">User Management</span>
                                <svg id="caret-um" class="h-4 w-4 text-gray-400 transition-transform" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                            </button>
                            <div id="section-um" class="mt-1 space-y-1">
                                <a href="{{ route('users.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm {{ request()->routeIs('users.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span class="material-symbols-outlined text-[20px]">group</span>
                                    <span>Users</span>
                                </a>
                            </div>
                        </div>

                        <!-- Learning -->
                        <div>
                            <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-gray-50" onclick="window.__toggleSection('learn')">
                                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Learning</span>
                                <svg id="caret-learn" class="h-4 w-4 text-gray-400 transition-transform" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                            </button>
                            <div id="section-learn" class="mt-1 space-y-1">
                                <a href="{{ route('learning.notes.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm {{ request()->routeIs('learning.notes.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span class="material-symbols-outlined text-[20px]">notes</span>
                                    <span>All Notes</span>
                                </a>
                                <a href="{{ route('learning.levels.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm {{ request()->routeIs('learning.levels.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span class="material-symbols-outlined text-[20px]">school</span>
                                    <span>Education Levels</span>
                                </a>
                                <a href="{{ route('learning.subjects.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm {{ request()->routeIs('learning.subjects.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span class="material-symbols-outlined text-[20px]">library_books</span>
                                    <span>Subjects</span>
                                </a>
                                <a href="{{ route('learning.classes.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm {{ request()->routeIs('learning.classes.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span class="material-symbols-outlined text-[20px]">view_list</span>
                                    <span>Classes</span>
                                </a>
                            </div>
                        </div>

                        <!-- Learning Materials -->
                        <div>
                            <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-gray-50" onclick="window.__toggleSection('materials')">
                                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Learning Materials</span>
                                <svg id="caret-materials" class="h-4 w-4 text-gray-400 transition-transform" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                            </button>
                            <div id="section-materials" class="mt-1 space-y-1">
                                <a href="{{ route('materials.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm {{ request()->routeIs('materials.index') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span class="material-symbols-outlined text-[20px]">inventory_2</span>
                                    <span>All Materials</span>
                                </a>
                                <a href="{{ route('materials.subcategories.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm {{ request()->routeIs('materials.subcategories.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span class="material-symbols-outlined text-[20px]">subdirectory_arrow_right</span>
                                    <span>Material Type</span>
                                </a>
                                <a href="{{ route('materials.subsubcategories.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm {{ request()->routeIs('materials.subsubcategories.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span class="material-symbols-outlined text-[20px]">subdirectory_arrow_right</span>
                                    <span>Material Sub Type</span>
                                </a>
                            </div>
                        </div>

                        <!-- Mobile App -->
                        <div>
                            <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-gray-50" onclick="window.__toggleSection('mobile')">
                                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Mobile App</span>
                                <svg id="caret-mobile" class="h-4 w-4 text-gray-400 transition-transform" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                            </button>
                            <div id="section-mobile" class="mt-1 space-y-1">
                                <a href="{{ route('mobile.notifications.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm {{ request()->routeIs('mobile.notifications.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span class="material-symbols-outlined text-[20px]">notifications</span>
                                    <span>Notifications</span>
                                </a>
                                <a href="{{ route('mobile.maintenance') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm {{ request()->routeIs('mobile.maintenance') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span class="material-symbols-outlined text-[20px]">build</span>
                                    <span>Maintenance Mode</span>
                                </a>
                                <a href="{{ route('mobile.settings') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm {{ request()->routeIs('mobile.settings') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span class="material-symbols-outlined text-[20px]">settings_applications</span>
                                    <span>General Settings</span>
                                </a>
                                <a href="{{ route('mobile.api') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm {{ request()->routeIs('mobile.api') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span class="material-symbols-outlined text-[20px]">api</span>
                                    <span>API Documents</span>
                                </a>
                            </div>
                        </div>

                        

                        <!-- Settings -->
                        <div>
                            <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-gray-50" onclick="window.__toggleSection('settings')">
                                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Settings</span>
                                <svg id="caret-settings" class="h-4 w-4 text-gray-400 transition-transform" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                            </button>
                            <div id="section-settings" class="mt-1 space-y-1">
                                <a href="{{ route('settings.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm {{ request()->routeIs('settings.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span class="material-symbols-outlined text-[20px]">settings</span>
                                    <span>General</span>
                                </a>
                                <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm {{ request()->routeIs('admin.profile.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span class="material-symbols-outlined text-[20px]">account_circle</span>
                                    <span>My Profile</span>
                                </a>
                            </div>
                        </div>
                    </nav>
                    <div class="border-t border-gray-200 px-4 py-3 text-xs text-gray-400 flex items-center justify-between">
                        <span>&copy; {{ date('Y') }} {{ $__admin->site_name ?? 'wazaelimu' }}</span>
                        <span>v1.0</span>
                    </div>
                </aside>

                <!-- Main content -->
                <main class="px-4 {{ $fixed ? 'md:ml-[260px] h-full overflow-y-auto' : '' }} py-4">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>
    </div>
</div>

<script>
// Global loader controller
(function(){
    const loader = document.getElementById('globalPageLoader');
    function show(){ loader?.classList.remove('hidden'); loader?.classList.add('flex'); }
    function hide(){ loader?.classList.add('hidden'); loader?.classList.remove('flex'); }

    // Hide once the page fully loads
    window.addEventListener('load', hide);

    // Intercept same-origin link clicks to show loader
    document.addEventListener('click', function(e){
        const a = e.target.closest('a');
        if (!a) return;
        const href = a.getAttribute('href');
        if (!href || href.startsWith('#') || a.target === '_blank') return;
        // Only same-origin navigations
        const url = new URL(href, window.location.origin);
        if (url.origin !== window.location.origin) return;
        // Ignore modifier/middle clicks
        if (e.defaultPrevented || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0) return;
        show();
    }, true);

    // Show on form submissions
    document.addEventListener('submit', function(e){
        show();
    }, true);
})();

// User menu toggle
window.__toggleUserMenu = function(e){
    e?.preventDefault?.();
    const root = document.getElementById('userMenuRoot');
    const menu = document.getElementById('userMenu');
    if (!menu) return;
    menu.classList.toggle('hidden');
}
document.addEventListener('click', function(e){
    const root = document.getElementById('userMenuRoot');
    const menu = document.getElementById('userMenu');
    if (!root || !menu) return;
    if (!root.contains(e.target)) {
        menu.classList.add('hidden');
    }
}, true);

// Password modal helpers
window.__openPwdModal = function(){
    const m = document.getElementById('pwdModal');
    if (m) m.classList.remove('hidden');
    const um = document.getElementById('userMenu');
    if (um) um.classList.add('hidden');
};
window.__closePwdModal = function(){
    const m = document.getElementById('pwdModal');
    if (m) m.classList.add('hidden');
};
document.addEventListener('keydown', function(e){
    if (e.key === 'Escape') window.__closePwdModal();
});
@if($errors->has('current_password') || $errors->has('password'))
document.addEventListener('DOMContentLoaded', function(){ window.__openPwdModal(); });
@endif

// Mobile sidebar helpers
window.__openSidebar = function(){
    const sb = document.getElementById('admin-sidebar');
    const ov = document.getElementById('admin-sidebar-overlay');
    if (!sb || !ov) return;
    // Prepare sidebar for mobile viewport
    sb.classList.remove('hidden');
    sb.classList.add('fixed','top-14','left-0','w-[260px]','h-[calc(100vh-56px)]','z-40','shadow-lg');
    ov.classList.remove('hidden');
    // Lock body scroll (mobile only)
    try { document.body.style.overflow = 'hidden'; } catch {}
};
window.__closeSidebar = function(){
    const sb = document.getElementById('admin-sidebar');
    const ov = document.getElementById('admin-sidebar-overlay');
    if (!sb || !ov) return;
    // Hide mobile sidebar; keep md+ behavior intact
    sb.classList.add('hidden');
    sb.classList.remove('fixed','top-14','left-0','w-[260px]','h-[calc(100vh-56px)]','z-40','shadow-lg');
    ov.classList.add('hidden');
    try { document.body.style.overflow = ''; } catch {}
};
window.__toggleSidebar = function(){
    const sb = document.getElementById('admin-sidebar');
    if (!sb) return;
    if (sb.classList.contains('hidden')) return window.__openSidebar();
    return window.__closeSidebar();
};
document.addEventListener('keydown', function(e){
    if (e.key === 'Escape') window.__closeSidebar();
});

// Sidebar section toggles with persistence
;(function(){
  const key = 'waza_sidebar_sections_v1';
  const defaults = { um: true, learn: true, materials: true, mobile: true, cms: true, settings: true };
  function load(){
    try { return { ...defaults, ...(JSON.parse(localStorage.getItem(key) || '{}')||{}) }; } catch { return { ...defaults }; }
  }
  function save(state){
    try { localStorage.setItem(key, JSON.stringify(state)); } catch {}
  }
  function apply(id, open){
    const sec = document.getElementById('section-'+id);
    const caret = document.getElementById('caret-'+id);
    if (!sec) return;
    if (open){
      sec.classList.remove('hidden');
      caret && caret.classList.remove('rotate-180');
    } else {
      sec.classList.add('hidden');
      caret && caret.classList.add('rotate-180');
    }
  }
  const state = load();
  window.__toggleSection = function(id){
    const next = !state[id];
    state[id] = next;
    apply(id, next);
    save(state);
  };
  document.addEventListener('DOMContentLoaded', function(){
    Object.keys(defaults).forEach(id => apply(id, !!state[id]));
  });
})();

// Header menu search (AJAX)
;(function(){
  const input = document.getElementById('menuSearchInput');
  const box = document.getElementById('menuSearchResults');
  const root = document.getElementById('menuSearchRoot');
  if (!input || !box || !root) return;
  let t = null; let sel = -1; let data = [];
  const render = () => {
    box.innerHTML = '';
    if (!data.length){ box.classList.add('hidden'); return; }
    const frag = document.createDocumentFragment();
    data.forEach((it, i) => {
      const a = document.createElement('a');
      a.href = it.url;
      a.className = 'block px-3 py-2 text-sm hover:bg-gray-50 ' + (i===sel ? 'bg-gray-50' : '');
      a.innerHTML = `<div class="font-medium text-gray-800">${it.title}</div><div class="text-xs text-gray-500">${it.section}</div>`;
      a.addEventListener('mousedown', e => { /* navigate without blur hiding */ });
      frag.appendChild(a);
    });
    box.appendChild(frag);
    box.classList.remove('hidden');
  };
  const fetchData = async (q) => {
    try{
      const res = await fetch(`{{ route('menu.search') }}?q=`+encodeURIComponent(q), { headers: { 'X-Requested-With':'XMLHttpRequest' }});
      if (!res.ok) return (data=[], render());
      data = await res.json(); sel = data.length ? 0 : -1; render();
    }catch{ data=[]; render(); }
  };
  const deb = (fn, d=200) => (v) => { clearTimeout(t); t = setTimeout(() => fn(v), d); };
  const onType = deb((e)=>{
    const v = input.value.trim();
    if (v.length < 1) { data=[]; render(); return; }
    fetchData(v);
  }, 250);
  input.addEventListener('input', onType);
  input.addEventListener('keydown', (e)=>{
    if (box.classList.contains('hidden')) return;
    if (e.key === 'ArrowDown'){ e.preventDefault(); sel = Math.min((sel<0?0:sel)+1, data.length-1); render(); }
    else if (e.key === 'ArrowUp'){ e.preventDefault(); sel = Math.max((sel<0?0:sel)-1, 0); render(); }
    else if (e.key === 'Enter'){ if (sel>=0 && data[sel]) { window.location.href = data[sel].url; } }
    else if (e.key === 'Escape'){ box.classList.add('hidden'); }
  });
  document.addEventListener('click', (e)=>{
    if (!root.contains(e.target)) box.classList.add('hidden');
  });
})();

</script>

</body>
</html>
