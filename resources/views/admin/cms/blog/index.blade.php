<x-admin-layout>
    <div class="h-1 bg-gradient-to-r from-indigo-500 via-sky-500 to-emerald-500 rounded-t-xl"></div>

    <div class="py-4">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Blog Posts</h1>
                <p class="text-sm text-gray-500 mt-1">Manage your blog content.</p>
            </div>
            <a href="{{ route('cms.blog.posts.create') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm shadow">
                <span class="material-symbols-outlined text-base">add</span>
                <span>New Post</span>
            </a>
        </div>

        <div class="mt-4">
            <form method="GET" class="flex items-center gap-2">
                <input type="text" name="s" value="{{ $s ?? '' }}" placeholder="Search posts..." class="w-full md:w-72 border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                <button class="px-3 py-2 rounded-lg border border-gray-300 text-sm">Search</button>
            </form>
        </div>

        <div class="mt-4 bg-white border rounded-xl overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-2">Title</th>
                        <th class="text-left px-4 py-2">Created</th>
                        <th class="text-left px-4 py-2">Views</th>
                        <th class="text-right px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                @forelse($posts as $post)
                    <tr>
                        <td class="px-4 py-2">
                            <div class="font-medium text-gray-900">{{ $post->title }}</div>
                            <div class="text-xs text-gray-500">/{{ $post->slug }}</div>
                        </td>
                        <td class="px-4 py-2 text-gray-700">{{ $post->created_at?->format('d M Y, H:i') }}</td>
                        <td class="px-4 py-2 text-gray-700">{{ $post->views }}</td>
                        <td class="px-4 py-2">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('cms.blog.posts.edit', $post) }}" class="px-2 py-1 rounded border text-xs">Edit</a>
                                <form method="POST" action="{{ route('cms.blog.posts.destroy', $post) }}" onsubmit="return confirm('Delete this post?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-2 py-1 rounded border text-xs text-red-600">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">No posts found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <div class="p-3">{{ $posts->links() }}</div>
        </div>
    </div>
</x-admin-layout>
