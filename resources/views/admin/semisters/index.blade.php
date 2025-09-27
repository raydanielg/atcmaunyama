<x-admin-layout>
    <div class="py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Semesters</h1>
                <p class="text-sm text-gray-500">Manage academic semesters and their associated notes.</p>
            </div>
            <a href="{{ route('admin.semisters.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                <span class="material-symbols-outlined">add</span>
                Add Semester
            </a>
        </div>

        <div class="mt-2 font-mono text-[11px] tracking-widest text-gray-400 select-none" aria-hidden="true">- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</div>

        @if (session('success'))
            <div class="mt-3 border border-green-200 bg-green-50 text-green-800 px-3 py-2 rounded-md text-sm">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mt-3 border border-red-200 bg-red-50 text-red-800 px-3 py-2 rounded-md text-sm">{{ session('error') }}</div>
        @endif

        <div class="mt-4 bg-white border rounded-lg">
            <div class="px-4 py-3 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">All Semesters</h3>
            </div>

            <div class="p-4">
                @if($semisters->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($semisters as $semister)
                            <div class="border border-gray-200 rounded-lg p-4 {{ $semister->is_active ? 'bg-white' : 'bg-gray-50 opacity-75' }}">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-gray-900">{{ $semister->name }}</h4>
                                        <div class="mt-2 space-y-1 text-sm text-gray-600">
                                            <div class="flex items-center gap-2">
                                                <span class="material-symbols-outlined text-[16px] text-gray-400">event</span>
                                                <span>{{ $semister->start_date->format('M d, Y') }} - {{ $semister->end_date->format('M d, Y') }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="material-symbols-outlined text-[16px] text-gray-400">description</span>
                                                <span>{{ $semister->description ?? 'No description' }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="material-symbols-outlined text-[16px] {{ $semister->is_active ? 'text-green-500' : 'text-gray-400' }}"></span>
                                                <span>{{ $semister->is_active ? 'Active' : 'Inactive' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 ml-4">
                                        <button onclick="window.__toggleStatus({{ $semister->id }})" class="p-1 text-gray-400 hover:text-gray-600" title="{{ $semister->is_active ? 'Deactivate' : 'Activate' }}">
                                            <span class="material-symbols-outlined text-[18px]">{{ $semister->is_active ? 'visibility' : 'visibility_off' }}</span>
                                        </button>
                                        <a href="{{ route('admin.semisters.edit', $semister) }}" class="p-1 text-gray-400 hover:text-gray-600" title="Edit">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                        <button onclick="window.__deleteSemister({{ $semister->id }})" class="p-1 text-gray-400 hover:text-red-600" title="Delete">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-500">{{ $semister->notes()->count() }} notes</span>
                                        <a href="{{ route('admin.semisters.show', $semister) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                            View Details →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <span class="material-symbols-outlined text-gray-400 text-[48px]">school</span>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No semesters found</h3>
                        <p class="mt-2 text-sm text-gray-500">Get started by creating your first semester.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.semisters.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center gap-2">
                                <span class="material-symbols-outlined">add</span>
                                Add First Semester
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/40" onclick="window.__closeDeleteModal()"></div>
        <div class="relative min-h-full w-full grid place-items-center p-4">
            <div class="bg-white w-full max-w-md rounded-xl shadow-lg overflow-hidden">
                <div class="px-4 py-3 border-b">
                    <h3 class="font-semibold text-gray-900">Delete Semester</h3>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-600">Are you sure you want to delete this semester? This action cannot be undone.</p>
                    <div class="mt-4 flex items-center justify-end gap-3">
                        <button type="button" class="px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 border border-gray-300 rounded-lg" onclick="window.__closeDeleteModal()">Cancel</button>
                        <form id="deleteForm" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-2 text-sm text-white bg-red-600 hover:bg-red-700 rounded-lg">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.__toggleStatus = function(id) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/semesters/${id}/toggle-status`;
            form.innerHTML = '@csrf @method("PATCH")';
            document.body.appendChild(form);
            form.submit();
        };

        window.__deleteSemister = function(id) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            form.action = `/semesters/${id}`;
            modal.classList.remove('hidden');
        };

        window.__closeDeleteModal = function() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
        };

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') window.__closeDeleteModal();
        });
    </script>
</x-admin-layout>
