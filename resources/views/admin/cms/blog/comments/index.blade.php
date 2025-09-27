<x-admin-layout>
    <div class="h-1 bg-gradient-to-r from-indigo-500 via-sky-500 to-emerald-500 rounded-t-xl"></div>
    <div class="py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Blog Comments</h1>
                <p class="text-sm text-gray-500 mt-1">Browse and inspect comments and replies.</p>
            </div>
            <a href="{{ route('cms.blog.posts.index') }}" class="inline-flex items-center px-3 py-1.5 rounded border text-gray-700 border-gray-300 hover:bg-gray-100 text-xs">Back to Blog</a>
        </div>

        <div class="mt-4">
            <form method="GET" class="flex flex-wrap items-center gap-2">
                <input type="text" name="s" value="{{ $s ?? '' }}" placeholder="Search author/email/content" class="w-full md:w-72 border rounded-lg px-3 py-2 text-sm" />
                <input type="number" name="post_id" value="{{ $post ?? '' }}" placeholder="Post ID (optional)" class="w-full md:w-40 border rounded-lg px-3 py-2 text-sm" />
                <button class="px-3 py-2 rounded-lg border border-gray-300 text-sm">Filter</button>
            </form>
        </div>

        <div class="mt-4 bg-white border rounded-xl overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="text-left px-4 py-2">Post</th>
                    <th class="text-left px-4 py-2">Author</th>
                    <th class="text-left px-4 py-2">Excerpt</th>
                    <th class="text-left px-4 py-2">When</th>
                    <th class="text-right px-4 py-2">Action</th>
                </tr>
                </thead>
                <tbody class="divide-y">
                @forelse($comments as $c)
                    <tr>
                        <td class="px-4 py-2">
                            <div class="font-medium text-gray-900">#{{ $c->post_id }} — {{ $c->post?->title }}</div>
                            <div class="text-xs text-gray-500">/{{ $c->post?->slug }}</div>
                        </td>
                        <td class="px-4 py-2">
                            <div class="text-gray-900">{{ $c->user?->name ?? ($c->author_name ?: 'Anonymous') }}</div>
                            <div class="text-xs text-gray-500">{{ $c->user?->email ?? $c->email }}</div>
                        </td>
                        <td class="px-4 py-2 text-gray-700 line-clamp-2">{{ \Illuminate\Support\Str::limit($c->content, 80) }}</td>
                        <td class="px-4 py-2 text-gray-500">{{ $c->created_at?->format('d M Y, H:i') }}</td>
                        <td class="px-4 py-2">
                            <div class="flex items-center justify-end">
                                <a href="{{ route('cms.blog.comments.show', $c) }}" class="px-2 py-1 rounded border text-xs">View</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">No comments found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <div class="p-3">{{ $comments->links() }}</div>
        </div>
    </div>
</x-admin-layout>
