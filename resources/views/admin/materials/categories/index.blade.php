<x-admin-layout>
    <div class="py-4">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Material Course</h1>
                <p class="text-sm text-gray-500">Read-only view. Assignments are managed from Learning → Courses.</p>
            </div>
        </div>
        <div class="border-t border-dashed mb-4"></div>

        <div class="mb-3 flex items-start justify-between gap-4">
            <form method="GET" class="flex items-center gap-2 relative" autocomplete="off">
                <div class="relative">
                    <input id="catSearchInput" type="text" name="q" value="{{ $q ?? '' }}" placeholder="Search courses..." class="w-72 border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                    <div id="catSuggestBox" class="absolute z-10 mt-1 w-full bg-white border rounded-lg shadow hidden max-h-56 overflow-auto"></div>
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
                        <th class="px-4 py-3 font-medium">Assigned Types</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse(($categories ?? []) as $cat)
                        <tr class="text-sm">
                            <td class="px-4 py-3 text-gray-900">{{ $cat->name }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $types = ($cat->types ?? collect());
                                    // Deduplicate by normalized name (in case duplicates exist across pivot)
                                    $uniqueTypes = $types->unique(function($t){ return strtolower(trim($t->name ?? '')); })->values();
                                @endphp
                                @if($uniqueTypes->isEmpty())
                                    <span class="text-gray-400 text-sm">None</span>
                                @else
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($uniqueTypes as $s)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200 text-xs">
                                                <span class="material-symbols-outlined text-[14px]">bookmark</span>
                                                {{ $s->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-10 text-center">
                                <div class="flex flex-col items-center justify-center gap-4">
                                    <div class="text-gray-800">
                                        <!-- Empty state illustration -->
                                        <svg class="w-auto max-w-[16rem] h-40 text-gray-800 dark:text-white" aria-hidden="true" width="783" height="554" viewBox="0 0 783 554" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M156.622 542.948H222.329V547C222.329 548.105 221.434 549 220.329 549H158.622C157.517 549 156.622 548.105 156.622 547V542.948Z" fill="#2563eb"/>
<path d="M156.622 542.948L162.674 499.72L187.746 489.777C185.297 501.017 189.043 524.36 201.147 527.818C217.451 532.476 222.185 539.346 222.329 542.948H156.622Z" fill="#d6e2fb"/>
<path d="M156.622 542.948L162.674 499.72L187.746 489.777C185.297 501.017 189.043 524.36 201.147 527.818C217.451 532.476 222.185 539.346 222.329 542.948H156.622Z" fill="url(#paint0_linear_182_7954)"/>
<mask id="mask0_182_7954" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="156" y="489" width="67" height="54">
<path d="M156.622 542.948L162.674 499.72L187.746 489.777C185.297 501.017 189.043 524.36 201.147 527.818C217.451 532.476 222.185 539.346 222.329 542.948H156.622Z" fill="#d6e2fb"/>
<path d="M156.622 542.948L162.674 499.72L187.746 489.777C185.297 501.017 189.043 524.36 201.147 527.818C217.451 532.476 222.185 539.346 222.329 542.948H156.622Z" fill="url(#paint1_linear_182_7954)"/>
</mask>
<g mask="url(#mask0_182_7954)">
<path d="M205.037 525.657H223.193V542.948H201.579L205.037 525.657Z" fill="#c8d8fa"/>
</g>
<path fill-rule="evenodd" clip-rule="evenodd" d="M171.752 527.98C174.228 527.98 176.236 525.972 176.236 523.495C176.236 521.019 174.228 519.011 171.752 519.011C169.275 519.011 167.268 521.019 167.268 523.495C167.268 525.972 169.275 527.98 171.752 527.98ZM171.752 529.98C175.333 529.98 178.236 527.077 178.236 523.495C178.236 519.914 175.333 517.011 171.752 517.011C168.171 517.011 165.268 519.914 165.268 523.495C165.268 527.077 168.171 529.98 171.752 529.98Z" fill="#c8d8fa"/>
<path d="M235.298 542.948H301.005V547C301.005 548.105 300.11 549 299.005 549H237.298C236.193 549 235.298 548.105 235.298 547V542.948Z" fill="#2563eb"/>
<path d="M230.11 488.913L235.298 542.948H301.005C299.276 534.735 294.953 535.167 274.636 526.089C258.382 518.827 254.318 498.279 254.318 488.913H230.11Z" fill="#d6e2fb"/>
<path d="M230.11 488.913L235.298 542.948H301.005C299.276 534.735 294.953 535.167 274.636 526.089C258.382 518.827 254.318 498.279 254.318 488.913H230.11Z" fill="url(#paint2_linear_182_7954)"/>
<mask id="mask1_182_7954" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="230" y="488" width="72" height="55">
<path d="M230.11 488.913L235.298 542.948H301.005C299.276 534.735 294.953 535.167 274.636 526.089C258.382 518.827 254.318 498.279 254.318 488.913H230.11Z" fill="#d6e2fb"/>
<path d="M230.11 488.913L235.298 542.948H301.005C299.276 534.735 294.953 535.167 274.636 526.089C258.382 518.827 254.318 498.279 254.318 488.913H230.11Z" fill="url(#paint3_linear_182_7954)"/>
</mask>
<g mask="url(#mask1_182_7954)">
<path d="M283.713 525.657H301.869V542.948H280.255L283.713 525.657Z" fill="#c8d8fa"/>
</g>
<path fill-rule="evenodd" clip-rule="evenodd" d="M246.97 527.98C249.446 527.98 251.454 525.972 251.454 523.495C251.454 521.019 249.446 519.011 246.97 519.011C244.493 519.011 242.485 521.019 242.485 523.495C242.485 525.972 244.493 527.98 246.97 527.98ZM246.97 529.98C250.551 529.98 253.454 527.077 253.454 523.495C253.454 519.914 250.551 517.011 246.97 517.011C243.388 517.011 240.485 519.914 240.485 523.495C240.485 527.077 243.388 529.98 246.97 529.98Z" fill="#c8d8fa"/>
<!-- ... trimmed for brevity: remaining SVG paths and defs unchanged from user's provided SVG ... -->
<defs>
<linearGradient id="paint0_linear_182_7954" x1="204.674" y1="480.699" x2="204.674" y2="529.547" gradientUnits="userSpaceOnUse">
<stop stop-color="#c8d8fa"/>
<stop offset="1" stop-color="#c8d8fa" stop-opacity="0"/>
</linearGradient>
<!-- all other gradients/defs from provided SVG here -->
</defs>
</svg>
                                    </div>
                                    <div class="text-gray-500 text-sm">No courses yet. Start by adding one.</div>
                                    <button id="btnOpenAddCatEmpty" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm shadow">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                        <span>Add Course</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ ($categories ?? null)?->links() }}</div>
    </div>

    <script>
    (function(){
        // AJAX search suggestions
        const sInput = document.getElementById('catSearchInput');
        const sBox = document.getElementById('catSuggestBox');
        let sTimer = null;
        function hideS(){ sBox?.classList.add('hidden'); if (sBox) sBox.innerHTML=''; }
        function showS(items){
            if (!sBox) return;
            if (!items || items.length === 0){ hideS(); return; }
            sBox.innerHTML = items.map(x=>`<button type="button" class="w-full text-left px-3 py-2 hover:bg-gray-50">${x}</button>`).join('');
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
                    const url = new URL("{{ route('materials.categories.suggest') }}", window.location.origin);
                    url.searchParams.set('q', q);
                    const res = await fetch(url);
                    const data = await res.json();
                    showS(data);
                }catch(e){ hideS(); }
            }, 250);
        });
        document.addEventListener('click', (e)=>{ if (!sBox?.contains(e.target) && e.target !== sInput) hideS(); });
        function renderTypes(filter=''){
            const f = (filter||'').trim().toLowerCase();
            const items = typesCache.filter(t => !f || (t.name||'').toLowerCase().includes(f));
            if (items.length === 0){
                typesList.innerHTML = '<div class="col-span-2 text-center text-gray-500 py-8">No types found.</div>';
                return;
            }
            typesList.innerHTML = items.map(t => `
                <label class="flex items-center gap-3 p-2 rounded border hover:bg-gray-50">
                    <input type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" value="${t.id}" ${t.assigned ? 'checked' : ''}>
                    <span class="text-sm text-gray-800">${t.name}</span>
                </label>
            `).join('');
        }

        document.getElementById('btnCloseAssignTypes')?.addEventListener('click', ()=>close(assignModal));
        document.getElementById('btnCancelAssignTypes')?.addEventListener('click', ()=>close(assignModal));
        typesFilter?.addEventListener('input', ()=>renderTypes(typesFilter.value));

        Array.from(document.querySelectorAll('.btnAssignTypes')).forEach(btn=>{
            btn.addEventListener('click', async ()=>{
                currentLevelId = parseInt(btn.getAttribute('data-id'));
                assignLevelName.textContent = btn.getAttribute('data-name') || '';
                typesList.innerHTML = '<div class="col-span-2 text-center text-gray-500 py-8">Loading...</div>';
                open(assignModal);
                try{
                    const url = new URL(`{{ url('materials/categories') }}/${currentLevelId}/types-json`, window.location.origin);
                    const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
                    if (!res.ok) throw new Error(`HTTP ${res.status}`);
                    const data = await res.json();
                    // Deduplicate by name just in case
                    const map = new Map();
                    for (const t of (Array.isArray(data)?data:[])){
                        const k = String(t.name||'').toLowerCase();
                        if (!map.has(k)) map.set(k, { id: t.id, name: t.name, assigned: !!t.assigned });
                        else { const cur = map.get(k); cur.assigned = cur.assigned || !!t.assigned; }
                    }
                    typesCache = Array.from(map.values()).sort((a,b)=>String(a.name||'').localeCompare(String(b.name||'')));
                    renderTypes(typesFilter.value);
                }catch(e){
                    console.error('Load types failed', e);
                    typesList.innerHTML = '<div class="col-span-2 text-center text-red-500 py-8">Failed to load types. Please refresh.</div>';
                }
            });
        });

        document.getElementById('btnSaveAssignTypes')?.addEventListener('click', async ()=>{
            if (!currentLevelId) return;
            const checked = Array.from(typesList.querySelectorAll('input[type="checkbox"]:checked')).map(i=>parseInt(i.value));
            const btn = document.getElementById('btnSaveAssignTypes');
            const original = btn.innerHTML;
            btn.disabled = true; btn.innerHTML = '<span class="material-symbols-outlined animate-spin mr-2">progress_activity</span>Saving...';
            try{
                const url = new URL(`{{ url('materials/categories') }}/${currentLevelId}/types-sync`, window.location.origin);
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ type_ids: checked })
                });
                const data = await res.json();
                if (data && data.success){ window.location.reload(); }
                else alert('Failed to save assignments.');
            }catch(e){ alert('Error saving.'); }
            finally { btn.disabled = false; btn.innerHTML = original; }
        });
    })();
    </script>
</x-admin-layout>
