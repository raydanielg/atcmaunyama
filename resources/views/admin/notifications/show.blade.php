<x-admin-layout>
    <div class="h-1 bg-gradient-to-r from-indigo-500 via-sky-500 to-emerald-500 rounded-t-xl"></div>

    <div class="py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Notification</h1>
                <p class="text-sm text-gray-500 mt-1">Details and performance stats.</p>
            </div>
            <a href="{{ route('admin.notifications.index') }}" class="inline-flex items-center px-3 py-1.5 rounded border text-gray-700 border-gray-300 hover:bg-gray-100 text-xs">Back</a>
        </div>

        <div class="mt-4 grid md:grid-cols-3 gap-4">
            <div class="md:col-span-2 p-4 border rounded-xl bg-white">
                <div class="text-sm text-gray-500">Title</div>
                <div class="text-gray-900 font-medium">{{ $n->title }}</div>
                <div class="mt-3">
                    <div class="text-sm text-gray-500">Message</div>
                    <div class="mt-1 text-gray-900 whitespace-pre-line">{{ $n->message ?: '—' }}</div>
                </div>
                <div class="mt-3 grid md:grid-cols-2 gap-3 text-sm">
                    <div>
                        <div class="text-gray-500">Action Label</div>
                        <div class="text-gray-900">{{ $n->action_label ?: '—' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Action URL</div>
                        <div class="text-gray-900">
                            @if($n->action_url)
                                <a href="{{ $n->action_url }}" target="_blank" class="text-indigo-600 hover:underline">{{ $n->action_url }}</a>
                            @else
                                —
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-500">Starts At</div>
                        <div class="text-gray-900">{{ $n->starts_at?->format('d M Y, H:i') ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Ends At</div>
                        <div class="text-gray-900">{{ $n->ends_at?->format('d M Y, H:i') ?? '—' }}</div>
                    </div>
                </div>
                <div class="mt-3">
                    @php
                        $now = now();
                        $statusBadge = 'Inactive'; $badgeClass='bg-gray-100 text-gray-700 border-gray-200';
                        if ($n->is_active) {
                            if ($n->starts_at && $n->starts_at->isFuture()) { $statusBadge='Scheduled'; $badgeClass='bg-amber-100 text-amber-700 border-amber-200'; }
                            elseif ($n->ends_at && $n->ends_at->isPast()) { $statusBadge='Expired'; $badgeClass='bg-gray-100 text-gray-500 border-gray-200'; }
                            else { $statusBadge='Active'; $badgeClass='bg-emerald-100 text-emerald-700 border-emerald-200'; }
                        }
                    @endphp
                    <span class="text-[10px] px-2 py-0.5 rounded-full border {{ $badgeClass }}">{{ $statusBadge }}</span>
                </div>

                <div class="mt-4 flex items-center gap-2">
                    <a href="{{ route('admin.notifications.edit', $n) }}" class="px-3 py-1.5 rounded border text-sm">Edit</a>
                    @if(!$n->is_active)
                        <form method="POST" action="{{ route('admin.notifications.publish', $n) }}">@csrf
                            <button class="px-3 py-1.5 rounded bg-emerald-600 hover:bg-emerald-700 text-white text-sm">Publish</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.notifications.unpublish', $n) }}">@csrf
                            <button class="px-3 py-1.5 rounded bg-amber-600 hover:bg-amber-700 text-white text-sm">Unpublish</button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('admin.notifications.resend', $n) }}" onsubmit="return confirm('Create and publish a fresh copy now?');">@csrf
                        <button class="px-3 py-1.5 rounded border text-sm">Resend</button>
                    </form>
                    <form method="POST" action="{{ route('admin.notifications.destroy', $n) }}" onsubmit="return confirm('Delete this notification?');">@csrf @method('DELETE')
                        <button class="px-3 py-1.5 rounded border text-sm text-red-600">Delete</button>
                    </form>
                </div>
            </div>

            <div class="p-4 border rounded-xl bg-white">
                <div class="text-sm font-medium text-gray-800">Performance</div>
                <div class="mt-3 grid grid-cols-2 gap-3 text-center">
                    <div class="p-3 rounded-lg border bg-gray-50">
                        <div class="text-2xl font-semibold text-gray-900">{{ number_format($n->views) }}</div>
                        <div class="text-xs text-gray-500">Views</div>
                    </div>
                    <div class="p-3 rounded-lg border bg-gray-50">
                        <div class="text-2xl font-semibold text-gray-900">{{ number_format($n->clicks) }}</div>
                        <div class="text-xs text-gray-500">Clicks</div>
                    </div>
                </div>
                @php $ctr = $n->views > 0 ? round(($n->clicks / max(1,$n->views)) * 100, 2) : 0; @endphp
                <div class="mt-3 p-3 rounded-lg border bg-gray-50 text-center">
                    <div class="text-lg font-semibold text-gray-900">{{ $ctr }}%</div>
                    <div class="text-xs text-gray-500">CTR</div>
                </div>
                <div class="mt-4">
                    <div class="text-xs text-gray-500">Public API preview</div>
                    <div class="mt-1 text-xs">
                        <code class="block p-2 bg-gray-100 rounded">GET /api/mobile/notifications</code>
                        <code class="block p-2 bg-gray-100 rounded">GET /api/mobile/notifications/{{ $n->id }}</code>
                        <code class="block p-2 bg-gray-100 rounded">POST /api/mobile/notifications/{{ $n->id }}/view</code>
                        <code class="block p-2 bg-gray-100 rounded">POST /api/mobile/notifications/{{ $n->id }}/click</code>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
