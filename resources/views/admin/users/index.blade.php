<x-admin-layout>
    <!-- Masthead strip -->
    <div class="h-1 bg-gradient-to-r from-indigo-500 via-sky-500 to-emerald-500 rounded-t-xl"></div>
    <div class="py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">User Management</h1>
                <p class="text-sm text-gray-500 mt-1">View and manage users. Ban, unban, delete and inspect details.</p>
            </div>
            <div class="flex items-center gap-3">
                <form method="GET" class="w-full max-w-xs">
                    <div class="relative">
                        <input type="text" name="s" value="{{ $s ?? '' }}" placeholder="Search name or email..." class="w-full pl-10 pr-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        <span class="material-symbols-outlined absolute left-2 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                    </div>
                </form>
                <button id="btnOpenAddUser" type="button" class="inline-flex items-center gap-1 px-3 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                    <span class="material-symbols-outlined text-sm">person_add</span>
                    <span class="text-sm">Add User</span>
                </button>
            </div>
        </div>

        <!-- Dashed divider under heading -->
        <div class="mt-3 border-t border-dashed border-gray-300"></div>

        @if(session('status'))
            <div class="mt-4 p-3 rounded bg-green-50 text-green-700 border border-green-200">{{ session('status') }}</div>
        @endif

        <div class="mt-6 overflow-hidden bg-white border border-gray-200 rounded-xl shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $user->id }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-sm">
                                @php
                                    $isSelf = auth()->id() === $user->id;
                                    $isSuper = strtolower((string)$user->role) === 'super administrator';
                                    $isPrimary = (int)$user->id === 1;
                                @endphp
                                @if($isSuper || $isPrimary)
                                    <span class="inline-flex items-center px-2 py-1 rounded bg-purple-50 text-purple-700 border border-purple-200 text-xs">Super Administrator</span>
                                @else
                                    <form method="POST" action="{{ route('users.update_role', $user) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <select name="role" class="border rounded px-2 py-1 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" {{ ($isSelf || $isPrimary) ? 'disabled' : '' }}>
                                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>user</option>
                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>admin</option>
                                        </select>
                                        <button type="submit" class="px-2 py-1 rounded bg-indigo-600 text-white text-xs disabled:opacity-50" {{ ($isSelf || $isPrimary) ? 'disabled' : '' }} onclick="return confirm('Change role for this user?')">Save</button>
                                    </form>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($user->banned_at)
                                    <span class="inline-flex items-center px-2 py-1 rounded bg-red-50 text-red-700 border border-red-200 text-xs">Banned</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded bg-green-50 text-green-700 border border-green-200 text-xs">Active</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('users.show', $user) }}" class="inline-flex items-center px-3 py-1.5 rounded border text-gray-700 border-gray-300 hover:bg-gray-100 text-xs">View</a>
                                    @if(!$isPrimary)
                                        @if($user->banned_at)
                                            <form action="{{ route('users.unban', $user) }}" method="POST">
                                                @csrf
                                                <button class="inline-flex items-center px-3 py-1.5 rounded border text-indigo-700 border-indigo-300 hover:bg-indigo-50 text-xs" onclick="return confirm('Unban this user?')">Unban</button>
                                            </form>
                                        @else
                                            <form action="{{ route('users.ban', $user) }}" method="POST">
                                                @csrf
                                                <button class="inline-flex items-center px-3 py-1.5 rounded border text-amber-700 border-amber-300 hover:bg-amber-50 text-xs" onclick="return confirm('Ban this user?')">Ban</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('users.destroy', $user) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="inline-flex items-center px-3 py-1.5 rounded border text-red-700 border-red-300 hover:bg-red-50 text-xs" onclick="return confirm('Delete this user? This cannot be undone.')">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $users->links() }}</div>
    </div>

    <!-- Add User Modal -->
    <div id="addUserModal" class="fixed inset-0 bg-black/40 z-40 hidden">
        <div class="min-h-full flex items-center justify-center p-4">
            <div class="w-full max-w-lg bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-5 py-4 border-b flex items-center justify-between">
                    <h2 class="text-lg font-semibold">Add New User</h2>
                    <button type="button" id="btnCloseAddUser" class="text-gray-500 hover:text-gray-700">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('users.store') }}" class="px-5 py-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Name</label>
                            <input name="name" type="text" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Email</label>
                            <input name="email" type="email" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Role</label>
                            <select name="role" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="user">user</option>
                                <option value="admin">admin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Phone (optional)</label>
                            <input name="phone" type="text" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                        <div class="md:col-span-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm text-gray-700 mb-1">Password (optional)</label>
                                    <input name="password" type="password" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700 mb-1">Confirm Password</label>
                                    <input name="password_confirmation" type="password" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">If left blank, a temporary password will be generated and shown after creation.</p>
                        </div>
                    </div>

                    <div class="mt-5 flex items-center justify-end gap-3">
                        <button type="button" id="btnCancelAddUser" class="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function(){
            const modal = document.getElementById('addUserModal');
            const openBtn = document.getElementById('btnOpenAddUser');
            const closeBtn = document.getElementById('btnCloseAddUser');
            const cancelBtn = document.getElementById('btnCancelAddUser');
            function open(){ modal.classList.remove('hidden'); }
            function close(){ modal.classList.add('hidden'); }
            if(openBtn) openBtn.addEventListener('click', open);
            if(closeBtn) closeBtn.addEventListener('click', close);
            if(cancelBtn) cancelBtn.addEventListener('click', close);
            // Close on backdrop click
            if(modal) modal.addEventListener('click', function(e){ if(e.target === modal) close(); });
        })();
    </script>
</x-admin-layout>
