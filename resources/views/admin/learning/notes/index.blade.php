<x-admin-layout>
    <div class="py-4">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">COURSE NOTES</h1>
                <p class="text-sm text-gray-500 mt-1">Manage notes for different courses, classes, and subjects.</p>
            </div>
        </div>

    <!-- View Note Modal -->
    <div id="viewNoteModal" class="fixed inset-0 bg-black/40 z-40 hidden">
        <div class="min-h-full flex items-center justify-center p-4">
            <div class="w-full max-w-xl bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 border-b">
                    <h3 class="text-lg font-semibold">View Note</h3>
                    <div class="flex items-center gap-2">
                        <a id="viewNoteOpenNew" href="#" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm">
                            <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                            Open
                        </a>
                        <button type="button" id="btnCloseViewNote" class="p-1 rounded hover:bg-gray-100"><span class="material-symbols-outlined">close</span></button>
                    </div>
                </div>
                <div class="p-4 space-y-3">
                    <div>
                        <div class="text-xs text-gray-500">Title</div>
                        <div id="viewNoteTitle" class="text-gray-900 font-medium">-</div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-xs text-gray-500">Author</div>
                            <div id="viewNoteAuthor" class="text-gray-900">-</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Updated</div>
                            <div id="viewNoteUpdated" class="text-gray-900">-</div>
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Summary</div>
                        <div id="viewNoteSummary" class="text-gray-800 text-sm">-</div>
                    </div>
                </div>
                <div class="px-4 py-3 border-t flex items-center justify-end">
                    <button type="button" id="btnCloseViewNote2" class="px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Note Modal -->
    <div id="editNoteModal" class="fixed inset-0 bg-black/40 z-40 hidden">
        <div class="min-h-full flex items-center justify-center p-4">
            <div class="w-full max-w-2xl bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 border-b">
                    <h3 class="text-lg font-semibold">Edit Note</h3>
                    <button type="button" id="btnCloseEditNote" class="p-1 rounded hover:bg-gray-100">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="p-4">
                    <form id="editNoteForm" method="POST" action="#" class="space-y-4" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" id="editNoteTitle" name="title" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Course</label>
                            <select name="level_id" id="editLevelSelect" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Loading courses...</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Subject</label>
                                <select name="subject_id" id="editSubjectSelect" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" disabled>
                                    <option value="">Select course first...</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Class</label>
                                <select name="class_id" id="editClassSelect" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" disabled>
                                    <option value="">Select subject first...</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description / Body</label>
                            <textarea id="editNoteBody" name="body" rows="4" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Replace Document (optional)</label>
                            <input type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.txt" class="mt-1 w-full border rounded-lg px-3 py-2" />
                        </div>
                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" id="btnCancelEditNote" class="px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Modal (shared) -->
    <div id="confirmModal" class="fixed inset-0 bg-black/30 hidden z-50">
        <div class="min-h-full w-full grid place-items-center p-4">
            <div class="bg-white rounded-lg shadow max-w-sm w-full">
                <div class="px-4 py-3 border-b flex items-center justify-between">
                    <h3 id="confirmTitle" class="font-semibold">Confirm</h3>
                    <button id="confirmClose" class="p-1 hover:bg-gray-100 rounded" title="Close">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="p-4">
                    <p id="confirmMessage" class="text-sm text-gray-700">Are you sure?</p>
                </div>
                <div class="px-4 pb-4 flex items-center justify-end gap-2">
                    <button id="confirmCancel" class="px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button id="confirmOk" class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white">Yes, Delete</button>
                </div>
            </div>
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
                                    <button type="button"
                                        class="btnViewNote inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded border text-gray-700 border-gray-300 hover:bg-gray-100 text-xs"
                                        data-id="{{ $note->id }}"
                                        data-title="{{ $note->title }}"
                                        data-user="{{ optional($note->user ?? null)->name ?? '—' }}"
                                        data-updated="{{ $note->updated_at?->diffForHumans() }}"
                                        data-url="{{ route('notes.preview', $note) }}"
                                        data-body="{{ Str::limit(strip_tags($note->body ?? ''), 300) }}"
                                    >
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                        <span>View</span>
                                    </button>
                                    <button type="button"
                                        class="btnEditNote inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded border text-indigo-700 border-indigo-300 hover:bg-indigo-50 text-xs"
                                        data-id="{{ $note->id }}"
                                        data-title="{{ $note->title }}"
                                        data-level-id="{{ $note->level_id ?? '' }}"
                                        data-subject-id="{{ $note->subject_id ?? '' }}"
                                        data-class-id="{{ $note->class_id ?? '' }}"
                                        data-body="{{ $note->body ?? '' }}"
                                    >
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                        <span>Edit</span>
                                    </button>
                                    <form method="POST" action="{{ route('learning.notes.destroy', $note) }}" class="js-confirm-delete">
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
                            <label class="block text-sm font-medium text-gray-700">Course</label>
                            <select name="level_id" id="levelSelect" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Loading courses...</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Class</label>
                                <select name="class_id" id="classSelect" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" disabled>
                                    <option value="">Select course first...</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Semester</label>
                                <select name="semister_id" id="semisterSelect" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" disabled>
                                    <option value="">Select class first...</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Subject</label>
                                <select name="subject_id" id="subjectSelect" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" disabled>
                                    <option value="">Select semester first...</option>
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
        const semisterSelect = document.getElementById('semisterSelect');
        const uploadIcon = document.getElementById('uploadIcon');
        const uploadText = document.getElementById('uploadText');

        function openModal(){ modal.classList.remove('hidden'); }
        function closeModal(){ modal.classList.add('hidden'); resetForm(); }
        function showSuccess(){ successPopup.classList.remove('hidden'); setTimeout(()=>{ successPopup.classList.add('hidden'); }, 1600); }
        function resetForm(){ form.reset(); progressBar.style.width = '0%'; progressBar.textContent = ' 0%'; progressWrap.classList.add('hidden'); uploadIcon.style.display='none'; uploadText.textContent='Upload'; subjectSelect.innerHTML='<option value="">Select semester first...</option>'; classSelect.innerHTML='<option value="">Select course first...</option>'; if(semisterSelect){ semisterSelect.innerHTML='<option value="">Select class first...</option>'; semisterSelect.disabled=true; } }

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
            levelSelect.innerHTML = '<option value="">Loading courses...</option>';
            try{
                const data = await fetchJSON('{{ route('learning.notes.levels') }}');
                levelSelect.innerHTML = '<option value="">Select course...</option>' + data.map(l=>`<option value="${l.id}">${l.name}</option>`).join('');
            }catch(e){
                levelSelect.innerHTML = '<option value="">Failed to load courses</option>';
            }finally{
                levelSelect.disabled = false;
            }
        }
        async function loadClasses(levelId){
            if (!classSelect) return;
            classSelect.disabled = true;
            classSelect.innerHTML = '<option value="">Loading classes...</option>';
            // Reset subjects when level changes
            if (subjectSelect){ subjectSelect.disabled = true; subjectSelect.innerHTML = '<option value="">Select semester first...</option>'; }
            if (semisterSelect){ semisterSelect.disabled = true; semisterSelect.innerHTML = '<option value="">Select class first...</option>'; }
            if (!levelId){
                classSelect.innerHTML = '<option value="">Select course first...</option>';
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
        async function loadSemisters(){
            if (!semisterSelect) return;
            semisterSelect.disabled = true;
            semisterSelect.innerHTML = '<option value="">Loading semesters...</option>';
            try{
                const data = await fetchJSON('{{ route('learning.notes.semisters') }}');
                semisterSelect.innerHTML = '<option value="">Select semester...</option>' + data.map(s=>`<option value="${s.id}">${s.name}</option>`).join('');
            }catch(e){
                semisterSelect.innerHTML = '<option value="">Failed to load semesters</option>';
            }finally{
                semisterSelect.disabled = false;
            }
        }
        async function loadSubjects(classId){
            if (!subjectSelect) return;
            subjectSelect.disabled = true;
            subjectSelect.innerHTML = '<option value="">Loading subjects...</option>';
            if (!classId){
                subjectSelect.innerHTML = '<option value="">Select semester first...</option>';
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
        classSelect?.addEventListener('change', ()=> { loadSemisters(); subjectSelect.disabled = true; subjectSelect.innerHTML = '<option value="">Select semester first...</option>'; });
        semisterSelect?.addEventListener('change', ()=> loadSubjects(classSelect.value));

        // Initialize levels when modal opens and reset dependent selects
        btnOpen?.addEventListener('click', ()=>{
            if (classSelect){ classSelect.disabled = true; classSelect.innerHTML = '<option value="">Select course first...</option>'; }
            if (semisterSelect){ semisterSelect.disabled = true; semisterSelect.innerHTML = '<option value="">Select class first...</option>'; }
            if (subjectSelect){ subjectSelect.disabled = true; subjectSelect.innerHTML = '<option value="">Select semester first...</option>'; }
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

    <script>
    (function(){
        // Shared helpers
        async function fetchJSON(url){ const r = await fetch(url, { headers: { 'Accept':'application/json' }}); if(!r.ok) throw new Error('HTTP '+r.status); return r.json(); }
        function hideGlobalLoader(){ try{ const gl=document.getElementById('globalPageLoader'); if(gl){ gl.classList.add('hidden'); gl.classList.remove('flex'); } }catch{}
        }

        // View Note modal
        const vModal = document.getElementById('viewNoteModal');
        const vTitle = document.getElementById('viewNoteTitle');
        const vAuthor = document.getElementById('viewNoteAuthor');
        const vUpdated = document.getElementById('viewNoteUpdated');
        const vSummary = document.getElementById('viewNoteSummary');
        const vOpenNew = document.getElementById('viewNoteOpenNew');
        const vClose1 = document.getElementById('btnCloseViewNote');
        const vClose2 = document.getElementById('btnCloseViewNote2');
        function openV(){ vModal?.classList.remove('hidden'); }
        function closeV(){ vModal?.classList.add('hidden'); }
        document.querySelectorAll('.btnViewNote').forEach(btn => {
            btn.addEventListener('click', ()=>{
                vTitle.textContent = btn.getAttribute('data-title') || '-';
                vAuthor.textContent = btn.getAttribute('data-user') || '-';
                vUpdated.textContent = btn.getAttribute('data-updated') || '-';
                vSummary.textContent = btn.getAttribute('data-body') || '-';
                const url = btn.getAttribute('data-url') || '#';
                if (vOpenNew) vOpenNew.href = url;
                openV();
            });
        });
        vClose1?.addEventListener('click', closeV);
        vClose2?.addEventListener('click', closeV);
        vModal?.addEventListener('click', (e)=>{ if(e.target===vModal) closeV(); });

        // Edit Note modal
        const eModal = document.getElementById('editNoteModal');
        const eForm = document.getElementById('editNoteForm');
        const eTitle = document.getElementById('editNoteTitle');
        const eBody = document.getElementById('editNoteBody');
        const eLevel = document.getElementById('editLevelSelect');
        const eSubject = document.getElementById('editSubjectSelect');
        const eClass = document.getElementById('editClassSelect');
        const eClose = document.getElementById('btnCloseEditNote');
        const eCancel = document.getElementById('btnCancelEditNote');
        function openE(){ eModal?.classList.remove('hidden'); }
        function closeE(){ eModal?.classList.add('hidden'); }

        async function loadLevels(target, selected=null){
            if (!target) return; target.disabled=true; target.innerHTML='<option value="">Loading courses...</option>';
            try{ const data = await fetchJSON(`{{ route('learning.notes.levels') }}`);
                target.innerHTML = '<option value="">Select course...</option>' + (Array.isArray(data)?data:[]).map(l=>`<option value="${l.id}">${l.name}</option>`).join('');
                if (selected) target.value = String(selected);
            }catch{ target.innerHTML='<option value="">Failed to load courses</option>'; }
            finally{ target.disabled=false; }
        }
        async function loadClasses(target, levelId, selected=null){
            if (!target) return; target.disabled=true; target.innerHTML='<option value="">Loading classes...</option>';
            if (!levelId){ target.innerHTML='<option value="">Select course first...</option>'; return; }
            try{ const data = await fetchJSON(`{{ route('learning.notes.classes') }}?level_id=${encodeURIComponent(levelId)}`);
                target.innerHTML = '<option value="">Select class...</option>' + (Array.isArray(data)?data:[]).map(c=>`<option value="${c.id}">${c.name}</option>`).join('');
                if (selected) target.value = String(selected);
            }catch{ target.innerHTML='<option value="">Failed to load classes</option>'; }
            finally{ target.disabled=false; }
        }
        async function loadSubjects(target, classId, selected=null){
            if (!target) return; target.disabled=true; target.innerHTML='<option value="">Loading subjects...</option>';
            if (!classId){ target.innerHTML='<option value="">Select class first...</option>'; return; }
            try{ const data = await fetchJSON(`{{ route('learning.notes.subjects') }}?class_id=${encodeURIComponent(classId)}`);
                target.innerHTML = '<option value="">Select subject...</option>' + (Array.isArray(data)?data:[]).map(s=>`<option value="${s.id}">${s.name}</option>`).join('');
                if (selected) target.value = String(selected);
            }catch{ target.innerHTML='<option value="">Failed to load subjects</option>'; }
            finally{ target.disabled=false; }
        }

        document.querySelectorAll('.btnEditNote').forEach(btn => {
            btn.addEventListener('click', async ()=>{
                const id = btn.getAttribute('data-id');
                const title = btn.getAttribute('data-title') || '';
                const levelId = btn.getAttribute('data-level-id') || '';
                const subjectId = btn.getAttribute('data-subject-id') || '';
                const classId = btn.getAttribute('data-class-id') || '';
                const body = btn.getAttribute('data-body') || '';
                eTitle.value = title;
                eBody.value = body;
                await loadLevels(eLevel, levelId);
                await loadClasses(eClass, levelId || eLevel.value, classId);
                await loadSubjects(eSubject, classId || eClass.value, subjectId);
                eForm.action = `{{ url('learning/notes') }}/${id}`;
                openE();
            });
        });
        eClose?.addEventListener('click', closeE);
        eCancel?.addEventListener('click', closeE);
        eModal?.addEventListener('click', (e)=>{ if(e.target===eModal) closeE(); });

        // Confirm delete modal
        const cModal = document.getElementById('confirmModal');
        const cTitle = document.getElementById('confirmTitle');
        const cMsg = document.getElementById('confirmMessage');
        const cOk = document.getElementById('confirmOk');
        const cCancel = document.getElementById('confirmCancel');
        const cClose = document.getElementById('confirmClose');
        let pendingForm = null;
        function openC(title, message, form){ cTitle.textContent = title||'Confirm'; cMsg.textContent = message||'Are you sure?'; pendingForm=form; cModal.classList.remove('hidden'); }
        function closeC(){ cModal.classList.add('hidden'); pendingForm=null; }
        cCancel?.addEventListener('click', closeC);
        cClose?.addEventListener('click', closeC);
        cModal?.addEventListener('click', (e)=>{ if(e.target===cModal) closeC(); });
        cOk?.addEventListener('click', ()=>{ if(pendingForm){ pendingForm.submit(); closeC(); }});
        document.querySelectorAll('form.js-confirm-delete').forEach(f=>{
            f.addEventListener('submit', (e)=>{ e.preventDefault(); hideGlobalLoader(); openC('Delete Note', 'Are you sure you want to delete this note?', f); });
        });
    })();
    </script>
</x-admin-layout>
