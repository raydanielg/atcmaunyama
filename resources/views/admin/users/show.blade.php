<x-admin-layout>
    <!-- Masthead strip -->
    <div class="h-1 bg-gradient-to-r from-indigo-500 via-sky-500 to-emerald-500 rounded-t-xl"></div>
    <div class="py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">User Details</h1>
                <p class="text-sm text-gray-500 mt-1">View user profile and manage actions.</p>
            </div>
            <a href="{{ route('users.index') }}" class="inline-flex items-center px-3 py-1.5 rounded border text-gray-700 border-gray-300 hover:bg-gray-100 text-xs">Back to list</a>
        </div>

        <!-- Dashed divider under heading -->
        <div class="mt-3 border-t border-dashed border-gray-300"></div>

        @if(session('status'))
            <div class="mt-4 p-3 rounded bg-green-50 text-green-700 border border-green-200">{{ session('status') }}</div>
        @endif

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <h2 class="text-sm font-semibold text-gray-800 mb-3">Profile</h2>
                <dl class="divide-y divide-gray-100">
                    <div class="py-2 grid grid-cols-3 gap-2">
                        <dt class="text-xs text-gray-500">ID</dt>
                        <dd class="col-span-2 text-sm text-gray-900">{{ $user->id }}</dd>
                    </div>
                    <div class="py-2 grid grid-cols-3 gap-2">
                        <dt class="text-xs text-gray-500">Name</dt>
                        <dd class="col-span-2 text-sm text-gray-900">{{ $user->name }}</dd>
                    </div>
                    <div class="py-2 grid grid-cols-3 gap-2">
                        <dt class="text-xs text-gray-500">Email</dt>
                        <dd class="col-span-2 text-sm text-gray-900">{{ $user->email }}</dd>
                    </div>
                    <div class="py-2 grid grid-cols-3 gap-2">
                        <dt class="text-xs text-gray-500">Role</dt>
                        <dd class="col-span-2 text-sm text-gray-900">{{ $user->role }}</dd>
                    </div>
                    <div class="py-2 grid grid-cols-3 gap-2">
                        <dt class="text-xs text-gray-500">Status</dt>
                        <dd class="col-span-2 text-sm">
                            @if($user->banned_at)
                                <span class="inline-flex items-center px-2 py-1 rounded bg-red-50 text-red-700 border border-red-200 text-xs">Banned ({{ $user->banned_at }})</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded bg-green-50 text-green-700 border border-green-200 text-xs">Active</span>
                            @endif
                        </dd>
                    </div>
                    <div class="py-2 grid grid-cols-3 gap-2">
                        <dt class="text-xs text-gray-500">Created</dt>
                        <dd class="col-span-2 text-sm text-gray-900">{{ $user->created_at }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <h2 class="text-sm font-semibold text-gray-800 mb-3">Actions</h2>
                <div class="flex flex-wrap gap-2">
                    @if($user->banned_at)
                        <form action="{{ route('users.unban', $user) }}" method="POST">
                            @csrf
                            <button class="inline-flex items-center px-3 py-2 rounded border text-indigo-700 border-indigo-300 hover:bg-indigo-50 text-xs" onclick="return confirm('Unban this user?')">Unban</button>
                        </form>
                    @else
                        <form action="{{ route('users.ban', $user) }}" method="POST">
                            @csrf
                            <button class="inline-flex items-center px-3 py-2 rounded border text-amber-700 border-amber-300 hover:bg-amber-50 text-xs" onclick="return confirm('Ban this user?')">Ban</button>
                        </form>
                    @endif
                    <form action="{{ route('users.destroy', $user) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="inline-flex items-center px-3 py-2 rounded border text-red-700 border-red-300 hover:bg-red-50 text-xs" onclick="return confirm('Delete this user? This cannot be undone.')">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
