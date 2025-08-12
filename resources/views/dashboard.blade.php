<x-admin-layout>
    @php
        // Counts from DB with safe guards
        $usersCount = \App\Models\User::count();
        $notesCount = \Illuminate\Support\Facades\Schema::hasTable('notes') ? (int) \Illuminate\Support\Facades\DB::table('notes')->count() : 0;
        // Use correct table name for classes
        $classesCount = \Illuminate\Support\Facades\Schema::hasTable('school_classes') ? (int) \Illuminate\Support\Facades\DB::table('school_classes')->count() : 0;
        $materialsCount = \Illuminate\Support\Facades\Schema::hasTable('materials') ? (int) \Illuminate\Support\Facades\DB::table('materials')->count() : 0;

        // Recent activity (generic activity_logs: action, description, created_at)
        $recentActivity = [];
        if (\Illuminate\Support\Facades\Schema::hasTable('activity_logs')) {
            $recentActivity = \Illuminate\Support\Facades\DB::table('activity_logs')
                ->select(['action','description','created_at'])
                ->latest('created_at')
                ->limit(6)
                ->get();
        }

        // Login logs (email, created_at, ip optional)
        $loginLogs = [];
        if (\Illuminate\Support\Facades\Schema::hasTable('login_logs')) {
            $loginLogs = \Illuminate\Support\Facades\DB::table('login_logs')
                ->select(['email','created_at','ip_address'])
                ->latest('created_at')
                ->limit(5)
                ->get();
        }

        // Storage/health metrics
        $diskPath = base_path();
        $total = @disk_total_space($diskPath);
        $free = @disk_free_space($diskPath);
        $used = ($total !== false && $free !== false) ? ($total - $free) : 0;
        $usedPercent = ($total && $total > 0) ? round(($used / $total) * 100) : 0;
        $healthPercent = max(0, 100 - $usedPercent); // simple heuristic

        function humanBytes($bytes) {
            if ($bytes <= 0) return '0 B';
            $units = ['B','KB','MB','GB','TB'];
            $i = floor(log($bytes, 1024));
            return round($bytes / pow(1024, $i), 2).' '.$units[$i];
        }

        // Mobile performance status (placeholder logic)
        $mobilePerf = $healthPercent >= 70 ? 'Fast' : ($healthPercent >= 40 ? 'Medium' : 'Slow');
        $mobilePerfColor = $mobilePerf === 'Fast' ? 'text-green-600' : ($mobilePerf === 'Medium' ? 'text-yellow-600' : 'text-red-600');
    @endphp

    <div class="space-y-5">
        <!-- Masthead strip -->
        <div class="h-1 bg-gray-100 rounded"></div>

        <!-- Heading -->
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-900">Dashboard</h1>
            <span class="text-xs text-gray-500">Last update: {{ now()->format('d-m-Y H:i:s') }}</span>
        </div>

        <!-- Dashed divider under heading -->
        <div class="border-t border-dashed border-gray-300"></div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div class="bg-white border border-gray-200 rounded-md p-4 reveal">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold counter" data-count="{{ $usersCount }}">0</div>
                        <div class="text-xs text-gray-500">Users</div>
                    </div>
                    <span class="material-symbols-outlined text-[28px] text-green-600">group</span>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-md p-4 reveal" style="transition-delay:60ms">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold counter" data-count="{{ $notesCount }}">0</div>
                        <div class="text-xs text-gray-500">Notes</div>
                    </div>
                    <span class="material-symbols-outlined text-[28px] text-indigo-600">notes</span>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-md p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold counter" data-count="{{ $classesCount }}">0</div>
                        <div class="text-xs text-gray-500">Classes</div>
                    </div>
                    <span class="material-symbols-outlined text-[28px] text-amber-600">view_list</span>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-md p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold counter" data-count="{{ $materialsCount }}">0</div>
                        <div class="text-xs text-gray-500">Materials</div>
                    </div>
                    <span class="material-symbols-outlined text-[28px] text-pink-600">inventory_2</span>
                </div>
            </div>
            <!-- System Health card -->
            <div class="bg-white border border-gray-200 rounded-md p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs text-gray-500">System Health</div>
                        <div class="text-2xl font-bold">{{ $healthPercent }}%</div>
                    </div>
                    <span class="material-symbols-outlined text-[28px] text-emerald-600">health_and_safety</span>
                </div>
                <div class="mt-2">
                    <div class="h-2 w-full bg-gray-200 rounded">
                        <div class="h-2 bg-green-600 rounded" style="width: {{ $healthPercent }}%"></div>
                    </div>
                    <div class="mt-1 text-[11px] text-gray-500">Storage used: {{ humanBytes($used) }} / {{ humanBytes($total) }} ({{ $usedPercent }}%)</div>
                </div>
            </div>
        </div>

        <!-- Main content grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Left column -->
            <div class="space-y-4 lg:col-span-2">
                <!-- Recent Activity -->
                <div class="bg-white rounded-md border border-gray-200 reveal">
                    <div class="px-4 py-2 border-b border-gray-200 font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-gray-500">history</span>
                        Recent Activity
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

                <!-- Pie Chart -->
                <div class="bg-white rounded-md border border-gray-200">
                    <div class="px-4 py-2 border-b border-gray-200 font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-gray-500">pie_chart</span>
                        Platform Stats
                    </div>
                    <div class="p-4">
                        <div class="chart-resize mx-auto">
                            <canvas id="statsPie"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right column -->
            <div class="space-y-4">
                <!-- CA card: storage + mobile perf -->
                <div class="bg-white rounded-md border border-gray-200">
                    <div class="px-4 py-2 border-b border-gray-200 font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-gray-500">important_devices</span>
                        System Snapshot
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Storage Used</span>
                            <span class="font-medium">{{ humanBytes($used) }} / {{ humanBytes($total) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded h-2">
                            <div class="bg-blue-600 h-2 rounded" style="width: {{ $usedPercent }}%"></div>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Mobile Performance</span>
                            <span class="font-medium {{ $mobilePerfColor }}">{{ $mobilePerf }}</span>
                        </div>
                    </div>
                </div>

                <!-- Login activity logs -->
                <div class="bg-white rounded-md border border-gray-200">
                    <div class="px-4 py-2 border-b border-gray-200 font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-gray-500">login</span>
                        Login Activity
                    </div>
                    <div class="p-4">
                        <div class="text-xs text-gray-500 mb-2">Recent logins</div>
                        @if (!empty($loginLogs) && count($loginLogs))
                            <div class="space-y-2 text-sm">
                                @foreach ($loginLogs as $log)
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-700">{{ $log->email }}</span>
                                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-sm text-gray-500">No login entries.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        (function(){
            // Show loader placeholders (optional)
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

            // Chart
            const ctx = document.getElementById('statsPie');
            if (ctx) {
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Users', 'Materials', 'Categories'],
                        datasets: [{
                            data: [{{ $usersCount }}, {{ $materialsCount }}, {{ \Illuminate\Support\Facades\Schema::hasTable('categories') ? (int) \Illuminate\Support\Facades\DB::table('categories')->count() : 0 }}],
                            backgroundColor: ['#16a34a', '#f43f5e', '#6366f1'],
                            borderWidth: 0
                        }]
                    },
                    options: { plugins: { legend: { position: 'bottom' } } }
                });
            }
        })();
    </script>
</x-admin-layout>
