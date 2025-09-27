<x-admin-layout>
    <div class="h-1 bg-gradient-to-r from-indigo-500 via-sky-500 to-emerald-500 rounded-t-xl"></div>

    <div class="py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Comment Detail</h1>
                <p class="text-sm text-gray-500 mt-1">Inspect comment and its replies.</p>
            </div>
            <a href="{{ route('cms.blog.comments.index') }}" class="inline-flex items-center px-3 py-1.5 rounded border text-gray-700 border-gray-300 hover:bg-gray-100 text-xs">Back</a>
        </div>

        <div class="mt-4 grid gap-4 md:grid-cols-2">
            <div class="p-4 border rounded-xl bg-white">
                <div class="text-sm text-gray-500">Post</div>
                <div class="text-gray-900 font-medium">#{{ $comment->post_id }} — {{ $comment->post?->title }}</div>
                <div class="text-xs text-gray-500">/{{ $comment->post?->slug }}</div>
                <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <div class="text-gray-500">Author</div>
                        <div class="text-gray-900 flex items-center gap-2">
                            {{ $comment->user?->name ?? ($comment->author_name ?: 'Anonymous') }}
                            @if($comment->user && method_exists($comment->user,'isAdmin') && $comment->user->isAdmin())
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 border border-blue-200">Admin</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-500">Email</div>
                        <div class="text-gray-900">{{ $comment->user?->email ?? $comment->email ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">When</div>
                        <div class="text-gray-900">{{ $comment->created_at?->format('d M Y, H:i') }}</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-gray-500 text-sm">Content</div>
                    <div class="mt-1 text-gray-900 whitespace-pre-line">{{ $comment->content }}</div>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <form method="POST" action="{{ route('cms.blog.comments.destroy', $comment) }}" onsubmit="return confirm('Delete this comment? This will also delete its replies.');">
                        @csrf
                        @method('DELETE')
                        <button class="px-3 py-1.5 rounded-lg border text-sm text-red-600">Delete Comment</button>
                    </form>
                </div>
            </div>

            <div class="p-4 border rounded-xl bg-white">
                <div class="text-sm font-medium text-gray-800">Responders</div>
                <div class="mt-2 space-y-2">
                    @forelse($responders as $r)
                        <div class="flex items-center justify-between text-sm">
                            <div class="text-gray-900">{{ $r['name'] ?? 'Anonymous' }}</div>
                            <div class="text-gray-500">{{ $r['email'] ?? '—' }}</div>
                        </div>
                    @empty
                        <div class="text-sm text-gray-500">No replies yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="mt-4 p-4 border rounded-xl bg-white">
            <div class="text-sm font-medium text-gray-800">Replies</div>
            <div class="mt-2 space-y-3">
                @forelse($comment->replies as $r)
                    <div class="p-3 border rounded-lg">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-900 font-medium flex items-center gap-2">
                                {{ $r->user?->name ?? ($r->author_name ?: 'Anonymous') }}
                                @if($r->user && method_exists($r->user,'isAdmin') && $r->user->isAdmin())
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 border border-blue-200">Admin</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500 flex items-center gap-3">
                                <span>{{ $r->user?->email ?? $r->email ?? '—' }}</span>
                                <form method="POST" action="{{ route('cms.blog.comments.destroy', $r) }}" onsubmit="return confirm('Delete this reply?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-2 py-1 rounded border text-xs text-red-600">Delete</button>
                                </form>
                            </div>
                        </div>
                        <div class="text-sm text-gray-700 mt-1">{{ $r->content }}</div>
                        <div class="text-xs text-gray-400 mt-1">{{ $r->created_at?->format('d M Y, H:i') }}</div>
                    </div>
                @empty
                    <div class="text-sm text-gray-500">No replies yet.</div>
                @endforelse
            </div>

            <div class="mt-4 p-3 border rounded-lg">
                <div class="text-sm font-medium text-gray-800">Reply as Admin</div>
                <form method="POST" action="{{ route('cms.blog.comments.reply', $comment) }}" class="mt-2 space-y-2">
                    @csrf
                    <textarea name="content" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Write an official reply..." required></textarea>
                    <div class="flex items-center gap-2">
                        <button class="px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm">Post Reply</button>
                        <span class="text-xs text-gray-500">Reply will show with blue Admin badge.</span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
