<x-admin-layout>
    <div class="h-1 bg-gradient-to-r from-indigo-500 via-sky-500 to-emerald-500 rounded-t-xl"></div>
    <div class="py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Notifications</h1>
                <p class="text-sm text-gray-500 mt-1">Manage, filter, and review stats.</p>
            </div>
            <a href="{{ route('admin.notifications.create') }}" class="inline-flex items-center px-3 py-1.5 rounded bg-indigo-600 hover:bg-indigo-700 text-white text-xs">New Notification</a>
        </div>

        <form method="GET" class="mt-4 flex flex-wrap items-center gap-2">
            <input type="text" name="q" value="{{ $s ?? '' }}" placeholder="Search title/message" class="w-full md:w-72 border rounded-lg px-3 py-2 text-sm" />
            <select name="status" class="w-full md:w-48 border rounded-lg px-3 py-2 text-sm">
                <option value="">All statuses</option>
                <option value="active" {{ ($status ?? '')==='active' ? 'selected' : '' }}>Active</option>
                <option value="scheduled" {{ ($status ?? '')==='scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="expired" {{ ($status ?? '')==='expired' ? 'selected' : '' }}>Expired</option>
                <option value="inactive" {{ ($status ?? '')==='inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button class="px-3 py-2 rounded-lg border border-gray-300 text-sm">Filter</button>
        </form>

        <div class="mt-4 bg-white border rounded-xl overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-2">Title</th>
                        <th class="text-left px-4 py-2">Schedule</th>
                        <th class="text-left px-4 py-2">Status</th>
                        <th class="text-right px-4 py-2">Views</th>
                        <th class="text-right px-4 py-2">Clicks</th>
                        <th class="text-right px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                @forelse($items as $n)
                    @php
                        $now = now();
                        $statusBadge = 'Inactive'; $badgeClass='bg-gray-100 text-gray-700 border-gray-200';
                        if ($n->is_active) {
                            if ($n->starts_at && $n->starts_at->isFuture()) { $statusBadge='Scheduled'; $badgeClass='bg-amber-100 text-amber-700 border-amber-200'; }
                            elseif ($n->ends_at && $n->ends_at->isPast()) { $statusBadge='Expired'; $badgeClass='bg-gray-100 text-gray-500 border-gray-200'; }
                            else { $statusBadge='Active'; $badgeClass='bg-emerald-100 text-emerald-700 border-emerald-200'; }
                        }
                    @endphp
                    <tr>
                        <td class="px-4 py-2">
                            <div class="text-gray-900 font-medium">{{ $n->title }}</div>
                            <div class="text-xs text-gray-500 line-clamp-1">{{ $n->message }}</div>
                        </td>
                        <td class="px-4 py-2 text-xs text-gray-600">
                            <div>Start: {{ $n->starts_at?->format('d M Y, H:i') ?? '—' }}</div>
                            <div>End: {{ $n->ends_at?->format('d M Y, H:i') ?? '—' }}</div>
                        </td>
                        <td class="px-4 py-2">
                            <span class="text-[10px] px-2 py-0.5 rounded-full border {{ $badgeClass }}">{{ $statusBadge }}</span>
                        </td>
                        <td class="px-4 py-2 text-right text-gray-900">{{ number_format($n->views) }}</td>
                        <td class="px-4 py-2 text-right text-gray-900">{{ number_format($n->clicks) }}</td>
                        <td class="px-4 py-2">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.notifications.show', $n) }}" class="px-2 py-1 rounded border text-xs">View</a>
                                <a href="{{ route('admin.notifications.edit', $n) }}" class="px-2 py-1 rounded border text-xs">Edit</a>
                                <form method="POST" action="{{ route('admin.notifications.destroy', $n) }}" onsubmit="return confirm('Delete notification?');">
                                    @csrf @method('DELETE')
                                    <button class="px-2 py-1 rounded border text-xs text-red-600">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">No notifications found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <div class="p-3">{{ $items->links() }}</div>
        </div>
    </div>
</x-admin-layout>
