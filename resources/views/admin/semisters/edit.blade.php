<x-admin-layout>
    <div class="py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Edit Semester</h1>
                <p class="text-sm text-gray-500">Update semester information.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.semisters.show', $semister) }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined">visibility</span>
                    View Details
                </a>
                <a href="{{ route('admin.semisters.index') }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined">arrow_back</span>
                    Back to Semesters
                </a>
            </div>
        </div>

        <div class="mt-2 font-mono text-[11px] tracking-widest text-gray-400 select-none" aria-hidden="true">- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</div>

        <div class="mt-4 max-w-2xl">
            <form action="{{ route('admin.semisters.update', $semister) }}" method="POST" class="bg-white border rounded-lg p-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Semester Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Semester Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $semister->name) }}"
                               class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror"
                               placeholder="e.g., Fall 2024, Spring 2025" required />
                        @error('name')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $semister->start_date->format('Y-m-d')) }}"
                               class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('start_date') border-red-500 @enderror"
                               required />
                        @error('start_date')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $semister->end_date->format('Y-m-d')) }}"
                               class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('end_date') border-red-500 @enderror"
                               required />
                        @error('end_date')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div>
                        <div class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $semister->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                Active Semester
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Active semesters are available for note uploads and student access.</p>
                        @if($semister->notes()->count() > 0)
                            <p class="text-xs text-amber-600 mt-1">Note: This semester has {{ $semister->notes()->count() }} notes. Changing status may affect accessibility.</p>
                        @endif
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                        <textarea name="description" id="description" rows="4"
                                  class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror"
                                  placeholder="Additional information about this semester...">{{ old('description', $semister->description) }}</textarea>
                        @error('description')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.semisters.index') }}" class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 border border-gray-300 rounded-lg">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg">
                        Update Semester
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
