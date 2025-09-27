        <x-admin-layout title="Courses">
    <div class="px-4 py-4">
        <div class="text-2xl font-semibold tracking-wide text-gray-800">COURSES</div>
        <div class="mt-2 border-t border-dashed"></div>

        <div class="mt-3 flex items-center justify-between">
            <form method="GET" class="w-full max-w-xs">
                <div class="relative">
                    <input type="text" name="s" value="{{ $s ?? '' }}" placeholder="Search course..." class="w-full pl-10 pr-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                    <span class="material-symbols-outlined absolute left-2 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                </div>

    <!-- Assign Material Types Modal -->
    <div id="assignMaterialTypesModal" class="fixed inset-0 bg-black/40 z-40 hidden">
        <div class="min-h-full flex items-center justify-center p-4">
            <div class="w-full max-w-xl bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 border-b">
                    <h3 class="text-lg font-semibold">Assign Material Types to <span id="amtLevelName" class="text-green-700"></span></h3>
                    <button type="button" id="btnCloseAssignMaterialTypes" class="p-1 rounded hover:bg-gray-100" aria-label="Close">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="p-4">
                    <div class="mb-3">
                        <input id="amtFilter" type="text" placeholder="Filter material types..." class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500" />
                    </div>
                    <div id="amtTypesContainer" class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-[420px] overflow-y-auto">
                        <div class="text-gray-500 text-sm">Loading types...</div>
                    </div>
                    <div class="flex items-center gap-2 pt-3">
                        <button type="button" id="btnSaveAssignMaterialTypes" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm shadow">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            <span>Save</span>
                        </button>
                        <button type="button" id="btnCancelAssignMaterialTypes" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
            </form>
            <button type="button" id="btnOpenAddLevel" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm shadow">
                <span class="material-symbols-outlined">add_circle</span>
                <span>Add Level</span>
            </button>
        </div>

        @if(session('status'))
            <div class="mt-3 p-3 bg-green-50 text-green-800 border border-green-200 rounded">{{ session('status') }}</div>
        @endif

        <div class="mt-4 bg-white border rounded-lg overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-600 text-sm">
                    <tr>
                        <th class="px-4 py-2">Icon</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                @forelse(($levels ?? []) as $level)
                    <tr>
                        <td class="px-4 py-2">
                            @php $icon = (string)($level->icon ?? ''); @endphp
                            @if(\Illuminate\Support\Str::contains($icon, '<svg'))
                                {!! $icon !!}
                            @else
                                <svg class="w-6 h-6 text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                  <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                                  <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                            @endif
                        </td>
                        <td class="px-4 py-2 font-medium text-gray-800">{{ $level->name }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ Str::limit($level->description, 80) }}</td>
                        <td class="px-4 py-2">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded border text-gray-700 border-gray-300 hover:bg-gray-50 text-xs btnViewClasses" data-id="{{ $level->id }}">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <span>View</span>
                                </button>
                                <button type="button"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded border text-sky-700 border-sky-300 hover:bg-sky-50 text-xs btnAssignLevelClasses"
                                    data-id="{{ $level->id }}"
                                    data-name="{{ $level->name }}">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7h-9"/><path d="M14 17H5"/><path d="M20 17h-3"/><path d="M5 7h3"/></svg>
                                    <span>Assign</span>
                                </button>
                                <button type="button"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded border text-indigo-700 border-indigo-300 hover:bg-indigo-50 text-xs btnEditLevel"
                                    data-id="{{ $level->id }}"
                                    data-name="{{ $level->name }}"
                                    data-description="{{ $level->description }}"
                                    data-icon="{{ $level->icon }}">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 1 1 3 3L7 19l-4 1 1-4 12.5-12.5Z"/></svg>
                                    <span>Edit</span>
                                </button>
                                <button type="button"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded border text-green-700 border-green-300 hover:bg-green-50 text-xs btnAssignMaterialTypes"
                                    data-id="{{ $level->id }}"
                                    data-name="{{ $level->name }}">
                                    <span class="material-symbols-outlined text-[16px]">assignment_add</span>
                                    <span>Assign Material Types</span>
                                </button>
                                <form method="POST" action="{{ route('learning.levels.destroy', $level) }}" class="js-confirm-delete" data-confirm-title="Delete Level" data-confirm-message="Are you sure you want to delete this level? This cannot be undone.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded border text-red-700 border-red-300 hover:bg-red-50 text-xs">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                        <span>Delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">No levels found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ ($levels ?? null)?->links() }}</div>
    </div>

    <!-- Add Level Modal -->
    <div id="addLevelModal" class="fixed inset-0 bg-black/40 z-40 hidden">
        <div class="min-h-full flex items-center justify-center p-4">
            <div class="w-full max-w-xl bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 border-b">
                    <h3 class="text-lg font-semibold">Add Level</h3>
                    <button type="button" id="btnCloseAddLevel" class="p-1 rounded hover:bg-gray-100" aria-label="Close">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="p-4">
                    <form method="POST" action="{{ route('learning.levels.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" id="addLevelName" name="name" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="addLevelDescription" name="description" rows="3" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Icon (paste SVG markup)</label>
                            <div class="flex items-start gap-3">
                                <textarea name="icon" id="addIconInput" placeholder="<svg ...>...</svg>" rows="3" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                <div id="addIconPreviewContainer" class="mt-1 w-10 h-10 flex items-center justify-center text-gray-700"></div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">Leave blank to use default eye icon. You can paste any Tailwind/Heroicons SVG.</div>
                        </div>
                        <div class="flex items-center gap-2 pt-2">
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm shadow">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                <span>Save</span>
                            </button>
                            <button type="button" id="btnCancelAddLevel" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Classes To Level Modal -->
    <div id="assignLevelClassesModal" class="fixed inset-0 bg-black/40 z-40 hidden">
        <div class="min-h-full flex items-center justify-center p-4">
            <div class="w-full max-w-xl bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 border-b">
                    <h3 class="text-lg font-semibold">Assign Classes to <span id="alcLevelName" class="text-sky-700"></span></h3>
                    <button type="button" id="btnCloseAssignLevelClasses" class="p-1 rounded hover:bg-gray-100" aria-label="Close">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="p-4">
                    <form id="assignLevelClassesForm" method="POST" class="space-y-4">
                        @csrf
                        <div id="alcClassesContainer" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="text-gray-500 text-sm">Loading classes...</div>
                        </div>
                        <div class="flex items-center gap-2 pt-2">
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-sky-600 hover:bg-sky-700 text-white text-sm shadow">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                <span>Save</span>
                            </button>
                            <button type="button" id="btnCancelAssignLevelClasses" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Confirm Modal -->
    <div id="confirmModal" class="fixed inset-0 bg-black/40 z-50 hidden">
        <div class="min-h-full flex items-center justify-center p-4">
            <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-4 py-3 border-b flex items-center justify-between">
                    <h3 id="confirmTitle" class="text-lg font-semibold">Confirm</h3>
                    <button type="button" id="confirmClose" class="p-1 rounded hover:bg-gray-100" aria-label="Close">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="p-4">
                    <p id="confirmMessage" class="text-gray-700">Are you sure?</p>
                    <div class="mt-4 flex items-center justify-end gap-2">
                        <button type="button" id="confirmCancel" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm">Cancel</button>
                        <button type="button" id="confirmOk" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                            <span>Delete</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Classes Modal -->
    <div id="viewClassesModal" class="fixed inset-0 bg-black/40 z-40 hidden">
        <div class="min-h-full flex items-center justify-center p-4">
            <div class="w-full max-w-xl bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 border-b">
                    <h3 class="text-lg font-semibold"><span class="align-middle">Assigned Classes</span></h3>
                    <button type="button" id="btnCloseViewClasses" class="p-1 rounded hover:bg-gray-100" aria-label="Close">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="p-4">
                    <div id="viewClassesHeader" class="text-sm text-gray-600 mb-2"></div>
                    <ul id="classesList" class="divide-y rounded border"></ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Level Modal -->
    <div id="editLevelModal" class="fixed inset-0 bg-black/40 z-40 hidden">
        <div class="min-h-full flex items-center justify-center p-4">
            <div class="w-full max-w-xl bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 border-b">
                    <h3 class="text-lg font-semibold">Edit Level</h3>
                    <button type="button" id="btnCloseEditLevel" class="p-1 rounded hover:bg-gray-100" aria-label="Close">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="p-4">
                    <form id="editLevelForm" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="_target" id="editTarget" value="" />
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="editName" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="editDescription" rows="3" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Icon (paste SVG markup)</label>
                            <div class="flex items-start gap-3">
                                <textarea name="icon" id="editIconInput" placeholder="<svg ...>...</svg>" rows="3" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                <div id="editIconPreviewContainer" class="mt-1 w-10 h-10 flex items-center justify-center text-gray-700"></div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 pt-2">
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm shadow">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                <span>Update</span>
                            </button>
                            <button type="button" id="btnCancelEditLevel" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function(){
        const addModal = document.getElementById('addLevelModal');
        const editModal = document.getElementById('editLevelModal');
        const btnOpenAdd = document.getElementById('btnOpenAddLevel');
        const btnCloseAdd = document.getElementById('btnCloseAddLevel');
        const btnCancelAdd = document.getElementById('btnCancelAddLevel');
        const btnCloseEdit = document.getElementById('btnCloseEditLevel');
        const btnCancelEdit = document.getElementById('btnCancelEditLevel');
        const addIconInput = document.getElementById('addIconInput');
        const addIconPreviewC = document.getElementById('addIconPreviewContainer');
        const editIconInput = document.getElementById('editIconInput');
        const editIconPreviewC = document.getElementById('editIconPreviewContainer');
        const editForm = document.getElementById('editLevelForm');
        const editTarget = document.getElementById('editTarget');
        const editName = document.getElementById('editName');
        const editDesc = document.getElementById('editDescription');
        const addName = document.getElementById('addLevelName');
        const addDesc = document.getElementById('addLevelDescription');

        function open(el){ el.classList.remove('hidden'); }
        function close(el){ el.classList.add('hidden'); }

        btnOpenAdd?.addEventListener('click', ()=> open(addModal));
        btnCloseAdd?.addEventListener('click', ()=> close(addModal));
        btnCancelAdd?.addEventListener('click', ()=> close(addModal));
        addModal?.addEventListener('click', (e)=>{ if(e.target===addModal) close(addModal); });

        btnCloseEdit?.addEventListener('click', ()=> close(editModal));
        btnCancelEdit?.addEventListener('click', ()=> close(editModal));
        editModal?.addEventListener('click', (e)=>{ if(e.target===editModal) close(editModal); });

        // icon preview handlers (supports inline SVG; fallback heroicon eye)
        const defaultEye = '<svg class="w-6 h-6 text-gray-700" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z"/><circle cx="12" cy="12" r="3"/></svg>';
        function renderIconPreview(val, container){
            const v = (val || '').trim();
            if (v.includes('<svg')) {
                container.innerHTML = v;
            } else {
                container.innerHTML = defaultEye;
            }
        }
        addIconInput?.addEventListener('input', ()=> renderIconPreview(addIconInput.value, addIconPreviewC));
        editIconInput?.addEventListener('input', ()=> renderIconPreview(editIconInput.value, editIconPreviewC));

        // bind edit buttons
        document.querySelectorAll('.btnEditLevel').forEach(btn => {
            btn.addEventListener('click', ()=>{
                const id = btn.getAttribute('data-id');
                const name = btn.getAttribute('data-name');
                const description = btn.getAttribute('data-description') || '';
                const icon = btn.getAttribute('data-icon') || '';
                editName.value = name;
                editDesc.value = description;
                editIconInput.value = icon;
                renderIconPreview(icon, editIconPreviewC);
                editForm.action = `{{ url('learning/levels') }}/${id}`;
                open(editModal);
            });
        });

        // initial default preview
        if (addIconPreviewC) renderIconPreview(addIconInput?.value || '', addIconPreviewC);

        // View classes logic
        const viewModal = document.getElementById('viewClassesModal');
        const btnCloseView = document.getElementById('btnCloseViewClasses');
        const classesHeader = document.getElementById('viewClassesHeader');
        const classesList = document.getElementById('classesList');
        function openView(){ viewModal.classList.remove('hidden'); }
        function closeView(){ viewModal.classList.add('hidden'); classesHeader.textContent=''; classesList.innerHTML=''; }
        btnCloseView?.addEventListener('click', closeView);
        viewModal?.addEventListener('click', (e)=>{ if(e.target===viewModal) closeView(); });

        async function fetchJSON(url){ const r = await fetch(url, { headers: { 'Accept':'application/json' } }); return r.json(); }
        document.querySelectorAll('.btnViewClasses').forEach(btn => {
            btn.addEventListener('click', async ()=>{
                const id = btn.getAttribute('data-id');
                const url = `{{ url('learning/levels') }}/${id}/classes-json`;
                try {
                    const data = await fetchJSON(url);
                    classesHeader.textContent = `${data.level.name} • ${data.classes.length} classes`;
                    if (data.classes.length === 0) {
                        classesList.innerHTML = '<li class="px-3 py-3 text-gray-500">No classes assigned.</li>';
                    } else {
                        classesList.innerHTML = data.classes.map(c => `<li class=\"px-3 py-2 flex items-center gap-2\"><svg class=\"w-4 h-4 text-indigo-600\" xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"2\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M4 6h16M4 12h16M4 18h16\" /></svg><span>${c.name}</span></li>`).join('');
                    }
                    openView();
                } catch (e) {
                    alert('Failed to load classes.');
                }
            });
        });

        // Assign Level -> Classes modal logic
        const alcModal = document.getElementById('assignLevelClassesModal');
        const alcName = document.getElementById('alcLevelName');
        const alcForm = document.getElementById('assignLevelClassesForm');
        const alcContainer = document.getElementById('alcClassesContainer');
        const btnCloseALC = document.getElementById('btnCloseAssignLevelClasses');
        const btnCancelALC = document.getElementById('btnCancelAssignLevelClasses');
        function alcOpen(){ alcModal.classList.remove('hidden'); }
        function alcClose(){ alcModal.classList.add('hidden'); }
        function alcReset(){ alcContainer.innerHTML = '<div class="text-gray-500 text-sm">Loading classes...</div>'; }
        function buildCB(id, name, checked){ const c = checked ? 'checked' : ''; return `<label class="flex items-center gap-2 border rounded-lg px-3 py-2"><input type="checkbox" name="class_ids[]" value="${id}" class="rounded" ${c}/><span class="text-sm text-gray-800">${name}</span></label>`; }
        async function openAssignLevelClasses(levelId, levelName){
            alcName.textContent = levelName || '';
            alcReset();
            alcForm.action = `{{ url('learning/levels') }}/${levelId}/classes`;
            alcOpen();
            try{
                const data = await fetchJSON(`{{ url('learning/levels') }}/${levelId}/assign-classes-json`);
                const selected = new Set((data.selected||[]).map(Number));
                const list = (data.classes||[]);
                if (!list.length){ alcContainer.innerHTML = '<div class="text-gray-500 text-sm">No classes found.</div>'; return; }
                alcContainer.innerHTML = list.map(x => buildCB(x.id, x.name, selected.has(Number(x.id)))).join('');
            }catch(e){ alcContainer.innerHTML = '<div class="text-red-600 text-sm">Failed to load classes.</div>'; }
        }
        document.querySelectorAll('.btnAssignLevelClasses').forEach(btn => {
            btn.addEventListener('click', ()=>{
                const id = btn.getAttribute('data-id');
                const name = btn.getAttribute('data-name');
                openAssignLevelClasses(id, name);
            });
        });
        btnCloseALC?.addEventListener('click', alcClose);
        btnCancelALC?.addEventListener('click', alcClose);
        alcModal?.addEventListener('click', (e)=>{ if(e.target===alcModal) alcClose(); });

        // Assign Material Types (bridge to Material Category by Level name)
        const amtModal = document.getElementById('assignMaterialTypesModal');
        const amtName = document.getElementById('amtLevelName');
        const amtContainer = document.getElementById('amtTypesContainer');
        const amtFilter = document.getElementById('amtFilter');
        const btnCloseAMT = document.getElementById('btnCloseAssignMaterialTypes');
        const btnCancelAMT = document.getElementById('btnCancelAssignMaterialTypes');
        const btnSaveAMT = document.getElementById('btnSaveAssignMaterialTypes');
        let currentAMTLevelId = null;
        let amtCache = [];
        function amtOpen(){ amtModal.classList.remove('hidden'); }
        function amtClose(){ amtModal.classList.add('hidden'); }
        function amtRender(filter=''){
            const f = (filter||'').trim().toLowerCase();
            const list = amtCache.filter(t => !f || (t.name||'').toLowerCase().includes(f));
            if (!list.length){ amtContainer.innerHTML = '<div class="text-gray-500 text-sm">No types found.</div>'; return; }
            amtContainer.innerHTML = list.map(t => `<label class="flex items-center gap-2 border rounded-lg px-3 py-2"><input type="checkbox" value="${t.id}" ${t.assigned ? 'checked' : ''} class="rounded"/><span class="text-sm text-gray-800">${t.name}</span></label>`).join('');
        }
        function getCheckedTypeIds(){ return Array.from(amtContainer.querySelectorAll('input[type="checkbox"]:checked')).map(el => parseInt(el.value)); }
        amtFilter?.addEventListener('input', ()=> amtRender(amtFilter.value));
        btnCloseAMT?.addEventListener('click', amtClose);
        btnCancelAMT?.addEventListener('click', amtClose);
        amtModal?.addEventListener('click', (e)=>{ if(e.target===amtModal) amtClose(); });

        document.querySelectorAll('.btnAssignMaterialTypes').forEach(btn => {
            btn.addEventListener('click', async ()=>{
                currentAMTLevelId = parseInt(btn.getAttribute('data-id'));
                amtName.textContent = btn.getAttribute('data-name') || '';
                amtContainer.innerHTML = '<div class="text-gray-500 text-sm">Loading types...</div>';
                amtOpen();
                try{
                    const url = `{{ url('learning/levels') }}/${currentAMTLevelId}/material-types-json`;
                    const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
                    if (!res.ok) throw new Error('HTTP '+res.status);
                    const data = await res.json();
                    const map = new Map();
                    for (const t of (Array.isArray(data)?data:[])){
                        const k = String(t.name||'').toLowerCase();
                        if (!map.has(k)) map.set(k, { id: t.id, name: t.name, assigned: !!t.assigned });
                        else { const cur = map.get(k); cur.assigned = cur.assigned || !!t.assigned; }
                    }
                    amtCache = Array.from(map.values()).sort((a,b)=> String(a.name||'').localeCompare(String(b.name||'')));
                    amtRender(amtFilter.value);
                }catch(e){ amtContainer.innerHTML = '<div class="text-red-600 text-sm">Failed to load types.</div>'; }
            });
        });

        btnSaveAMT?.addEventListener('click', async ()=>{
            if (!currentAMTLevelId) return;
            const ids = getCheckedTypeIds();
            const original = btnSaveAMT.innerHTML; btnSaveAMT.disabled = true; btnSaveAMT.innerHTML = '<span class="material-symbols-outlined animate-spin mr-2">progress_activity</span>Saving...';
            try{
                const url = `{{ url('learning/levels') }}/${currentAMTLevelId}/material-types-sync`;
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ type_ids: ids })
                });
                const data = await res.json();
                if (data && data.success){ amtClose(); }
                else alert('Failed to save.');
            }catch(e){ alert('Error saving.'); }
            finally { btnSaveAMT.disabled = false; btnSaveAMT.innerHTML = original; }
        });

        // Confirm delete modal logic
        const cModal = document.getElementById('confirmModal');
        const cTitle = document.getElementById('confirmTitle');
        const cMsg = document.getElementById('confirmMessage');
        const cOk = document.getElementById('confirmOk');
        const cCancel = document.getElementById('confirmCancel');
        const cClose = document.getElementById('confirmClose');
        let pendingForm = null;

        function openConfirm(title, message, form){
            cTitle.textContent = title || 'Confirm';
            cMsg.textContent = message || 'Are you sure?';
            pendingForm = form;
            cModal.classList.remove('hidden');
        }
        function closeConfirm(){ cModal.classList.add('hidden'); pendingForm = null; }

        cCancel?.addEventListener('click', closeConfirm);
        cClose?.addEventListener('click', closeConfirm);
        cModal?.addEventListener('click', (e)=>{ if(e.target===cModal) closeConfirm(); });
        cOk?.addEventListener('click', ()=>{ if(pendingForm){ pendingForm.submit(); closeConfirm(); } });

        document.querySelectorAll('form.js-confirm-delete').forEach(f => {
            f.addEventListener('submit', (e)=>{
                e.preventDefault();
                const title = f.getAttribute('data-confirm-title') || 'Confirm';
                const msg = f.getAttribute('data-confirm-message') || 'Are you sure?';
                try{ const gl=document.getElementById('globalPageLoader'); if(gl){ gl.classList.add('hidden'); gl.classList.remove('flex'); } }catch{}
                openConfirm(title, msg, f);
            });
        });
    })();
    </script>
</x-admin-layout>
