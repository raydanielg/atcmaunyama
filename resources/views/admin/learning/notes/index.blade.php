<x-admin-layout>
    <div class="py-4">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">ALL NOTES</h1>
                <p class="text-sm text-gray-500 mt-1">Manage all notes in the system.</p>
            </div>
        </div>

        <!-- Dashed divider under heading -->
        <div class="mt-3 border-t border-dashed border-gray-300"></div>

        <!-- Controls row: search left, add button right -->
        <div class="mt-3 flex items-center justify-between">
            <form method="GET" class="w-full max-w-xs">
                <div class="relative">
                    <input type="text" name="s" value="{{ $s ?? '' }}" placeholder="Search title..." class="w-full pl-10 pr-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                    <span class="material-symbols-outlined absolute left-2 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                </div>
            </form>
            <button type="button" id="btnOpenAddNote" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm shadow">
                <span class="material-symbols-outlined text-base">add_circle</span>
                <span>Add Note</span>
            </button>
        </div>

        @if(session('status'))
            <div class="mt-4 p-3 rounded bg-green-50 text-green-700 border border-green-200">{{ session('status') }}</div>
        @endif

        <div class="mt-4 bg-white rounded-lg shadow-sm border">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Updated</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse(($notes ?? []) as $note)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $note->id }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $note->title }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ optional($note->user ?? null)->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $note->updated_at?->diffForHumans() }}</td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('learning.notes.show', $note) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded border text-gray-700 border-gray-300 hover:bg-gray-100 text-xs">
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                        <span>View</span>
                                    </a>
                                    <a href="{{ route('learning.notes.edit', $note) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded border text-indigo-700 border-indigo-300 hover:bg-indigo-50 text-xs">
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                        <span>Edit</span>
                                    </a>
                                    <form method="POST" action="{{ route('learning.notes.destroy', $note) }}" onsubmit="return confirm('Delete this note?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded border text-red-700 border-red-300 hover:bg-red-50 text-xs">
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">No notes found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ ($notes ?? null)?->links() }}</div>
    </div>

    <!-- Add Note Modal -->
    <div id="addNoteModal" class="fixed inset-0 bg-black/40 z-40 hidden">
        <div class="min-h-full flex items-center justify-center p-4">
            <div class="w-full max-w-2xl bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 border-b">
                    <h3 class="text-lg font-semibold">Add Note</h3>
                    <button type="button" id="btnCloseAddNote" class="p-1 rounded hover:bg-gray-100">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="p-4">
                    <form id="addNoteForm" class="space-y-4" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Level of Education</label>
                            <select name="level_id" id="levelSelect" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Loading levels...</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Subject</label>
                                <select name="subject_id" id="subjectSelect" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" disabled>
                                    <option value="">Select level first...</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Class</label>
                                <select name="class_id" id="classSelect" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" disabled>
                                    <option value="">Select subject first...</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description / Body</label>
                            <textarea name="body" rows="4" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Upload Document (PDF, Word, PPT, TXT)</label>
                            <input type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.txt" class="mt-1 w-full border rounded-lg px-3 py-2" />
                        </div>

                        <!-- Progress Bar -->
                        <div id="uploadProgressWrap" class="hidden">
                            <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700">
                                <div id="uploadProgressBar" class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" style="width: 0%"> 0%</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-2">
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm shadow">
                                <span class="material-symbols-outlined text-base animate-pulse" id="uploadIcon" style="display:none;">autorenew</span>
                                <span id="uploadText">Upload</span>
                            </button>
                            <button type="button" id="btnCancelUpload" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Tick Popup -->
    <div id="successPopup" class="fixed inset-0 z-50 hidden">
        <div class="min-h-full flex items-center justify-center p-4 bg-black/40">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="mx-auto mb-3 h-16 w-16 rounded-full bg-green-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-600 text-4xl">task_alt</span>
                </div>
                <div class="text-lg font-semibold text-gray-800">Uploaded successfully</div>
            </div>
        </div>
    </div>

    <script>
    (function(){
        const modal = document.getElementById('addNoteModal');
        const btnOpen = document.getElementById('btnOpenAddNote');
        const btnClose = document.getElementById('btnCloseAddNote');
        const btnCancel = document.getElementById('btnCancelUpload');
        const form = document.getElementById('addNoteForm');
        const progressWrap = document.getElementById('uploadProgressWrap');
        const progressBar = document.getElementById('uploadProgressBar');
        const successPopup = document.getElementById('successPopup');
        const levelSelect = document.getElementById('levelSelect');
        const subjectSelect = document.getElementById('subjectSelect');
        const classSelect = document.getElementById('classSelect');
        const uploadIcon = document.getElementById('uploadIcon');
        const uploadText = document.getElementById('uploadText');

        function openModal(){ modal.classList.remove('hidden'); }
        function closeModal(){ modal.classList.add('hidden'); resetForm(); }
        function showSuccess(){ successPopup.classList.remove('hidden'); setTimeout(()=>{ successPopup.classList.add('hidden'); }, 1600); }
        function resetForm(){ form.reset(); progressBar.style.width = '0%'; progressBar.textContent = ' 0%'; progressWrap.classList.add('hidden'); uploadIcon.style.display='none'; uploadText.textContent='Upload'; subjectSelect.innerHTML='<option value="">Select subject...</option>'; classSelect.innerHTML='<option value="">Select class...</option>'; }

        btnOpen?.addEventListener('click', openModal);
        btnClose?.addEventListener('click', closeModal);
        btnCancel?.addEventListener('click', closeModal);
        modal?.addEventListener('click', (e)=>{ if(e.target===modal) closeModal(); });

        // Fetch helpers
        async function fetchJSON(url){
            const r = await fetch(url, { headers: { 'Accept':'application/json' } });
            if (!r.ok) throw new Error('Network error');
            return r.json();
        }
        async function loadLevels(){
            if (!levelSelect) return;
            levelSelect.disabled = true;
            levelSelect.innerHTML = '<option value="">Loading levels...</option>';
            try{
                const data = await fetchJSON('{{ route('learning.notes.levels') }}');
                levelSelect.innerHTML = '<option value="">Select level...</option>' + data.map(l=>`<option value="${l.id}">${l.name}</option>`).join('');
            }catch(e){
                levelSelect.innerHTML = '<option value="">Failed to load levels</option>';
            }finally{
                levelSelect.disabled = false;
            }
        }
        async function loadClasses(levelId){
            if (!classSelect) return;
            classSelect.disabled = true;
            classSelect.innerHTML = '<option value="">Loading classes...</option>';
            // Reset subjects when level changes
            if (subjectSelect){ subjectSelect.disabled = true; subjectSelect.innerHTML = '<option value="">Select class first...</option>'; }
            if (!levelId){
                classSelect.innerHTML = '<option value="">Select level first...</option>';
                return;
            }
            try{
                const data = await fetchJSON('{{ route('learning.notes.classes') }}?level_id='+encodeURIComponent(levelId||''));
                classSelect.innerHTML = '<option value="">Select class...</option>' + data.map(c=>`<option value="${c.id}">${c.name}</option>`).join('');
            }catch(e){
                classSelect.innerHTML = '<option value="">Failed to load classes</option>';
            }finally{
                classSelect.disabled = false;
            }
        }
        async function loadSubjects(classId){
            if (!subjectSelect) return;
            subjectSelect.disabled = true;
            subjectSelect.innerHTML = '<option value="">Loading subjects...</option>';
            if (!classId){
                subjectSelect.innerHTML = '<option value="">Select class first...</option>';
                return;
            }
            try{
                const data = await fetchJSON('{{ route('learning.notes.subjects') }}?class_id='+encodeURIComponent(classId||''));
                subjectSelect.innerHTML = '<option value="">Select subject...</option>' + data.map(s=>`<option value="${s.id}">${s.name}</option>`).join('');
            }catch(e){
                subjectSelect.innerHTML = '<option value="">Failed to load subjects</option>';
            }finally{
                subjectSelect.disabled = false;
            }
        }

        levelSelect?.addEventListener('change', ()=> loadClasses(levelSelect.value));
        classSelect?.addEventListener('change', ()=> loadSubjects(classSelect.value));

        // Initialize levels when modal opens and reset dependent selects
        btnOpen?.addEventListener('click', ()=>{
            if (classSelect){ classSelect.disabled = true; classSelect.innerHTML = '<option value="">Select level first...</option>'; }
            if (subjectSelect){ subjectSelect.disabled = true; subjectSelect.innerHTML = '<option value="">Select class first...</option>'; }
            loadLevels();
        });

        // Upload with progress via XHR
        form?.addEventListener('submit', function(e){
            e.preventDefault();
            const url = '{{ route('learning.notes.store') }}';
            const fd = new FormData(form);
            progressWrap.classList.remove('hidden');
            uploadIcon.style.display='inline-block';
            uploadText.textContent='Uploading...';

            const xhr = new XMLHttpRequest();
            xhr.open('POST', url, true);
            xhr.setRequestHeader('Accept', 'application/json');
            // CSRF
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            if (tokenMeta) xhr.setRequestHeader('X-CSRF-TOKEN', tokenMeta.getAttribute('content'));

            xhr.upload.addEventListener('progress', function(ev){
                if (ev.lengthComputable) {
                    const pct = Math.round((ev.loaded / ev.total) * 100);
                    progressBar.style.width = pct + '%';
                    progressBar.textContent = ' ' + pct + '%';
                }
            });

            xhr.onreadystatechange = function(){
                if (xhr.readyState === 4) {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        progressBar.style.width = '100%';
                        progressBar.textContent = ' 100%';
                        showSuccess();
                        closeModal();
                        // Reload page to reflect new note
                        window.location.reload();
                    } else {
                        alert('Upload failed. Please try again.');
                        uploadIcon.style.display='none';
                        uploadText.textContent='Upload';
                    }
                }
            };

            xhr.send(fd);
        });
    })();
    </script>
</x-admin-layout>
