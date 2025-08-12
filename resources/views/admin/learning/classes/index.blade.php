<x-admin-layout>
    <div class="py-4">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">All Classes</h1>
                <p class="text-sm text-gray-500">Manage classes and assign them to subjects.</p>
            </div>

    <!-- Assign Subjects Modal -->
    <div id="assignSubjectsModal" class="fixed inset-0 bg-black/30 hidden z-50">
        <div class="min-h-full w-full grid place-items-center p-4">
            <div class="bg-white rounded-lg shadow max-w-lg w-full">
                <div class="px-4 py-3 border-b flex items-center justify-between">
                    <h3 class="font-semibold">Assign Subjects to Class</h3>
                    <button id="btnCloseAssignSubjects" class="p-1 hover:bg-gray-100 rounded">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form id="assignSubjectsForm" method="POST" action="#" class="p-4">
                    @csrf
                    <div class="grid gap-3 max-h-80 overflow-auto pr-1">
                        @foreach(($subjects ?? []) as $sub)
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="subject_ids[]" value="{{ $sub->id }}" class="rounded border-gray-300">
                            <span>{{ $sub->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    <div class="mt-4 flex items-center justify-end gap-2">
                        <button type="button" id="btnCancelAssignSubjects" class="px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
        </div>
        <div class="border-t border-dashed mb-4"></div>

        <div class="mb-3 flex items-start justify-between gap-4">
            <form method="GET" class="flex items-center gap-2 relative" autocomplete="off">
                <div class="relative">
                    <input id="classSearchInput" type="text" name="q" value="{{ $q ?? '' }}" placeholder="Search classes..." class="w-64 border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                    <div id="classSuggestBox" class="absolute z-10 mt-1 w-full bg-white border rounded-lg shadow hidden max-h-56 overflow-auto"></div>
                </div>
                <button class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/></svg>
                    <span>Search</span>
                </button>
            </form>
            <button id="btnOpenAddClass" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm shadow">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                <span>Add Class</span>
            </button>
        </div>

        <div class="mt-4 bg-white border rounded-lg overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-700">
                    <tr class="text-sm">
                        <th class="px-4 py-3 font-medium">Class</th>
                        <th class="px-4 py-3 font-medium">Subject</th>
                        <th class="px-4 py-3 font-medium">Description</th>
                        <th class="px-4 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse(($classes ?? []) as $class)
                        <tr class="text-sm">
                            <td class="px-4 py-3 text-gray-900">{{ $class->name }}</td>
                            <td class="px-4 py-3 text-gray-700">
                                <div>{{ $class->subject->name ?? '-' }}</div>
                                @if(($class->subjects ?? collect())->isNotEmpty())
                                    <div class="mt-1 flex flex-wrap gap-1">
                                        @foreach($class->subjects as $s)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 text-gray-700 text-xs">{{ $s->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ Str::limit($class->description, 120) }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        class="btnAssignSubjects inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50"
                                        data-id="{{ $class->id }}"
                                        data-subject-ids="{{ $class->subjects->pluck('id')->implode(',') }}"
                                    >
                                        <span class="material-symbols-outlined text-[18px]">playlist_add_check</span>
                                        <span class="text-sm">Assign Subjects</span>
                                    </button>
                                    <button
                                        class="btnEditClass inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50"
                                        data-id="{{ $class->id }}"
                                        data-name="{{ $class->name }}"
                                        data-description="{{ $class->description }}"
                                        data-subject-id="{{ $class->subject_id }}"
                                    >
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                        <span class="text-sm">Edit</span>
                                    </button>
                                    <form class="js-confirm-delete" method="POST" action="{{ route('learning.classes.destroy', $class) }}" data-confirm-title="Delete Class" data-confirm-message="Are you sure you want to delete this class?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-red-200 text-red-600 hover:bg-red-50">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                            <span class="text-sm">Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500 text-sm">No classes found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ ($classes ?? null)?->links() }}</div>
    </div>

    <!-- Add Class Modal -->
    <div id="addClassModal" class="fixed inset-0 bg-black/30 hidden z-50">
        <div class="min-h-full w-full grid place-items-center p-4">
            <div class="bg-white rounded-lg shadow max-w-lg w-full">
                <div class="px-4 py-3 border-b flex items-center justify-between">
                    <h3 class="font-semibold">Add Class</h3>
                    <button id="btnCloseAddClass" class="p-1 hover:bg-gray-100 rounded">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('learning.classes.store') }}" class="p-4">
                    @csrf
                    <div class="grid gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Class Name</label>
                            <input name="name" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Subject</label>
                            <select name="subject_id" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select subject...</option>
                                @foreach(($subjects ?? []) as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" rows="3" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-end gap-2">
                        <button type="button" id="btnCancelAddClass" class="px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Class Modal -->
    <div id="editClassModal" class="fixed inset-0 bg-black/30 hidden z-50">
        <div class="min-h-full w-full grid place-items-center p-4">
            <div class="bg-white rounded-lg shadow max-w-lg w-full">
                <div class="px-4 py-3 border-b flex items-center justify-between">
                    <h3 class="font-semibold">Edit Class</h3>
                    <button id="btnCloseEditClass" class="p-1 hover:bg-gray-100 rounded">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form id="editClassForm" method="POST" action="#" class="p-4">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Class Name</label>
                            <input id="editClassName" name="name" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Subject</label>
                            <select id="editSubjectId" name="subject_id" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach(($subjects ?? []) as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="editClassDescription" name="description" rows="3" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-end gap-2">
                        <button type="button" id="btnCancelEditClass" class="px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Global Confirm Modal (reused) -->
    <div id="confirmModal" class="fixed inset-0 bg-black/30 hidden z-[60]">
        <div class="min-h-full w-full grid place-items-center p-4">
            <div class="bg-white rounded-lg shadow max-w-md w-full">
                <div class="px-4 py-3 border-b flex items-center justify-between">
                    <h3 id="confirmTitle" class="font-semibold">Confirm</h3>
                    <button id="confirmClose" class="p-1 hover:bg-gray-100 rounded">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="p-4">
                    <p id="confirmMessage" class="text-gray-700">Are you sure?</p>
                </div>
                <div class="px-4 py-3 border-t flex items-center justify-end gap-2">
                    <button id="confirmCancel" class="px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button id="confirmOk" class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function(){
        // Search suggestions
        const searchInput = document.getElementById('classSearchInput');
        const suggestBox = document.getElementById('classSuggestBox');
        let searchTimer = null;
        function hideSuggest(){ suggestBox?.classList.add('hidden'); if (suggestBox) suggestBox.innerHTML=''; }
        function showSuggest(items){
            if (!suggestBox) return;
            if (!items || items.length === 0){ hideSuggest(); return; }
            suggestBox.innerHTML = items.map(x=>`<button type="button" class="w-full text-left px-3 py-2 hover:bg-gray-50">${x}</button>`).join('');
            suggestBox.classList.remove('hidden');
            Array.from(suggestBox.querySelectorAll('button')).forEach(btn=>{
                btn.addEventListener('click', ()=>{ searchInput.value = btn.textContent.trim(); hideSuggest(); });
            });
        }
        searchInput?.addEventListener('input', ()=>{
            const q = searchInput.value.trim();
            clearTimeout(searchTimer);
            if (q.length < 1){ hideSuggest(); return; }
            searchTimer = setTimeout(async ()=>{
                try{
                    const url = new URL("{{ route('learning.classes.suggest') }}", window.location.origin);
                    url.searchParams.set('q', q);
                    const res = await fetch(url, {headers:{'X-Requested-With':'XMLHttpRequest'}});
                    const data = await res.json();
                    showSuggest(data);
                }catch(e){ hideSuggest(); }
            }, 180);
        });
        document.addEventListener('click', (e)=>{
            if (!suggestBox?.contains(e.target) && e.target !== searchInput){ hideSuggest(); }
        });

        // Modals open/close
        const addModal = document.getElementById('addClassModal');
        const editModal = document.getElementById('editClassModal');
        const btnOpenAdd = document.getElementById('btnOpenAddClass');
        const btnCloseAdd = document.getElementById('btnCloseAddClass');
        const btnCancelAdd = document.getElementById('btnCancelAddClass');
        const btnCloseEdit = document.getElementById('btnCloseEditClass');
        const btnCancelEdit = document.getElementById('btnCancelEditClass');
        function open(el){ el?.classList.remove('hidden'); }
        function close(el){ el?.classList.add('hidden'); }
        btnOpenAdd?.addEventListener('click', ()=> open(addModal));
        btnCloseAdd?.addEventListener('click', ()=> close(addModal));
        btnCancelAdd?.addEventListener('click', ()=> close(addModal));
        addModal?.addEventListener('click', (e)=>{ if(e.target===addModal) close(addModal); });
        btnCloseEdit?.addEventListener('click', ()=> close(editModal));
        btnCancelEdit?.addEventListener('click', ()=> close(editModal));
        editModal?.addEventListener('click', (e)=>{ if(e.target===editModal) close(editModal); });

        // Edit populate
        const editForm = document.getElementById('editClassForm');
        const editName = document.getElementById('editClassName');
        const editDesc = document.getElementById('editClassDescription');
        const editSubjectId = document.getElementById('editSubjectId');
        document.querySelectorAll('.btnEditClass').forEach(btn => {
            btn.addEventListener('click', ()=>{
                const id = btn.getAttribute('data-id');
                const name = btn.getAttribute('data-name');
                const description = btn.getAttribute('data-description') || '';
                const subjectId = btn.getAttribute('data-subject-id') || '';
                editName.value = name;
                editDesc.value = description;
                if (editSubjectId) editSubjectId.value = subjectId;
                editForm.action = `{{ url('learning/classes') }}/${id}`;
                open(editModal);
            });
        });

        // Assign Subjects
        const assignModal = document.getElementById('assignSubjectsModal');
        const assignForm = document.getElementById('assignSubjectsForm');
        const btnCloseAssign = document.getElementById('btnCloseAssignSubjects');
        const btnCancelAssign = document.getElementById('btnCancelAssignSubjects');
        function openAssign(){ assignModal?.classList.remove('hidden'); }
        function closeAssign(){ assignModal?.classList.add('hidden'); }
        btnCloseAssign?.addEventListener('click', closeAssign);
        btnCancelAssign?.addEventListener('click', closeAssign);
        assignModal?.addEventListener('click', (e)=>{ if(e.target===assignModal) closeAssign(); });
        document.querySelectorAll('.btnAssignSubjects').forEach(btn => {
            btn.addEventListener('click', ()=>{
                const id = btn.getAttribute('data-id');
                const selected = (btn.getAttribute('data-subject-ids')||'').split(',').filter(x=>x);
                // Reset checks
                assignForm?.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                    cb.checked = selected.includes(cb.value);
                });
                assignForm.action = `{{ url('learning/classes') }}/${id}/subjects`;
                openAssign();
            });
        });

        // Confirm delete modal logic
        const cModal = document.getElementById('confirmModal');
        const cTitle = document.getElementById('confirmTitle');
        const cMsg = document.getElementById('confirmMessage');
        const cOk = document.getElementById('confirmOk');
        const cCancel = document.getElementById('confirmCancel');
        const cClose = document.getElementById('confirmClose');
        let pendingForm = null;
        function openConfirm(title, message, form){ cTitle.textContent = title || 'Confirm'; cMsg.textContent = message || 'Are you sure?'; pendingForm = form; cModal.classList.remove('hidden'); }
        function closeConfirm(){ cModal.classList.add('hidden'); pendingForm = null; }
        cCancel?.addEventListener('click', closeConfirm);
        cClose?.addEventListener('click', closeConfirm);
        cModal?.addEventListener('click', (e)=>{ if(e.target===cModal) closeConfirm(); });
        cOk?.addEventListener('click', ()=>{ if(pendingForm){ pendingForm.submit(); closeConfirm(); } });
        document.querySelectorAll('form.js-confirm-delete').forEach(f => {
            f.addEventListener('submit', (e)=>{ e.preventDefault(); const title = f.getAttribute('data-confirm-title') || 'Confirm'; const msg = f.getAttribute('data-confirm-message') || 'Are you sure?'; openConfirm(title, msg, f); });
        });
    })();
    </script>
</x-admin-layout>
