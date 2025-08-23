<x-admin-layout>
    <div class="py-4">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Sub Sub Category</h1>
                <p class="text-sm text-gray-500">Manage third-level categories for materials.</p>
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
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Name</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Sub Category</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Year</th>
                            <th class="px-4 py-2 text-right font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse(($subsubcategories ?? []) as $ssc)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">{{ $ssc->name }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-gray-700">{{ $ssc->subcategory->name ?? '—' }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-gray-700">{{ $ssc->year ?? '—' }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            class="btnEditSSC inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50"
                                            data-id="{{ $ssc->id }}"
                                            data-name="{{ $ssc->name }}"
                                            data-subcategory-id="{{ $ssc->subcategory_id }}"
                                            data-year="{{ $ssc->year }}"
                                        >
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                            <span class="text-sm">Edit</span>
                                        </button>
                                        <form method="POST" action="{{ route('materials.subsubcategories.destroy', $ssc) }}" class="inline js-confirm-delete" data-confirm-title="Delete" data-confirm-message="Delete this sub sub category?">
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
            @if(($subsubcategories ?? null) && $subsubcategories->hasPages())
                <div class="px-2 py-3 border-t bg-white">
                    {{ $subsubcategories->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Add Sub Sub Category Modal -->
    <div id="addSSCModal" class="fixed inset-0 bg-black/30 hidden z-50">
        <div class="min-h-full w-full grid place-items-center p-4">
            <div class="bg-white rounded-lg shadow max-w-lg w-full">
                <div class="px-4 py-3 border-b flex items-center justify-between">
                    <h3 class="font-semibold">Add Sub Sub Category</h3>
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
                        <label class="block text-sm font-medium text-gray-700">Sub Category</label>
                        <select id="sscSubcategorySelect" name="subcategory_id" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="">Loading...</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Year</label>
                        <input type="number" name="year" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Optional" />
                    </div>
                    <div class="pt-2 flex items-center justify-end gap-2">
                        <button type="button" id="btnCancelAddSSC" class="px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">Save</button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Sub Sub Category Modal -->
    <div id="editSSCModal" class="fixed inset-0 bg-black/30 hidden z-50">
        <div class="min-h-full w-full grid place-items-center p-4">
            <div class="bg-white rounded-lg shadow max-w-lg w-full">
                <div class="px-4 py-3 border-b flex items-center justify-between">
                    <h3 class="font-semibold">Edit Sub Sub Category</h3>
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
                        <label class="block text-sm font-medium text-gray-700">Sub Category</label>
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
        const subSelect = document.getElementById('sscSubcategorySelect');

        async function loadSubcategories(){
            if (!subSelect) return;
            subSelect.innerHTML = '<option value="">Loading...</option>';
            try{
                const url = new URL("{{ route('materials.subcategories') }}", window.location.origin);
                // No category filter to fetch all
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!res.ok) throw new Error('Failed');
                const data = await res.json();
                const opts = ['<option value="">Select...</option>']
                    .concat((data||[]).map(s=>`<option value="${s.id}">${s.name}</option>`));
                subSelect.innerHTML = opts.join('');
            }catch(e){
                subSelect.innerHTML = '<option value="">Failed to load</option>';
            }
        }

        function open(){ modal?.classList.remove('hidden'); }
        function close(){ modal?.classList.add('hidden'); }
        openBtn?.addEventListener('click', ()=>{ loadSubcategories(); open(); });
        closeBtn?.addEventListener('click', close);
        cancelBtn?.addEventListener('click', close);
        modal?.addEventListener('click', (e)=>{ if(e.target===modal) close(); });

        // Preload on page load as well
        document.addEventListener('DOMContentLoaded', loadSubcategories);
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
        function open(title, message, form){ cTitle.textContent = title||'Confirm'; cMsg.textContent = message||'Are you sure?'; pendingForm=form; cModal.classList.remove('hidden'); }
        function close(){ cModal.classList.add('hidden'); pendingForm=null; }
        cCancel?.addEventListener('click', close);
        cClose?.addEventListener('click', close);
        cModal?.addEventListener('click', (e)=>{ if(e.target===cModal) close(); });
        cOk?.addEventListener('click', ()=>{ if(pendingForm){ pendingForm.submit(); close(); }});
        document.querySelectorAll('form.js-confirm-delete').forEach(f=>{
            f.addEventListener('submit', (e)=>{ e.preventDefault(); const t=f.getAttribute('data-confirm-title')||'Confirm'; const m=f.getAttribute('data-confirm-message')||'Are you sure?'; open(t,m,f); });
        });
    })();
    </script>
</x-admin-layout>
