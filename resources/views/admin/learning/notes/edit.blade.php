<x-admin-layout>
    <!-- Masthead strip -->
    <div class="h-1 bg-gradient-to-r from-indigo-500 via-sky-500 to-emerald-500 rounded-t-xl"></div>

    <div class="py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Edit Note</h1>
                <p class="text-sm text-gray-500 mt-1">Update note information.</p>
            </div>
            <a href="{{ route('learning.notes.index') }}" class="inline-flex items-center px-3 py-1.5 rounded border text-gray-700 border-gray-300 hover:bg-gray-100 text-xs">Back to list</a>
        </div>

        <!-- Dashed divider under heading -->
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

        <form method="POST" action="{{ route('learning.notes.update', $note) }}" class="mt-4 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" value="{{ old('title', $note->title) }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Body</label>
                <textarea name="body" rows="6" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('body', $note->body) }}</textarea>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm shadow">
                    <span class="material-symbols-outlined text-base">save</span>
                    <span>Save Changes</span>
                </button>
                <a href="{{ route('learning.notes.show', $note) }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm">
                    <span class="material-symbols-outlined text-base">visibility</span>
                    <span>View</span>
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>
