<x-admin-layout>
    <div class="h-1 bg-gradient-to-r from-indigo-500 via-sky-500 to-emerald-500 rounded-t-xl"></div>

    <div class="py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Edit Blog Post</h1>
                <p class="text-sm text-gray-500 mt-1">Update your content.</p>
            </div>
            <a href="{{ route('cms.blog.posts.index') }}" class="inline-flex items-center px-3 py-1.5 rounded border text-gray-700 border-gray-300 hover:bg-gray-100 text-xs">Back to list</a>
        </div>

        <div class="mt-3 border-t border-dashed border-gray-300"></div>

        @if ($errors->any())
            <div class="mt-4 p-3 rounded bg-red-50 text-red-700 border border-red-200">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('cms.blog.posts.update', $post) }}" class="mt-4 space-y-4" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" value="{{ old('title', $post->title) }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Hero Image</label>
                    <input type="file" name="image" accept="image/*" class="mt-1 w-full border rounded-lg px-3 py-2" />
                    @if($post->image_path)
                        <div class="mt-2 text-xs text-gray-500">Current: <a href="{{ asset('storage/'.$post->image_path) }}" target="_blank" class="text-indigo-600 hover:underline">view image</a></div>
                    @endif
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Short Excerpt</label>
                <textarea name="excerpt" rows="2" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Optional summary shown in lists">{{ old('excerpt', $post->excerpt) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Content</label>
                <textarea name="content" rows="10" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>{{ old('content', $post->content) }}</textarea>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm shadow">
                    <span class="material-symbols-outlined text-base">save</span>
                    <span>Save Changes</span>
                </button>
                <a href="{{ route('cms.blog.posts.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm">
                    <span class="material-symbols-outlined text-base">cancel</span>
                    <span>Cancel</span>
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>
