<x-admin-layout>
    <div class="p-4">
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
            <p class="text-sm text-gray-500">Update your personal information and view feedbacks received.</p>
        </div>

        @if (session('status'))
            <x-alert type="success" message="{{ session('status') }}" />
        @endif
        @if ($errors->any())
            <x-alert type="error" message="{{ $errors->first() }}" />
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-start">
            <!-- Profile form -->
            <div class="lg:col-span-1 bg-white border rounded-lg p-4">
                <h2 class="font-semibold text-gray-900">Profile Information</h2>
                <p class="text-xs text-gray-500 mb-3">Name, phone number, and region.</p>

                <form action="{{ route('admin.profile.update') }}" method="post" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm text-gray-700">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="07xx xxx xxx" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700">Region</label>
                        <select name="region_id" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Select Region --</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}" {{ (string)old('region_id', $user->region_id) === (string)$region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pt-2">
                        <button class="px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-black">Save Changes</button>
                    </div>
                </form>
            </div>

            <!-- Feedbacks received -->
            <div class="lg:col-span-2 bg-white border rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <h2 class="font-semibold text-gray-900">Feedbacks Received</h2>
                    <span class="text-xs text-gray-500">Total: {{ $feedbacks->total() }}</span>
                </div>

                @if ($feedbacks->count() === 0)
                    <div class="text-center py-10 text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-12 h-12 mx-auto mb-2 text-gray-400"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7.5 8.25h9m-9 3h6.75M21 12c0 4.97-4.03 9-9 9a8.97 8.97 0 01-4.867-1.409L3 21l1.409-4.133A8.97 8.97 0 013 12c0-4.97 4.03-9 9-9s9 4.03 9 9z"/></svg>
                        <div class="text-sm">No feedbacks yet.</div>
                    </div>
                @else
                    <div class="mt-3 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-500 border-b">
                                    <th class="py-2 pr-3">From</th>
                                    <th class="py-2 pr-3">Comment</th>
                                    <th class="py-2 pr-3">Date</th>
                                </tr>
                            </thead>
                            <tbody class="align-top">
                                @foreach($feedbacks as $fb)
                                    <tr class="border-b">
                                        <td class="py-2 pr-3 text-gray-800">{{ $fb->sender?->name ?? 'Anonymous' }}</td>
                                        <td class="py-2 pr-3 text-gray-700 max-w-[640px] break-words">{{ $fb->message }}</td>
                                        <td class="py-2 pr-3 text-gray-500">{{ $fb->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $feedbacks->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
