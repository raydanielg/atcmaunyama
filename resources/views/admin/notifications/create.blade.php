<x-admin-layout>
    <div class="h-1 bg-gradient-to-r from-indigo-500 via-sky-500 to-emerald-500 rounded-t-xl"></div>
    <div class="py-4">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-900">New Notification</h1>
            <a href="{{ route('admin.notifications.index') }}" class="inline-flex items-center px-3 py-1.5 rounded border text-gray-700 border-gray-300 hover:bg-gray-100 text-xs">Back</a>
        </div>

        <form method="POST" action="{{ route('admin.notifications.store') }}" class="mt-4 space-y-4">
            @csrf
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-700">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm" required />
                </div>
                <div>
                    <label class="block text-sm text-gray-700">Action Label (optional)</label>
                    <input type="text" name="action_label" value="{{ old('action_label') }}" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm" />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-700">Action URL (optional)</label>
                    <input type="url" name="action_url" value="{{ old('action_url') }}" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm" />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-700">Message (optional)</label>
                    <textarea name="message" rows="4" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm">{{ old('message') }}</textarea>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm text-gray-700">Starts At (optional)</label>
                    <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm" />
                    <p class="text-xs text-gray-500 mt-1">Leave empty to start immediately.</p>
                </div>
                <div>
                    <label class="block text-sm text-gray-700">Ends At (optional)</label>
                    <input type="datetime-local" name="ends_at" value="{{ old('ends_at') }}" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm" />
                    <p class="text-xs text-gray-500 mt-1">Leave empty for no expiry.</p>
                </div>
                <div class="flex items-center gap-2 mt-6">
                    <input type="checkbox" id="is_active" name="is_active" value="1" class="rounded" {{ old('is_active') ? 'checked' : '' }} />
                    <label for="is_active" class="text-sm text-gray-700">Publish now</label>
                </div>
            </div>

            <div class="pt-2">
                <button class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm">Save Notification</button>
            </div>
        </form>
    </div>
</x-admin-layout>
