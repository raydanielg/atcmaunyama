<x-admin-layout>
    <div class="py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">{{ $semister->name }}</h1>
                <p class="text-sm text-gray-500">Semester details and associated notes.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.semisters.edit', $semister) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined">edit</span>
                    Edit
                </a>
                <a href="{{ route('admin.semisters.index') }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined">arrow_back</span>
                    Back to Semesters
                </a>
            </div>
        </div>

        <div class="mt-2 font-mono text-[11px] tracking-widest text-gray-400 select-none" aria-hidden="true">- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</div>

        <!-- Semester Information -->
        <div class="mt-4 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Semester Details -->
            <div class="lg:col-span-1">
                <div class="bg-white border rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Semester Information</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $semister->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Duration</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $semister->start_date->format('M d, Y') }} - {{ $semister->end_date->format('M d, Y') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $semister->start_date->diffInDays($semister->end_date) }} days
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <div class="mt-1 flex items-center gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $semister->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $semister->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>

                        @if($semister->description)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $semister->description }}</p>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Created</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $semister->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            <div class="lg:col-span-2">
                <div class="bg-white border rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Notes ({{ $notes->count() }})</h3>
                        <a href="{{ route('learning.notes.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                            <span class="material-symbols-outlined">add</span>
                            Upload Note
                        </a>
                    </div>

                    <div class="p-6">
                        @if($notes->count() > 0)
                            <div class="space-y-4">
                                @foreach($notes as $note)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h4 class="text-lg font-semibold text-gray-900">{{ $note->title }}</h4>
                                                <div class="mt-2 space-y-1 text-sm text-gray-600">
                                                    <div class="flex items-center gap-4">
                                                        <span class="flex items-center gap-1">
                                                            <span class="material-symbols-outlined text-[16px]">school</span>
                                                            {{ $note->level->name ?? 'No Level' }}
                                                        </span>
                                                        <span class="flex items-center gap-1">
                                                            <span class="material-symbols-outlined text-[16px]">library_books</span>
                                                            {{ $note->subject->name ?? 'No Subject' }}
                                                        </span>
                                                        <span class="flex items-center gap-1">
                                                            <span class="material-symbols-outlined text-[16px]">view_list</span>
                                                            {{ $note->class->name ?? 'No Class' }}
                                                        </span>
                                                    </div>
                                                    @if($note->description)
                                                        <p class="text-gray-700">{{ Str::limit($note->description, 150) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2 ml-4">
                                                <a href="{{ route('learning.notes.show', $note) }}" class="p-1 text-gray-400 hover:text-gray-600" title="View">
                                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                                </a>
                                                <a href="{{ route('learning.notes.edit', $note) }}" class="p-1 text-gray-400 hover:text-gray-600" title="Edit">
                                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <span class="material-symbols-outlined text-gray-400 text-[48px]">description</span>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">No notes found</h3>
                                <p class="mt-2 text-sm text-gray-500">This semester doesn't have any notes uploaded yet.</p>
                                <div class="mt-6">
                                    <a href="{{ route('learning.notes.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center gap-2">
                                        <span class="material-symbols-outlined">add</span>
                                        Upload First Note
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
