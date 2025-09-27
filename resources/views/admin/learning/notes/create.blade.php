<x-admin-layout>
    <!-- Masthead strip -->
    <div class="h-1 bg-gradient-to-r from-indigo-500 via-sky-500 to-emerald-500 rounded-t-xl"></div>

    <div class="py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Add Note</h1>
                <p class="text-sm text-gray-500 mt-1">Create a new note.</p>
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

        <form method="POST" action="{{ route('learning.notes.store') }}" class="mt-4 space-y-4" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Level</label>
                    <select name="level_id" id="level" class="mt-1 block w-full rounded-md border border-gray-300 py-2 px-3 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                        <option value="">Select Level</option>
                        @foreach(\App\Models\Level::orderBy('name')->get() as $level)
                            <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Class</label>
                    <select name="class_id" id="class" class="mt-1 block w-full rounded-md border border-gray-300 py-2 px-3 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                        <option value="">Select Class</option>
                        @if(old('class_id'))
                            @php $class = \App\Models\SchoolClass::find(old('class_id')); @endphp
                            @if($class)
                                <option value="{{ $class->id }}" selected>{{ $class->name }}</option>
                            @endif
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Subject</label>
                    <select name="subject_id" id="subject" class="mt-1 block w-full rounded-md border border-gray-300 py-2 px-3 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                        <option value="">Select Subject</option>
                        @if(old('subject_id'))
                            @php $subject = \App\Models\Subject::find(old('subject_id')); @endphp
                            @if($subject)
                                <option value="{{ $subject->id }}" selected>{{ $subject->name }}</option>
                            @endif
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Semester</label>
                    <select name="semister_id" id="semister" class="mt-1 block w-full rounded-md border border-gray-300 py-2 px-3 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                        <option value="">Select Semester</option>
                        @foreach(\App\Models\Semister::active()->orderBy('start_date', 'desc')->get() as $semester)
                            <option value="{{ $semester->id }}" {{ old('semister_id') == $semester->id ? 'selected' : '' }}>{{ $semester->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required />
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Body</label>
                <textarea name="body" rows="6" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('body') }}</textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">File (Optional)</label>
                <input type="file" name="file" class="mt-1 block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-indigo-50 file:text-indigo-700
                    hover:file:bg-indigo-100"
                    accept=".pdf,.doc,.docx,.ppt,.pptx,.txt">
                <p class="mt-1 text-xs text-gray-500">PDF, DOC, DOCX, PPT, PPTX, or TXT (Max: 20MB)</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm shadow">
                    <span class="material-symbols-outlined text-base">save</span>
                    <span>Save</span>
                </button>
                <a href="{{ route('learning.notes.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm">
                    <span class="material-symbols-outlined text-base">cancel</span>
                    <span>Cancel</span>
                </a>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const levelSelect = document.getElementById('level');
            const classSelect = document.getElementById('class');
            const subjectSelect = document.getElementById('subject');
            const semisterSelect = document.getElementById('semister');

            // Load classes when level changes
            if (levelSelect) {
                levelSelect.addEventListener('change', function() {
                    const levelId = this.value;
                    
                    // Clear and disable dependent selects
                    classSelect.innerHTML = '<option value="">Loading classes...</option>';
                    classSelect.disabled = true;
                    subjectSelect.innerHTML = '<option value="">Select Class first</option>';
                    subjectSelect.disabled = true;
                    
                    if (!levelId) {
                        classSelect.innerHTML = '<option value="">Select Level first</option>';
                        return;
                    }
                    
                    // Fetch classes for the selected level
                    fetch(`/admin/learning/notes/classes?level_id=${levelId}`)
                        .then(response => response.json())
                        .then(data => {
                            classSelect.innerHTML = '<option value="">Select Class</option>';
                            data.forEach(classItem => {
                                const option = document.createElement('option');
                                option.value = classItem.id;
                                option.textContent = classItem.name;
                                classSelect.appendChild(option);
                            });
                            classSelect.disabled = false;
                            
                            // If there's an old value, select it
                            const oldClassId = '{{ old('class_id') }}';
                            if (oldClassId) {
                                classSelect.value = oldClassId;
                                // Trigger change event to load subjects
                                classSelect.dispatchEvent(new Event('change'));
                            }
                        })
                        .catch(error => {
                            console.error('Error loading classes:', error);
                            classSelect.innerHTML = '<option value="">Error loading classes</option>';
                        });
                });
            }

            // Load subjects when class changes
            if (classSelect) {
                classSelect.addEventListener('change', function() {
                    const classId = this.value;
                    
                    // Clear and disable subject select
                    subjectSelect.innerHTML = '<option value="">Loading subjects...</option>';
                    subjectSelect.disabled = true;
                    
                    if (!classId) {
                        subjectSelect.innerHTML = '<option value="">Select Class first</option>';
                        return;
                    }
                    
                    // Fetch subjects for the selected class
                    fetch(`/admin/learning/notes-subjects?class_id=${classId}`)
                        .then(response => response.json())
                        .then(data => {
                            subjectSelect.innerHTML = '<option value="">Select Subject</option>';
                            data.forEach(subject => {
                                const option = document.createElement('option');
                                option.value = subject.id;
                                option.textContent = subject.name;
                                subjectSelect.appendChild(option);
                            });
                            subjectSelect.disabled = false;
                            
                            // If there's an old value, select it
                            const oldSubjectId = '{{ old('subject_id') }}';
                            if (oldSubjectId) {
                                subjectSelect.value = oldSubjectId;
                            }
                        })
                        .catch(error => {
                            console.error('Error loading subjects:', error);
                            subjectSelect.innerHTML = '<option value="">Error loading subjects</option>';
                        });
                });
            }

            // Trigger level change on page load if level is already selected
            if (levelSelect && levelSelect.value) {
                levelSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
    @endpush
</x-admin-layout>
