<x-admin-layout title="All Subjects">
    <!-- Material Symbols font for Google icon preview -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <div class="py-4">
        <div class="flex items-center justify-between mb-3">
            <div>
                <div class="text-xs uppercase tracking-wide text-gray-500">Learning</div>
                <h1 class="text-2xl font-bold text-gray-900">All Subjects</h1>
            </div>

    <!-- View Subject Modal -->
    <div id="viewSubjectModal" class="fixed inset-0 bg-black/40 z-40 hidden">
        <div class="min-h-full flex items-center justify-center p-4">
            <div class="w-full max-w-xl bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 border-b">
                    <h3 class="text-lg font-semibold">View Subject</h3>
                    <button type="button" id="btnCloseViewSubject" class="p-1 rounded hover:bg-gray-100" aria-label="Close">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="p-4 space-y-3">
                    <div>
                        <div class="text-xs text-gray-500">Name</div>
                        <div id="viewSubjName" class="text-gray-900 font-medium">-</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Description</div>
                        <div id="viewSubjDesc" class="text-gray-800 text-sm">-</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Assigned Classes</div>
                        <ul id="viewSubjClasses" class="list-disc list-inside text-sm text-gray-800"></ul>
                    </div>
                </div>
                <div class="px-4 py-3 border-t flex items-center justify-end">
                    <button type="button" id="btnCloseViewSubject2" class="px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Close</button>
                </div>
            </div>
        </div>
    </div>
        </div>
        <div class="border-t border-dashed mb-4"></div>

        <div class="mb-3 flex items-start justify-between gap-4">
            <form method="GET" class="flex items-center gap-2 relative" autocomplete="off">
                <div class="relative">
                    <input id="subjectSearchInput" type="text" name="q" value="{{ $q ?? '' }}" placeholder="Search subjects..." class="w-64 border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                    <div id="subjectSuggestBox" class="absolute z-10 mt-1 w-full bg-white border rounded-lg shadow hidden max-h-56 overflow-auto"></div>
                </div>
                <button class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/></svg>
                    <span>Search</span>
                </button>
            </form>
            <button id="btnOpenAddSubject" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm shadow">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                <span>Add Subject</span>
            </button>
        </div>

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
                @forelse(($subjects ?? []) as $subject)
                    <tr>
                        <td class="px-4 py-2">
                            @php $icon = (string)($subject->icon ?? ''); @endphp
                            @if(\Illuminate\Support\Str::contains($icon, '<svg'))
                                {!! $icon !!}
                            @elseif(!empty($icon))
                                <span class="material-symbols-rounded align-middle text-2xl text-gray-700">{{ $icon }}</span>
                            @else
                                <svg class="w-6 h-6 text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                  <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                                  <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                            @endif
                        </td>
                        <td class="px-4 py-2 font-medium text-gray-800">{{ $subject->name }}</td>
                        <td class="px-4 py-2 text-gray-600">
                            <div>{{ \Illuminate\Support\Str::limit($subject->description, 80) }}</div>
                            @php $cls = ($subject->classes ?? collect()); @endphp
                            @if($cls->count())
                                <div class="mt-1 flex flex-wrap gap-1">
                                    @foreach($cls as $c)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-sky-50 text-sky-700 border border-sky-200">{{ $c->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded border text-gray-700 border-gray-300 hover:bg-gray-100 text-xs btnViewSubject"
                                    data-name="{{ $subject->name }}"
                                    data-description="{{ $subject->description }}"
                                    data-classes='@json(($subject->classes ?? collect())->pluck("name"))'>
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <span>View</span>
                                </button>
                                <button type="button"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded border text-indigo-700 border-indigo-300 hover:bg-indigo-50 text-xs btnEditSubject"
                                    data-id="{{ $subject->id }}"
                                    data-name="{{ $subject->name }}"
                                    data-description="{{ $subject->description }}"
                                    data-icon='@json($subject->icon)'>
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 1 1 3 3L7 19l-4 1 1-4 12.5-12.5Z"/></svg>
                                    <span>Edit</span>
                                </button>
                                <button type="button"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded border text-sky-700 border-sky-300 hover:bg-sky-50 text-xs btnAssignClasses"
                                    data-id="{{ $subject->id }}"
                                    data-name="{{ $subject->name }}">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7h-9"/><path d="M14 17H5"/><path d="M20 17h-3"/><path d="M5 7h3"/></svg>
                                    <span>Assign Classes</span>
                                </button>
                                <form method="POST" action="{{ route('learning.subjects.destroy', $subject) }}" class="js-confirm-delete" data-confirm-title="Delete Subject" data-confirm-message="Are you sure you want to delete this subject? This cannot be undone.">
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
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">No subjects found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ ($subjects ?? null)?->links() }}</div>
    </div>

    <!-- Assign Classes Modal -->
    <div id="assignClassesModal" class="fixed inset-0 bg-black/40 z-40 hidden">
        <div class="min-h-full flex items-center justify-center p-4">
            <div class="w-full max-w-xl bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 border-b">
                    <h3 class="text-lg font-semibold">Assign Classes to <span id="acSubjectName" class="text-sky-700"></span></h3>
                    <button type="button" id="btnCloseAssignClasses" class="p-1 rounded hover:bg-gray-100" aria-label="Close">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="p-4">
                    <form id="assignClassesForm" method="POST" class="space-y-4">
                        @csrf
                        <div id="acClassesContainer" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="text-gray-500 text-sm">Loading classes...</div>
                        </div>
                        <div class="flex items-center gap-2 pt-2">
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-sky-600 hover:bg-sky-700 text-white text-sm shadow">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                <span>Save</span>
                            </button>
                            <button type="button" id="btnCancelAssignClasses" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm">Cancel</button>
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

    <!-- Add Subject Modal -->
    <div id="addSubjectModal" class="fixed inset-0 bg-black/40 z-40 hidden">
        <div class="min-h-full flex items-center justify-center p-4">
            <div class="w-full max-w-xl bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 border-b">
                    <h3 class="text-lg font-semibold">Add Subject</h3>
                    <button type="button" id="btnCloseAddSubject" class="p-1 rounded hover:bg-gray-100" aria-label="Close">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="p-4">
                    <form method="POST" action="{{ route('learning.subjects.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" rows="3" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Icon</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">Google icon name (Material Symbols)</div>
                                    <div class="relative">
                                        <input type="text" id="addIconName" placeholder="e.g. menu_book" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                        <div id="addIconNameSuggest" class="absolute z-10 mt-1 w-full bg-white border rounded-lg shadow hidden max-h-56 overflow-auto"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">Or paste SVG</div>
                                    <textarea id="addIconInput" placeholder="<svg ...>...</svg>" rows="3" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                </div>
                            </div>
                            <input type="hidden" name="icon" id="addIconFinal" />
                            <div class="mt-2 flex items-center gap-3">
                                <div class="text-xs text-gray-500">Preview:</div>
                                <div id="addIconPreviewContainer" class="w-10 h-10 flex items-center justify-center text-gray-700"></div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">Type a Google icon name to get suggestions, or paste SVG. We will save whichever you provide.</div>
                        </div>
                        <div class="flex items-center gap-2 pt-2">
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm shadow">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                <span>Save</span>
                            </button>
                            <button type="button" id="btnCancelAddSubject" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Subject Modal -->
    <div id="editSubjectModal" class="fixed inset-0 bg-black/40 z-40 hidden">
        <div class="min-h-full flex items-center justify-center p-4">
            <div class="w-full max-w-xl bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 border-b">
                    <h3 class="text-lg font-semibold">Edit Subject</h3>
                    <button type="button" id="btnCloseEditSubject" class="p-1 rounded hover:bg-gray-100" aria-label="Close">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="p-4">
                    <form id="editSubjectForm" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="editName" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="editDescription" rows="3" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Icon</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">Google icon name (Material Symbols)</div>
                                    <div class="relative">
                                        <input type="text" id="editIconName" placeholder="e.g. menu_book" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                        <div id="editIconNameSuggest" class="absolute z-10 mt-1 w-full bg-white border rounded-lg shadow hidden max-h-56 overflow-auto"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">Or paste SVG</div>
                                    <textarea id="editIconInput" placeholder="<svg ...>...</svg>" rows="3" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                </div>
                            </div>
                            <input type="hidden" name="icon" id="editIconFinal" />
                            <div class="mt-2 flex items-center gap-3">
                                <div class="text-xs text-gray-500">Preview:</div>
                                <div id="editIconPreviewContainer" class="w-10 h-10 flex items-center justify-center text-gray-700"></div>
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm shadow">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                <span>Update</span>
                            </button>
                            <button type="button" id="btnCancelEditSubject" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function(){
        const addModal = document.getElementById('addSubjectModal');
        const editModal = document.getElementById('editSubjectModal');
        const btnOpenAdd = document.getElementById('btnOpenAddSubject');
        const btnCloseAdd = document.getElementById('btnCloseAddSubject');
        const btnCancelAdd = document.getElementById('btnCancelAddSubject');
        const btnCloseEdit = document.getElementById('btnCloseEditSubject');
        const btnCancelEdit = document.getElementById('btnCancelEditSubject');

        // Assign Classes modal elements
        const acModal = document.getElementById('assignClassesModal');
        const acSubjectName = document.getElementById('acSubjectName');
        const acForm = document.getElementById('assignClassesForm');
        const acClassesContainer = document.getElementById('acClassesContainer');
        const btnCloseAssign = document.getElementById('btnCloseAssignClasses');
        const btnCancelAssign = document.getElementById('btnCancelAssignClasses');

        // Search suggestions
        const searchInput = document.getElementById('subjectSearchInput');
        const suggestBox = document.getElementById('subjectSuggestBox');
        let searchTimer = null;
        function hideSuggest(){ suggestBox?.classList.add('hidden'); suggestBox.innerHTML = ''; }
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
                    const url = new URL("{{ route('learning.subjects.suggest') }}", window.location.origin);
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

        // Modals
        function open(el){ el?.classList.remove('hidden'); }
        function close(el){ el?.classList.add('hidden'); }
        btnOpenAdd?.addEventListener('click', ()=> { hideGlobalLoader?.(); open(addModal); });
        btnCloseAdd?.addEventListener('click', ()=> close(addModal));
        btnCancelAdd?.addEventListener('click', ()=> close(addModal));
        addModal?.addEventListener('click', (e)=>{ if(e.target===addModal) close(addModal); });
        btnCloseEdit?.addEventListener('click', ()=> close(editModal));
        btnCancelEdit?.addEventListener('click', ()=> close(editModal));
        editModal?.addEventListener('click', (e)=>{ if(e.target===editModal) close(editModal); });

        // Assign Classes logic
        function resetAssignModal(){
            if (acClassesContainer){ acClassesContainer.innerHTML = '<div class="text-gray-500 text-sm">Loading classes...</div>'; }
        }
        async function fetchJSON(url){ const r = await fetch(url, { headers: { 'Accept':'application/json' } }); if(!r.ok) throw new Error('Network'); return r.json(); }
        function buildCheckbox(id, name, checked){
            const isChecked = checked ? 'checked' : '';
            return `
            <label class="flex items-center gap-2 border rounded-lg px-3 py-2">
                <input type="checkbox" name="class_ids[]" value="${id}" class="rounded" ${isChecked} />
                <span class="text-sm text-gray-800">${name}</span>
            </label>`;
        }
        async function openAssignClasses(subjectId, subjectName){
            acSubjectName.textContent = subjectName || '';
            resetAssignModal();
            // set form action
            acForm.action = `{{ url('learning/subjects') }}/${subjectId}/classes`;
            open(acModal);
            try{
                const url = `{{ url('learning/subjects') }}/${subjectId}/classes-json`;
                const data = await fetchJSON(url);
                const selected = new Set((data.selected||[]).map(Number));
                const list = (data.classes||[]);
                if (!list.length){ acClassesContainer.innerHTML = '<div class="text-gray-500 text-sm">No classes found.</div>'; return; }
                acClassesContainer.innerHTML = list.map(item => buildCheckbox(item.id, item.name, selected.has(Number(item.id)))).join('');
            }catch(e){
                acClassesContainer.innerHTML = '<div class="text-red-600 text-sm">Failed to load classes.</div>';
            }
        }
        document.querySelectorAll('.btnAssignClasses').forEach(btn => {
            btn.addEventListener('click', ()=>{
                const id = btn.getAttribute('data-id');
                const name = btn.getAttribute('data-name');
                hideGlobalLoader?.();
                openAssignClasses(id, name);
            });
        });
        btnCloseAssign?.addEventListener('click', ()=> close(acModal));
        btnCancelAssign?.addEventListener('click', ()=> close(acModal));
        acModal?.addEventListener('click', (e)=>{ if(e.target===acModal) close(acModal); });

        // Icon picker: Google Material Symbols name suggestions and SVG fallback
        const materialNames = [
            'menu','menu_book','school','book','library_books','bookmark','favorite','home','settings','notifications','search','person','group','dashboard','assignment','edit','delete','add','close','done','check_circle','warning','info','visibility','visibility_off','upload','download','description','category','class','quiz','translate','help','share'
        ];
        function filterNames(q){ q=q.toLowerCase(); return materialNames.filter(n=>n.includes(q)).slice(0,8); }
        function renderNameSuggest(container, input){
            const q = input.value.trim();
            const items = q? filterNames(q): [];
            if (!items.length){ container.classList.add('hidden'); container.innerHTML=''; return; }
            container.innerHTML = items.map(x=>`<button type="button" class="w-full text-left px-3 py-2 hover:bg-gray-50">${x}</button>`).join('');
            container.classList.remove('hidden');
            Array.from(container.querySelectorAll('button')).forEach(btn=>btn.addEventListener('click', ()=>{ input.value = btn.textContent.trim(); container.classList.add('hidden'); container.innerHTML=''; updateAddPreview(); updateEditPreview(); }));
        }

        const addIconName = document.getElementById('addIconName');
        const addIconNameSuggest = document.getElementById('addIconNameSuggest');
        const addIconInput = document.getElementById('addIconInput');
        const addIconPreviewC = document.getElementById('addIconPreviewContainer');
        const addIconFinal = document.getElementById('addIconFinal');
        const editIconName = document.getElementById('editIconName');
        const editIconNameSuggest = document.getElementById('editIconNameSuggest');
        const editIconInput = document.getElementById('editIconInput');
        const editIconPreviewC = document.getElementById('editIconPreviewContainer');
        const editIconFinal = document.getElementById('editIconFinal');
        const editForm = document.getElementById('editSubjectForm');
        const editName = document.getElementById('editName');
        const editDesc = document.getElementById('editDescription');

        const defaultEye = '<svg class="w-6 h-6 text-gray-700" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z"/><circle cx="12" cy="12" r="3"/></svg>';
        function renderIcon(val, container){
            const v = (val||'').trim();
            if (v.includes('<svg')) container.innerHTML = v; else if (v) container.innerHTML = `<span class="material-symbols-rounded text-2xl">${v}</span>`; else container.innerHTML = defaultEye;
        }
        function updateAddPreview(){
            // priority: SVG textarea if contains <svg>, else icon name
            const svg = (addIconInput?.value||'').trim();
            const name = (addIconName?.value||'').trim();
            if (svg.includes('<svg')) { addIconFinal.value = svg; renderIcon(svg, addIconPreviewC); }
            else if (name) { addIconFinal.value = name; renderIcon(name, addIconPreviewC); }
            else { addIconFinal.value = ''; renderIcon('', addIconPreviewC); }
        }
        function updateEditPreview(){
            const svg = (editIconInput?.value||'').trim();
            const name = (editIconName?.value||'').trim();
            if (svg.includes('<svg')) { editIconFinal.value = svg; renderIcon(svg, editIconPreviewC); }
            else if (name) { editIconFinal.value = name; renderIcon(name, editIconPreviewC); }
            else { editIconFinal.value = ''; renderIcon('', editIconPreviewC); }
        }

        addIconInput?.addEventListener('input', updateAddPreview);
        addIconName?.addEventListener('input', ()=>{ renderNameSuggest(addIconNameSuggest, addIconName); updateAddPreview(); });
        document.addEventListener('click', (e)=>{ if (!addIconNameSuggest?.contains(e.target) && e.target!==addIconName) { addIconNameSuggest?.classList.add('hidden'); } });

        editIconInput?.addEventListener('input', updateEditPreview);
        editIconName?.addEventListener('input', ()=>{ renderNameSuggest(editIconNameSuggest, editIconName); updateEditPreview(); });
        document.addEventListener('click', (e)=>{ if (!editIconNameSuggest?.contains(e.target) && e.target!==editIconName) { editIconNameSuggest?.classList.add('hidden'); } });

        // Bind edit buttons and populate fields
        document.querySelectorAll('.btnEditSubject').forEach(btn => {
            btn.addEventListener('click', ()=>{
                const id = btn.getAttribute('data-id');
                const name = btn.getAttribute('data-name');
                const description = btn.getAttribute('data-description') || '';
                const iconRaw = JSON.parse(btn.getAttribute('data-icon')||'null') || '';
                editName.value = name;
                editDesc.value = description;
                if (iconRaw.includes('<svg')) { editIconInput.value = iconRaw; editIconName.value = ''; }
                else { editIconName.value = iconRaw; editIconInput.value = ''; }
                updateEditPreview();
                editForm.action = `{{ url('learning/subjects') }}/${id}`;
                hideGlobalLoader?.();
                open(editModal);
            });
        });

        // initial previews
        updateAddPreview();

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

        function hideGlobalLoader(){ try{ const gl=document.getElementById('globalPageLoader'); if(gl){ gl.classList.add('hidden'); gl.classList.remove('flex'); } }catch{} }
        document.querySelectorAll('form.js-confirm-delete').forEach(f => {
            f.addEventListener('submit', (e)=>{
                e.preventDefault();
                const title = f.getAttribute('data-confirm-title') || 'Confirm';
                const msg = f.getAttribute('data-confirm-message') || 'Are you sure?';
                hideGlobalLoader(); openConfirm(title, msg, f);
            });
        });

        // View Subject logic
        const vModal = document.getElementById('viewSubjectModal');
        const vName = document.getElementById('viewSubjName');
        const vDesc = document.getElementById('viewSubjDesc');
        const vClasses = document.getElementById('viewSubjClasses');
        const vClose1 = document.getElementById('btnCloseViewSubject');
        const vClose2 = document.getElementById('btnCloseViewSubject2');
        function openView(){ vModal?.classList.remove('hidden'); }
        function closeView(){ vModal?.classList.add('hidden'); vClasses.innerHTML=''; }
        vClose1?.addEventListener('click', closeView);
        vClose2?.addEventListener('click', closeView);
        vModal?.addEventListener('click', (e)=>{ if(e.target===vModal) closeView(); });
        document.querySelectorAll('.btnViewSubject').forEach(btn => {
            btn.addEventListener('click', ()=>{
                const name = btn.getAttribute('data-name') || '-';
                const desc = btn.getAttribute('data-description') || '-';
                let cls = [];
                try { cls = JSON.parse(btn.getAttribute('data-classes')||'[]'); } catch {}
                vName.textContent = name;
                vDesc.textContent = desc || '-';
                vClasses.innerHTML = (Array.isArray(cls) && cls.length) ? cls.map(n=>`<li>${n}</li>`).join('') : '<li class="text-gray-400">None</li>';
                hideGlobalLoader?.();
                openView();
            });
        });
    })();
    </script>
</x-admin-layout>
