<x-admin-layout>
    <div class="py-4">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Material Subcategories</h1>
                <p class="text-sm text-gray-500">Manage subcategories and map them to categories. SVG icons supported.</p>
            </div>
            <button id="btnOpenAddSub" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm shadow">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                <span>Add Subcategory</span>
            </button>
        </div>
        <div class="border-t border-dashed mb-4"></div>

        <div class="mb-3 flex items-start justify-between gap-4">
            <form method="GET" class="flex items-center gap-2 relative" autocomplete="off">
                <div class="relative">
                    <input id="subSearchInput" type="text" name="q" value="{{ $q ?? '' }}" placeholder="Search subcategories..." class="w-72 border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                    <div id="subSuggestBox" class="absolute z-10 mt-1 w-full bg-white border rounded-lg shadow hidden max-h-56 overflow-auto"></div>
                </div>
                <button class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/></svg>
                    <span>Search</span>
                </button>
            </form>
        </div>

        <div class="mt-4 bg-white border rounded-lg overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-700">
                    <tr class="text-sm">
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Category</th>
                        <th class="px-4 py-3 font-medium">Year</th>
                        <th class="px-4 py-3 font-medium">Icon</th>
                        <th class="px-4 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse(($subcategories ?? []) as $sub)
                        <tr class="text-sm">
                            <td class="px-4 py-3 text-gray-900">{{ $sub->name }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $sub->category?->name }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $sub->year ?? '-' }}</td>
                            <td class="px-4 py-3"><div class="w-6 h-6 text-gray-700">{!! $sub->icon !!}</div></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        class="btnEditSub inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50"
                                        data-id="{{ $sub->id }}"
                                        data-name="{{ $sub->name }}"
                                        data-category-id="{{ $sub->category_id }}"
                                        data-year="{{ $sub->year }}"
                                        data-icon='@json($sub->icon)'
                                    >
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                        <span class="text-sm">Edit</span>
                                    </button>
                                    <form class="js-confirm-delete" method="POST" action="{{ route('materials.subcategories.destroy', $sub) }}" data-confirm-title="Delete Subcategory" data-confirm-message="Are you sure you want to delete this subcategory?">
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
                            <td colspan="5" class="px-4 py-10 text-center">
                                <div class="flex flex-col items-center justify-center gap-4">
                                    <div class="text-gray-800">
                                        <svg class="w-auto max-w-[16rem] h-40 text-gray-800" aria-hidden="true" width="411" height="578" viewBox="0 0 411 578" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M59 6C59 2.68629 61.6863 0 65 0H261C264.314 0 267 2.68629 267 6V245C267 248.314 264.314 251 261 251H65C61.6863 251 59 248.314 59 245V6Z" fill="#d6e2fb"/></svg>
                                    </div>
                                    <div class="text-gray-500 text-sm">No subcategories yet. Start by adding one.</div>
                                    <button id="btnOpenAddSubEmpty" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm shadow">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                        <span>Add Subcategory</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ ($subcategories ?? null)?->links() }}</div>
    </div>

    <!-- Add Subcategory Modal -->
    <div id="addSubModal" class="fixed inset-0 bg-black/30 hidden z-50">
        <div class="min-h-full w-full grid place-items-center p-4">
            <div class="bg-white rounded-lg shadow max-w-lg w-full">
                <div class="px-4 py-3 border-b flex items-center justify-between">
                    <h3 class="font-semibold">Add Subcategory</h3>
                    <button id="btnCloseAddSub" class="p-1 hover:bg-gray-100 rounded">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('materials.subcategories.store') }}" class="p-4">
                    @csrf
                    <div class="grid gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input name="name" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="category_id" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select category</option>
                                @foreach(($categories ?? []) as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Year (optional)</label>
                            <input type="number" name="year" min="1900" max="2100" placeholder="e.g. 2024" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">SVG Icon (optional)</label>
                            <textarea name="icon" rows="4" placeholder="<svg>...</svg>" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                            <p class="text-xs text-gray-500 mt-1">Paste inline SVG markup.</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-end gap-2">
                        <button type="button" id="btnCancelAddSub" class="px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Subcategory Modal -->
    <div id="editSubModal" class="fixed inset-0 bg-black/30 hidden z-50">
        <div class="min-h-full w-full grid place-items-center p-4">
            <div class="bg-white rounded-lg shadow max-w-lg w-full">
                <div class="px-4 py-3 border-b flex items-center justify-between">
                    <h3 class="font-semibold">Edit Subcategory</h3>
                    <button id="btnCloseEditSub" class="p-1 hover:bg-gray-100 rounded">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form id="editSubForm" method="POST" action="#" class="p-4">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input id="editSubName" name="name" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category</label>
                            <select id="editSubCategory" name="category_id" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select category</option>
                                @foreach(($categories ?? []) as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Year (optional)</label>
                            <input id="editSubYear" type="number" name="year" min="1900" max="2100" placeholder="e.g. 2024" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">SVG Icon (optional)</label>
                            <textarea id="editSubIcon" name="icon" rows="4" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                            <p class="text-xs text-gray-500 mt-1">Paste inline SVG markup.</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-end gap-2">
                        <button type="button" id="btnCancelEditSub" class="px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    (function(){
        // AJAX search suggestions
        const sInput = document.getElementById('subSearchInput');
        const sBox = document.getElementById('subSuggestBox');
        let sTimer = null;
        function hideS(){ sBox?.classList.add('hidden'); if (sBox) sBox.innerHTML=''; }
        function showS(items){
            if (!sBox) return;
            if (!items || items.length === 0){ hideS(); return; }
            sBox.innerHTML = items.map(x=>`<button type=\"button\" class=\"w-full text-left px-3 py-2 hover:bg-gray-50\">${x}</button>`).join('');
            sBox.classList.remove('hidden');
            Array.from(sBox.querySelectorAll('button')).forEach(btn=>{
                btn.addEventListener('click', ()=>{ sInput.value = btn.textContent; hideS(); sInput.form.submit(); });
            });
        }
        sInput?.addEventListener('input', ()=>{
            clearTimeout(sTimer);
            sTimer = setTimeout(async ()=>{
                const q = sInput.value.trim();
                if (!q){ hideS(); return; }
                try{
                    const url = new URL("{{ route('materials.subcategories.suggest') }}", window.location.origin);
                    url.searchParams.set('q', q);
                    const res = await fetch(url);
                    const data = await res.json();
                    showS(data);
                }catch(e){ hideS(); }
            }, 250);
        });
        document.addEventListener('click', (e)=>{ if (!sBox?.contains(e.target) && e.target !== sInput) hideS(); });

        // Modals open/close
        const addModal = document.getElementById('addSubModal');
        const editModal = document.getElementById('editSubModal');
        function open(m){ m?.classList.remove('hidden'); }
        function close(m){ m?.classList.add('hidden'); }
        document.getElementById('btnOpenAddSub')?.addEventListener('click', ()=>open(addModal));
        document.getElementById('btnOpenAddSubEmpty')?.addEventListener('click', ()=>open(addModal));
        document.getElementById('btnCloseAddSub')?.addEventListener('click', ()=>close(addModal));
        document.getElementById('btnCancelAddSub')?.addEventListener('click', ()=>close(addModal));
        document.getElementById('btnCloseEditSub')?.addEventListener('click', ()=>close(editModal));
        document.getElementById('btnCancelEditSub')?.addEventListener('click', ()=>close(editModal));

        // Edit buttons wire-up
        Array.from(document.querySelectorAll('.btnEditSub')).forEach(btn=>{
            btn.addEventListener('click', ()=>{
                const id = btn.getAttribute('data-id');
                const name = btn.getAttribute('data-name') || '';
                const catId = btn.getAttribute('data-category-id') || '';
                const year = btn.getAttribute('data-year') || '';
                const icon = JSON.parse(btn.getAttribute('data-icon') || 'null') || '';
                const form = document.getElementById('editSubForm');
                form.action = `{{ url('/materials/subcategories') }}/${id}`;
                document.getElementById('editSubName').value = name;
                document.getElementById('editSubCategory').value = catId;
                document.getElementById('editSubYear').value = year;
                document.getElementById('editSubIcon').value = icon;
                open(editModal);
            });
        });
    })();
    </script>
</x-admin-layout>
