<x-admin-layout>
    <div class="py-4">
        <div class="flex items-center justify-between">
            <div class="w-full">
                <h1 class="text-xl font-semibold text-gray-900">Maintenance Mode</h1>
                <p class="text-sm text-gray-500">Enable maintenance and write a message that mobile users will see as an in-app banner.</p>
                <div class="mt-2 font-mono text-[11px] tracking-widest text-gray-400 select-none whitespace-nowrap overflow-hidden" aria-hidden="true">- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</div>
            </div>
        </div>
        <div class="border-t border-dashed mt-2 mb-4"></div>

        @if (session('status'))
            <div class="mb-4"><x-alert type="success">{{ session('status') }}</x-alert></div>
        @endif
        @if ($errors->any())
            <div class="mb-4">
                <x-alert type="error">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Toggle Card -->
            <div class="bg-white border rounded-lg p-4 lg:col-span-1">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-900">Enable Maintenance</h3>
                        <p class="text-sm text-gray-500">When enabled, the app will show a maintenance banner.</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('mobile.maintenance.toggle') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="enabled" value="0" />
                    <label class="toggle-red">
                        <input type="checkbox" name="enabled" value="1" {{ ($enabled ?? false) ? 'checked' : '' }} />
                        <svg aria-label="enabled" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g stroke-linejoin="round" stroke-linecap="round" stroke-width="4" fill="none" stroke="currentColor">
                              <path d="M20 6 9 17l-5-5"></path>
                            </g>
                        </svg>
                        <svg aria-label="disabled" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </label>
                    <button class="mt-3 inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white">
                        <span class="material-symbols-outlined text-[18px]">power_settings_new</span>
                        Save Toggle
                    </button>
                </form>
                <div class="mt-3 text-xs text-gray-500">Current time: {{ ($now ?? now())->format('Y-m-d H:i') }}</div>
            </div>

            <!-- Message Card -->
            <div class="bg-white border rounded-lg p-4 lg:col-span-2">
                <h3 class="font-semibold text-gray-900">Maintenance Message</h3>
                <p class="text-sm text-gray-500">Write a short message that will appear at the top of the mobile app during maintenance.</p>
                <form method="POST" action="{{ route('mobile.maintenance.message') }}" class="mt-3 space-y-3">
                    @csrf
                    <textarea name="message" rows="4" maxlength="500" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-rose-500 focus:border-rose-500" placeholder="e.g. We are performing maintenance from 02:00 - 03:00. Some features may be unavailable.">{{ old('message', $message ?? '') }}</textarea>
                    <div class="text-right">
                        <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            Save Message
                        </button>
                    </div>
                </form>
                <div class="mt-2 text-xs text-gray-500">Tip: Keep it brief. This shows as a banner inside the mobile app.</div>
            </div>
        </div>

        <!-- History -->
        <div class="mt-6 bg-white border rounded-lg">
            <div class="px-4 py-3 border-b flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Maintenance History</h3>
                <div class="text-sm text-gray-500">Most recent 20 entries</div>
            </div>
            @if(($history ?? collect())->isEmpty())
                <div class="p-8 text-center">
                    <div class="flex flex-col items-center gap-4">
                        <svg width="120" height="120" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <circle cx="100" cy="100" r="90" stroke="#e5e7eb" stroke-width="4"/>
                            <path d="M60 110c15 10 35 10 50 0" stroke="#9ca3af" stroke-width="6" stroke-linecap="round"/>
                            <circle cx="75" cy="80" r="6" fill="#9ca3af"/>
                            <circle cx="125" cy="80" r="6" fill="#9ca3af"/>
                        </svg>
                        <div class="text-gray-500 text-sm">No maintenance records yet.</div>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider">When</th>
                                <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                                <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider">By</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($history as $row)
                                <tr>
                                    <td class="px-4 py-2 text-gray-700">{{ $row->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-2">
                                        @if($row->is_enabled)
                                            <span class="inline-flex items-center gap-1.5 text-rose-700 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-full text-xs">
                                                <span class="material-symbols-outlined text-[16px]">warning</span> Enabled
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full text-xs">
                                                <span class="material-symbols-outlined text-[16px]">check_circle</span> Disabled
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-gray-700">{{ \Illuminate\Support\Str::limit($row->message ?? '—', 100) }}</td>
                                    <td class="px-4 py-2 text-gray-600">{{ optional($row->user)->name ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Minimal toggle styles (red, DaisyUI-like) -->
    <style>
        .toggle-red{
            --on: #dc2626; /* rose-600-ish */
            --off: #e5e7eb; /* gray-200 */
            position: relative; display: inline-flex; align-items: center; gap:.5rem;
            padding: .25rem .5rem; border-radius:.75rem; border:1px solid #fecaca; background:#fef2f2; color:#991b1b;
        }
        .toggle-red input{ position: absolute; inset:0; opacity:0; cursor:pointer; }
        .toggle-red svg{ width:24px; height:24px; display:none; }
        .toggle-red input:checked ~ svg[aria-label="enabled"]{ display:block; color:var(--on); }
        .toggle-red input:not(:checked) ~ svg[aria-label="disabled"]{ display:block; color:#9ca3af; }
    </style>
</x-admin-layout>
