@php
    $user = auth()->user();
    // Safe counts
    $levelsCount = \Illuminate\Support\Facades\Schema::hasTable('levels') ? (int) \Illuminate\Support\Facades\DB::table('levels')->count() : 0;
    $classesCount = \Illuminate\Support\Facades\Schema::hasTable('school_classes') ? (int) \Illuminate\Support\Facades\DB::table('school_classes')->count() : 0;
    $materialsCount = \Illuminate\Support\Facades\Schema::hasTable('materials') ? (int) \Illuminate\Support\Facades\DB::table('materials')->count() : 0;

    // Recent activity: strictly current user only (requires activity_logs.user_id)
    $recentActivity = [];
    if (\Illuminate\Support\Facades\Schema::hasTable('activity_logs')
        && \Illuminate\Support\Facades\Schema::hasColumn('activity_logs','user_id')
        && $user?->id) {
        $recentActivity = \Illuminate\Support\Facades\DB::table('activity_logs')
            ->select(['action','description','created_at'])
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->limit(6)
            ->get();
    }
    $activityDays = collect($recentActivity)->map(fn($a) => \Carbon\Carbon::parse($a->created_at)->format('Y-m-d'))->unique()->values()->all();

    // My login attempts: prefer user_id, fallback to email if column exists
    $loginAttempts = [];
    if (\Illuminate\Support\Facades\Schema::hasTable('login_logs')) {
        $q2 = \Illuminate\Support\Facades\DB::table('login_logs')->select(['email','created_at','ip_address']);
        if (\Illuminate\Support\Facades\Schema::hasColumn('login_logs','user_id') && $user?->id) {
            $q2->where('user_id', $user->id);
        } elseif ($user?->email && \Illuminate\Support\Facades\Schema::hasColumn('login_logs','email')) {
            $q2->where('email', $user->email);
        } else {
            $q2 = null; // no supported scoping
        }
        if ($q2) {
            $loginAttempts = $q2->latest('created_at')->limit(6)->get();
        }
    }

    // Notifications count: show only active/unread if schema supports it
    $notifCount = 0;
    if (\Illuminate\Support\Facades\Schema::hasTable('mobile_notifications')) {
        $nTable = 'mobile_notifications';
        $q = \Illuminate\Support\Facades\DB::table($nTable.' as n');
        // Active filter if present
        if (\Illuminate\Support\Facades\Schema::hasColumn($nTable, 'is_active')) {
            $q->where('n.is_active', 1);
        } elseif (\Illuminate\Support\Facades\Schema::hasColumn($nTable, 'status')) {
            $q->where('n.status', 'active');
        }
        // Unread per-user if read tracking table exists
        $readsTable = null;
        if (\Illuminate\Support\Facades\Schema::hasTable('notification_reads')) {
            $readsTable = 'notification_reads';
        } elseif (\Illuminate\Support\Facades\Schema::hasTable('mobile_notification_reads')) {
            $readsTable = 'mobile_notification_reads';
        }
        if ($readsTable && $user?->id
            && \Illuminate\Support\Facades\Schema::hasColumn($readsTable,'notification_id')
            && \Illuminate\Support\Facades\Schema::hasColumn($readsTable,'user_id')) {
            $q->leftJoin($readsTable.' as r', function($join){
                $join->on('r.notification_id', '=', 'n.id')
                     ->where('r.user_id', '=', auth()->id());
            })->whereNull('r.id');
        }
        $notifCount = (int) $q->count();
    }
    
    // Profile completion gate: require a name
    $needsProfile = !($user && is_string($user->name) && trim($user->name) !== '');
@endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Dashboard') }}</h2>
            <a href="{{ route('mobile.notifications.index', [], false) ?? url('/notifications') }}" class="relative inline-flex items-center gap-2 px-3 py-1.5 rounded-md border bg-indigo-50/60 hover:bg-indigo-100">
                <span class="material-symbols-outlined text-indigo-700">notifications_active</span>
                <span class="text-sm text-indigo-800">Notifications</span>
                @if(($notifCount ?? 0) > 0)
                    <span class="ml-1 inline-flex items-center justify-center text-[11px] font-semibold text-white bg-violet-600 rounded-full min-w-[1.25rem] h-5 px-1">{{ $notifCount }}</span>
                @endif
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <!-- Masthead strip and dashed divider -->
            <div class="h-1 bg-gray-100 rounded"></div>
            <div class="border-t border-dashed border-gray-300"></div>

            @if($needsProfile)
                <!-- Complete your profile banner -->
                <div class="p-4 rounded-xl border border-amber-200 bg-amber-50/80 flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-amber-600 mt-0.5">account_circle</span>
                        <div>
                            <div class="text-sm font-semibold text-amber-800">Complete your profile</div>
                            <div class="text-xs text-amber-700">Add your name to finish setting up your account. Some features remain disabled until completed.</div>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-amber-600 text-white text-sm hover:bg-amber-700">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                        Update profile
                    </a>
                </div>
            @endif

            <!-- Welcome banner -->
            <div class="p-5 rounded-xl bg-gradient-to-r from-indigo-50 via-white to-emerald-50 border border-indigo-100 shadow-sm flex items-center justify-between">
                <div class="flex items-center">
                    <div class="mr-4 hidden sm:flex items-center justify-center w-12 h-12 rounded-full bg-indigo-100 text-indigo-600">
                        <span class="material-symbols-outlined">waving_hand</span>
                    </div>
                    <div>
                        <div class="text-xs uppercase tracking-wide text-indigo-600">Welcome back</div>
                        <div class="text-xl font-semibold text-gray-800">{{ $needsProfile ? ($user?->email ?? 'Your account') : ($user?->name) }}</div>
                        @if($needsProfile)
                            <div class="mt-1 inline-flex items-center gap-1 text-[11px] font-medium text-amber-800 bg-amber-100 rounded-full px-2 py-0.5">
                                <span class="material-symbols-outlined text-[14px]">warning</span>
                                Profile incomplete
                            </div>
                        @endif
                        <div class="text-xs text-gray-500 mt-1">Email: {{ $user?->email }}</div>
                    </div>
                </div>
                <div class="hidden sm:flex items-center gap-2 text-sm text-gray-600">
                    <span class="material-symbols-outlined text-emerald-600">calendar_today</span>
                    <span>{{ now()->format('D, d M Y') }}</span>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 {{ $needsProfile ? 'opacity-50 pointer-events-none select-none' : '' }}">
                <div class="bg-white border border-gray-200 rounded-md p-4 reveal">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold counter" data-count="{{ $levelsCount }}">0</div>
                            <div class="text-xs text-gray-500">Levels</div>
                        </div>
                        <span class="material-symbols-outlined text-[28px] text-violet-600">stacked_bar_chart</span>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-md p-4 reveal" style="transition-delay:60ms">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold counter" data-count="{{ $classesCount }}">0</div>
                            <div class="text-xs text-gray-500">Classes</div>
                        </div>
                        <span class="material-symbols-outlined text-[28px] text-amber-600">view_list</span>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-md p-4 reveal" style="transition-delay:120ms">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold counter" data-count="{{ $materialsCount }}">0</div>
                            <div class="text-xs text-gray-500">Materials</div>
                        </div>
                        <span class="material-symbols-outlined text-[28px] text-pink-600">inventory_2</span>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-md p-4">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="material-symbols-outlined text-[20px] text-emerald-600">tips_and_updates</span>
                        <span>Explore classes and materials to start learning.</span>
                    </div>
                </div>
            </div>

            <!-- Main content grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 {{ $needsProfile ? 'opacity-50 pointer-events-none select-none' : '' }}">
                <!-- Left column -->
                <div class="space-y-4 lg:col-span-2">
                    <!-- My Recent Activity -->
                    <div class="bg-white rounded-md border border-gray-200 reveal">
                        <div class="px-4 py-2 border-b border-gray-200 font-medium flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-gray-500">history</span>
                            My Recent Activity
                        </div>
                        <div class="p-4">
                            @if (!empty($recentActivity) && count($recentActivity))
                                <ul class="divide-y text-sm">
                                    @foreach ($recentActivity as $act)
                                        <li class="py-2 flex items-center justify-between">
                                            <div class="flex items-center gap-2 text-gray-700">
                                                <span class="material-symbols-outlined text-[18px] text-blue-600">activity_zone</span>
                                                <span class="font-medium">{{ $act->action }}</span>
                                                <span class="text-gray-500">— {{ $act->description }}</span>
                                            </div>
                                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($act->created_at)->diffForHumans() }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-sm text-gray-500">No recent activity.</div>
                            @endif
                        </div>
                    </div>

                    <!-- Calendar -->
                    <div class="bg-white rounded-md border border-gray-200 reveal">
                        <div class="px-4 py-2 border-b border-gray-200 font-medium flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-gray-500">event</span>
                            Calendar
                        </div>
                        <div class="p-4">
                            <div id="activityCalendar" class="w-full"></div>
                        </div>
                    </div>
                </div>

                <!-- Right column -->
                <div class="space-y-4">
                    <div class="bg-white rounded-md border border-gray-200">
                        <div class="px-4 py-2 border-b border-gray-200 font-medium flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-gray-500">menu_book</span>
                            Quick Links
                        </div>
                        <div class="p-4 grid grid-cols-2 gap-3 text-sm">
                            <a href="{{ route('user.classes.index') }}" class="flex items-center gap-2 px-3 py-2 border rounded hover:bg-gray-50">
                                <span class="material-symbols-outlined text-amber-600">view_list</span>
                                Classes
                            </a>
                            <a href="{{ route('materials.index', [], false) ?? url('/materials') }}" class="flex items-center gap-2 px-3 py-2 border rounded hover:bg-gray-50">
                                <span class="material-symbols-outlined text-pink-600">inventory_2</span>
                                Materils
                            </a>
                            <a href="{{ route('mobile.notifications.index', [], false) ?? url('/notifications') }}" class="flex items-center gap-2 px-3 py-2 border rounded hover:bg-gray-50">
                                <span class="material-symbols-outlined text-indigo-600">notifications</span>
                                Notifications
                            </a>
                            <a href="{{ route('faq.index') }}" class="flex items-center gap-2 px-3 py-2 border rounded hover:bg-gray-50">
                                <span class="material-symbols-outlined text-teal-600">help</span>
                                FAQ
                            </a>
                        </div>
                    </div>

                    <!-- My Login Attempts -->
                    <div class="bg-white rounded-md border border-gray-200">
                        <div class="px-4 py-2 border-b border-gray-200 font-medium flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-gray-500">login</span>
                            My Login Attempts
                        </div>
                        <div class="p-4">
                            @if (!empty($loginAttempts) && count($loginAttempts))
                                <div class="space-y-2 text-sm">
                                    @foreach ($loginAttempts as $log)
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2 text-gray-700">
                                                <span class="material-symbols-outlined text-[18px] text-emerald-600">verified_user</span>
                                                <span class="text-gray-600">{{ $log->email }}</span>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</div>
                                                @if (!empty($log->ip_address))
                                                    <div class="text-[11px] text-gray-400">IP: {{ $log->ip_address }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-sm text-gray-500">No login attempts found.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .reveal { opacity: 0; transform: translateY(10px); transition: all .35s ease; }
        .reveal.show { opacity: 1; transform: translateY(0); }
        .cal-grid { display: grid; grid-template-columns: repeat(7,minmax(0,1fr)); gap: .25rem; }
        .cal-day { display:flex; align-items:center; justify-content:center; height:2.25rem; border-radius:.5rem; font-size:.8rem; }
        .cal-day.is-today { outline: 2px solid rgb(99 102 241); outline-offset: -2px; }
        .cal-day.has-activity { background: rgba(99,102,241,.08); color:#111827; position: relative; }
        .cal-day.has-activity::after { content:''; position:absolute; bottom:4px; width:6px; height:6px; border-radius:9999px; background:#6366f1; }
    </style>
    <script>
        (function(){
            const counters = document.querySelectorAll('.counter');
            const animateCount = (el, to, duration = 800) => {
                const start = 0; const diff = to - start; const startTime = performance.now();
                const step = (now) => {
                    const p = Math.min((now - startTime) / duration, 1);
                    el.textContent = Math.floor(start + diff * p).toLocaleString();
                    if (p < 1) requestAnimationFrame(step);
                };
                requestAnimationFrame(step);
            };
            const io = new IntersectionObserver((entries)=>{
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        if (e.target.classList.contains('counter')) {
                            const n = parseInt(e.target.dataset.count||'0',10);
                            animateCount(e.target, n);
                        } else if (e.target.classList.contains('reveal')) {
                            e.target.classList.add('show');
                        }
                        io.unobserve(e.target);
                    }
                });
            }, { threshold: 0.35 });
            counters.forEach(c => io.observe(c));
            document.querySelectorAll('.reveal').forEach(el => io.observe(el));

            // Calendar
            const calEl = document.getElementById('activityCalendar');
            if (calEl) {
                const activityDays = @json($activityDays ?? []);
                const today = new Date();
                const year = today.getFullYear();
                const month = today.getMonth();
                const monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];
                const header = document.createElement('div');
                header.className = 'flex items-center justify-between mb-2';
                header.innerHTML = `<div class="text-sm font-medium text-gray-800">${monthNames[month]} ${year}</div>`;
                calEl.appendChild(header);
                const weekdays = ['Su','Mo','Tu','We','Th','Fr','Sa'];
                const wk = document.createElement('div');
                wk.className = 'cal-grid text-[11px] text-gray-500 mb-1';
                wk.innerHTML = weekdays.map(d=>`<div class="text-center">${d}</div>`).join('');
                calEl.appendChild(wk);
                const firstDay = new Date(year, month, 1);
                const startWeekday = firstDay.getDay();
                const daysInMonth = new Date(year, month+1, 0).getDate();
                const grid = document.createElement('div'); grid.className = 'cal-grid';
                for (let i=0;i<startWeekday;i++) grid.appendChild(document.createElement('div'));
                for (let d=1; d<=daysInMonth; d++) {
                    const cell = document.createElement('div'); cell.className = 'cal-day';
                    const isToday = d === today.getDate();
                    const iso = `${year}-${String(month+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
                    const isActive = activityDays.includes(iso);
                    if (isToday) cell.classList.add('is-today');
                    if (isActive) cell.classList.add('has-activity');
                    cell.textContent = d; grid.appendChild(cell);
                }
                calEl.appendChild(grid);
            }
        })();
    </script>
</x-app-layout>
