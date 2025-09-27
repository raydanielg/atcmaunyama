<x-admin-layout>
    <div class="py-4">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Material Sub Type</h1>
                <p class="text-sm text-gray-500">Manage Material Sub Types (third level under Material Type).</p>
            </div>
            <button id="btnOpenAddSSC" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm shadow">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>Add</span>
            </button>
        </div>
        <div class="border-t border-dashed mb-4"></div>

        <div class="mb-3 flex items-start justify-between gap-4">
            <form method="GET" class="flex items-center gap-2 relative w-full max-w-md" autocomplete="off">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.414-1.414l3.387 3.387a1 1 0 01-1.414 1.414l-3.387-3.387zM14 8a6 6 0 11-12 0 6 6 0 0112 0z" clip-rule="evenodd"/></svg>
                    </span>
                    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Search..." class="w-full rounded-lg border border-gray-300 pl-9 pr-3 py-1.5 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500" />
                </div>
                <button class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm">Filter</button>
            </form>
        </div>

        <div class="overflow-hidden rounded-lg border bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Sub Type Name</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Assigned Types</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Assigned Levels</th>
                            <th class="px-4 py-2 text-right font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse(($groupedSubjects ?? []) as $row)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">{{ $row['name'] }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    @if(empty($row['types']))
                                        <span class="text-gray-400">None</span>
                                    @else
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($row['types'] as $t)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200 text-xs">
                                                    <span class="material-symbols-outlined text-[14px]">bookmark</span>
                                                    {{ $t }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if(empty($row['levels']))
                                        <span class="text-gray-400">None</span>
                                    @else
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($row['levels'] as $l)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-gray-50 text-gray-700 border border-gray-200 text-xs">
                                                    <span class="material-symbols-outlined text-[14px]">layers</span>
                                                    {{ $l }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <button 
                                            class="btnEditByName inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50"
                                            data-name="{{ $row['name'] }}"
                                        >
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                            <span class="text-sm">Edit</span>
                                        </button>
                                        <form method="POST" action="{{ route('materials.subsubcategories.destroy_by_name', ['name' => $row['name']]) }}" class="inline js-confirm-delete" data-confirm-title="Delete Sub Type" data-confirm-message="Delete all entries for '{{ $row['name'] }}'?">
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
                                <td class="px-4 py-3 text-gray-400" colspan="4">No records yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(($namesPage ?? null) && $namesPage->hasPages())
                <div class="px-2 py-3 border-t bg-white">
                    {{ $namesPage->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Sub Type (by Name) Modal -->
    <div id="editByNameModal" class="fixed inset-0 bg-black/30 hidden z-50">
        <div class="min-h-full w-full grid place-items-center p-4">
            <div class="bg-white rounded-lg shadow max-w-md w-full">
                <div class="px-4 py-3 border-b flex items-center justify-between">
                    <h3 class="font-semibold">Edit Sub Type Name</h3>
                    <button id="btnCloseEditByName" class="p-1 hover:bg-gray-100 rounded" title="Close">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form id="editByNameForm" method="POST" action="#" class="p-4 space-y-3">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-700">New Name</label>
                        <input id="newSubjectNameInput" type="text" name="new_name" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required />
                    </div>
                    <div class="pt-2 flex items-center justify-end gap-2">
                        <button type="button" id="btnCancelEditByName" class="px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Sub Type Details Modal -->
    <div id="viewSSCModal" class="fixed inset-0 bg-black/30 hidden z-50">
        <div class="min-h-full w-full grid place-items-center p-4">
            <div class="bg-white rounded-lg shadow max-w-md w-full">
                <div class="px-4 py-3 border-b flex items-center justify-between">
                    <h3 class="font-semibold">Sub Type Details</h3>
                    <button id="btnCloseViewSSC" class="p-1 hover:bg-gray-100 rounded" title="Close">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="p-4 space-y-3">
                    <div>
                        <div class="text-xs text-gray-500">Sub Type Name</div>
                        <div id="viewSSCName" class="text-gray-900 font-medium">-</div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-xs text-gray-500">Assigned Type</div>
                            <div id="viewSSCType" class="text-gray-900">-</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Assigned Level</div>
                            <div id="viewSSCLevel" class="text-gray-900">-</div>
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Year</div>
                        <div id="viewSSCYear" class="text-gray-900">-</div>
                    </div>
                </div>
                <div class="px-4 py-3 border-t flex items-center justify-end">
                    <button type="button" id="btnCloseViewSSC2" class="px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Material Sub Type Modal -->
    <div id="addSSCModal" class="fixed inset-0 bg-black/30 hidden z-50">
        <div class="min-h-full w-full grid place-items-center p-4">
            <div class="bg-white rounded-lg shadow max-w-lg w-full">
                <div class="px-4 py-3 border-b flex items-center justify-between">
                    <h3 class="font-semibold">Add Material Sub Type</h3>
                    <button id="btnCloseAddSSC" class="p-1 hover:bg-gray-100 rounded" title="Close">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('materials.subsubcategories.store') }}" class="p-4 space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Material Type</label>
                        <select id="addSSCSubcategorySelect" name="subcategory_id" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="">Loading...</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Pick the Material Type that this Sub Type belongs to.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pick from Learning Subjects (optional)</label>
                        <div class="relative mt-1">
                            <input id="learnSubjInput" type="text" placeholder="Type to search Learning subjects..." class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                            <div id="learnSubjSuggestBox" class="absolute z-10 mt-1 w-full bg-white border rounded-lg shadow hidden max-h-56 overflow-auto"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Click a suggestion to auto-fill the Name above.</p>
                    </div>
                    <div class="pt-2 flex items-center justify-end gap-2">
                        <button type="button" id="btnCancelAddSSC" class="px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">Save</button>
                    </div>
                
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Material Sub Type Modal (single record, not grouped) -->
    <div id="editSSCModal" class="fixed inset-0 bg-black/30 hidden z-50">
        <div class="min-h-full w-full grid place-items-center p-4">
            <div class="bg-white rounded-lg shadow max-w-lg w-full">
                <div class="px-4 py-3 border-b flex items-center justify-between">
                    <h3 class="font-semibold">Edit Material Sub Type</h3>
                    <button id="btnCloseEditSSC" class="p-1 hover:bg-gray-100 rounded" title="Close">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form id="editSSCForm" method="POST" action="#" class="p-4 space-y-3">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input id="editSSCName" type="text" name="name" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Material Type</label>
                        <select id="editSSCSubcategorySelect" name="subcategory_id" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="">Loading...</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Year</label>
                        <input id="editSSCYear" type="number" name="year" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Optional" />
                    </div>
                    <div class="pt-2 flex items-center justify-end gap-2">
                        <button type="button" id="btnCancelEditSSC" class="px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">Update</button>
                    </div>
                </form>
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

    <script>
    (function(){
        const modal = document.getElementById('addSSCModal');
        const openBtn = document.getElementById('btnOpenAddSSC');
        const closeBtn = document.getElementById('btnCloseAddSSC');
        const cancelBtn = document.getElementById('btnCancelAddSSC');
        const nameInput = modal?.querySelector('input[name="name"]');
        const addSubSelect = document.getElementById('addSSCSubcategorySelect');
        const learnInput = document.getElementById('learnSubjInput');
        const learnBox = document.getElementById('learnSubjSuggestBox');

        function open(){ modal?.classList.remove('hidden'); }
        function close(){ modal?.classList.add('hidden'); hideLs(); }
        openBtn?.addEventListener('click', async () => {
            open();
            // Load Material Types for Add modal
            if (addSubSelect) {
                addSubSelect.innerHTML = '<option value="">Loading...</option>';
                try{
                    const url = new URL("{{ route('materials.subcategories') }}", window.location.origin);
                    const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const data = await res.json();
                    const opts = ['<option value="">Select...</option>'].concat((data||[]).map(s=>`<option value="${s.id}">${s.name}</option>`));
                    addSubSelect.innerHTML = opts.join('');
                }catch{ addSubSelect.innerHTML = '<option value="">Failed to load</option>'; }
            }
        });
        closeBtn?.addEventListener('click', close);
        cancelBtn?.addEventListener('click', close);
        modal?.addEventListener('click', (e)=>{ if(e.target===modal) close(); });

        // Learning subjects autocomplete (optional)
        let lsTimer = null;
        function hideLs(){ if(learnBox){ learnBox.classList.add('hidden'); learnBox.innerHTML = ''; } }
        function showLs(items){
            if (!learnBox) return;
            if (!items || items.length === 0){ hideLs(); return; }
            learnBox.innerHTML = items.map(x=>`<button type="button" class="w-full text-left px-3 py-2 hover:bg-gray-50 text-sm">${x}</button>`).join('');
            learnBox.classList.remove('hidden');
            Array.from(learnBox.querySelectorAll('button')).forEach(btn=>{
                btn.addEventListener('click', ()=>{
                    if (nameInput) nameInput.value = btn.textContent.trim();
                    hideLs();
                });
            });
        }
        learnInput?.addEventListener('input', ()=>{
            clearTimeout(lsTimer);
            lsTimer = setTimeout(async ()=>{
                const q = (learnInput.value||'').trim();
                if (!q){ hideLs(); return; }
                try{
                    const url = new URL("{{ route('learning.subjects.suggest') }}", window.location.origin);
                    url.searchParams.set('q', q);
                    const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    if (!res.ok) throw new Error('failed');
                    const data = await res.json();
                    showLs(Array.isArray(data) ? data : []);
                }catch(e){ hideLs(); }
            }, 250);
        });
        document.addEventListener('click', (e)=>{ if (!learnBox?.contains(e.target) && e.target !== learnInput) hideLs(); });
    })();
    </script>

    <script>
    (function(){
        // Edit-by-Name modal logic (bulk rename across all entries with same name)
        const m = document.getElementById('editByNameModal');
        const f = document.getElementById('editByNameForm');
        const input = document.getElementById('newSubjectNameInput');
        const btnClose = document.getElementById('btnCloseEditByName');
        const btnCancel = document.getElementById('btnCancelEditByName');
        function open(){ m?.classList.remove('hidden'); }
        function close(){ m?.classList.add('hidden'); }
        document.querySelectorAll('.btnEditByName').forEach(btn => {
            btn.addEventListener('click', ()=>{
                const currentName = btn.getAttribute('data-name') || '';
                if (input) { input.value = currentName; input.focus(); input.select?.(); }
                // Point form to update_by_name route
                const base = `{{ url('materials/subsubcategories/by-name') }}`;
                if (f) f.action = `${base}/${encodeURIComponent(currentName)}`;
                open();
            });
        });
        btnClose?.addEventListener('click', close);
        btnCancel?.addEventListener('click', close);
        m?.addEventListener('click', (e)=>{ if(e.target===m) close(); });
    })();
    </script>

    <script>
    (function(){
        // View modal logic
        const vModal = document.getElementById('viewSSCModal');
        const vName = document.getElementById('viewSSCName');
        const vType = document.getElementById('viewSSCType');
        const vLevel = document.getElementById('viewSSCLevel');
        const vYear = document.getElementById('viewSSCYear');
        const vClose1 = document.getElementById('btnCloseViewSSC');
        const vClose2 = document.getElementById('btnCloseViewSSC2');
        function open(){ vModal?.classList.remove('hidden'); }
        function close(){ vModal?.classList.add('hidden'); }
        document.querySelectorAll('.btnViewSSC').forEach(btn=>{
            btn.addEventListener('click', ()=>{
                vName.textContent = btn.getAttribute('data-name') || '-';
                vType.textContent = btn.getAttribute('data-type') || '-';
                vLevel.textContent = btn.getAttribute('data-level') || '-';
                vYear.textContent = btn.getAttribute('data-year') || '-';
                open();
            });
        });
        vClose1?.addEventListener('click', close);
        vClose2?.addEventListener('click', close);
        vModal?.addEventListener('click', (e)=>{ if(e.target===vModal) close(); });
    })();
    </script>
    <script>
    (function(){
        // Edit modal logic
        const eModal = document.getElementById('editSSCModal');
        const eForm = document.getElementById('editSSCForm');
        const eName = document.getElementById('editSSCName');
        const eYear = document.getElementById('editSSCYear');
        const eSubSelect = document.getElementById('editSSCSubcategorySelect');
        const btnClose = document.getElementById('btnCloseEditSSC');
        const btnCancel = document.getElementById('btnCancelEditSSC');

        function open(){ eModal?.classList.remove('hidden'); }
        function close(){ eModal?.classList.add('hidden'); }

        async function loadSubsAndSelect(selectedId){
            if (!eSubSelect) return;
            eSubSelect.innerHTML = '<option value="">Loading...</option>';
            try{
                const url = new URL("{{ route('materials.subcategories') }}", window.location.origin);
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const data = await res.json();
                const opts = ['<option value="">Select...</option>'].concat((data||[]).map(s=>`<option value="${s.id}">${s.name}</option>`));
                eSubSelect.innerHTML = opts.join('');
                if (selectedId) eSubSelect.value = String(selectedId);
            }catch{ eSubSelect.innerHTML = '<option value="">Failed to load</option>'; }
        }

        document.querySelectorAll('.btnEditSSC').forEach(btn => {
            btn.addEventListener('click', async ()=>{
                const id = btn.getAttribute('data-id');
                const name = btn.getAttribute('data-name') || '';
                const subId = btn.getAttribute('data-subcategory-id') || '';
                const year = btn.getAttribute('data-year') || '';
                eName.value = name; eYear.value = year;
                await loadSubsAndSelect(subId);
                eForm.action = `{{ url('materials/subsubcategories') }}/${id}`;
                open();
            });
        });

        btnClose?.addEventListener('click', close);
        btnCancel?.addEventListener('click', close);
        eModal?.addEventListener('click', (e)=>{ if(e.target===eModal) close(); });
    })();
    </script>

    <script>
    (function(){
        // Simple shared confirm dialog
        const cModal = document.getElementById('confirmModal');
        const cTitle = document.getElementById('confirmTitle');
        const cMsg = document.getElementById('confirmMessage');
        const cOk = document.getElementById('confirmOk');
        const cCancel = document.getElementById('confirmCancel');
        const cClose = document.getElementById('confirmClose');
        let pendingForm = null;
        function hideGlobalLoader(){
            try{
                const gl = document.getElementById('globalPageLoader');
                if (gl){ gl.classList.add('hidden'); gl.classList.remove('flex'); }
            }catch{}
        }
        function open(title, message, form){ cTitle.textContent = title||'Confirm'; cMsg.textContent = message||'Are you sure?'; pendingForm=form; cModal.classList.remove('hidden'); }
        function close(){ cModal.classList.add('hidden'); pendingForm=null; }
        cCancel?.addEventListener('click', close);
        cClose?.addEventListener('click', close);
        cModal?.addEventListener('click', (e)=>{ if(e.target===cModal) close(); });
        cOk?.addEventListener('click', ()=>{ if(pendingForm){ pendingForm.submit(); close(); }});
        document.querySelectorAll('form.js-confirm-delete').forEach(f=>{
            f.addEventListener('submit', (e)=>{ e.preventDefault(); hideGlobalLoader(); const t=f.getAttribute('data-confirm-title')||'Confirm'; const m=f.getAttribute('data-confirm-message')||'Are you sure?'; open(t,m,f); });
        });
    })();
    </script>
</x-admin-layout>
