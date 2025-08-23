@php
    /** @var \App\Models\SchoolClass $class */
    /** @var \Illuminate\Support\Collection|array $notes */
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Class Details') }}</h2>
            <a href="{{ route('user.classes.index') }}" class="inline-flex items-center gap-2 px-3 py-1.5 border rounded-md hover:bg-gray-50">
                <span class="material-symbols-outlined text-gray-600">arrow_back</span>
                <span class="text-sm">Back to Classes</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8 select-none" oncopy="return false" oncut="return false" onpaste="return false" oncontextmenu="return false">
        <style>
            * { -webkit-touch-callout: none; -webkit-user-select: none; user-select: none; }
            @media print { body { display: none !important; } }
            iframe.secure-preview { pointer-events: auto; }
        </style>
        <script>
            document.addEventListener('keydown', function(e) {
                const key = e.key?.toLowerCase();
                if ((e.ctrlKey || e.metaKey) && ['c','x','p','s','u','shift','i'].includes(key)) { e.preventDefault(); }
                if (e.key === 'PrintScreen') { e.preventDefault(); }
            }, true);
        </script>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-2xl font-semibold text-gray-800 flex items-center gap-2">
                            <span class="material-symbols-outlined text-indigo-600">school</span>
                            {{ $class->name }}
                        </div>
                        @if(!empty($class->description))
                            <div class="mt-1 text-gray-600">{{ $class->description }}</div>
                        @endif
                    </div>
                </div>

                <div class="mt-4">
                    <div class="text-sm font-medium text-gray-700 mb-2">Filters by Subject</div>
                    <div class="flex flex-wrap gap-2">
                        @php($queryBase = function($id = null) use($class){ return route('user.classes.show', $class->id) . ($id ? ('?subject_id=' . $id) : ''); })
                        <a href="{{ $queryBase(null) }}" class="inline-flex items-center gap-1 px-2 py-1 rounded-full border text-xs {{ empty($selectedSubjectId) ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                            <span class="material-symbols-outlined text-[16px]">filter_alt_off</span>
                            All
                        </a>
                        @foreach(($subjects ?? collect()) as $s)
                            <a href="{{ $queryBase($s->id) }}" class="inline-flex items-center gap-1 px-2 py-1 rounded-full border text-xs {{ ($selectedSubjectId ?? null) == $s->id ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                                <span class="material-symbols-outlined text-[16px]">category</span>
                                {{ $s->name }}
                            </a>
                        @endforeach
                        @if(($subjects ?? collect())->isEmpty())
                            <span class="text-sm text-gray-500">No subjects assigned.</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-0 overflow-hidden">
                <div class="p-5 border-b border-gray-200 flex items-center justify-between">
                    <div class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-600">event_note</span>
                        Documents
                    </div>
                </div>
                @if($notes->isEmpty())
                    <div class="p-5 text-sm text-gray-500">No materials found for this class.</div>
                @else
                    <div class="grid grid-cols-1 lg:grid-cols-2">
                        <div class="p-5 border-r border-gray-200">
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                        <tr class="text-left text-gray-600">
                                            <th class="py-2 pr-4">Title</th>
                                            <th class="py-2 pr-4">Created</th>
                                            <th class="py-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($notes as $n)
                                            @php($isActive = ($selectedNoteId ?? null) == $n->id)
                                            <tr class="{{ $isActive ? 'bg-indigo-50' : '' }}">
                                                <td class="py-2 pr-4">
                                                    <div class="font-medium text-gray-800 line-clamp-1">{{ $n->title }}</div>
                                                </td>
                                                <td class="py-2 pr-4 text-gray-600">{{ optional($n->created_at)->format('M d, Y') }}</td>
                                                <td class="py-2">
                                                    <a href="{{ request()->fullUrlWithQuery(['note_id' => $n->id]) }}" class="inline-flex items-center gap-1 px-2 py-1 border rounded {{ $isActive ? 'border-indigo-600 text-indigo-700 bg-white' : 'hover:bg-gray-50' }}">
                                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                                        Preview
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="border-t lg:border-t-0">
                            @php($current = ($selectedNoteId ? $notes->firstWhere('id', $selectedNoteId) : $notes->first()))
                            @if($current)
                                <div class="p-5 flex items-center justify-between">
                                    <div class="font-semibold text-gray-800 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-indigo-600">picture_as_pdf</span>
                                        {{ $current->title }}
                                    </div>
                                </div>
                                <div class="h-[70vh] bg-gray-50 border-t">
                                    <iframe class="secure-preview w-full h-full" src="{{ url('/api/mobile/content/notes/'.$current->id.'/preview') }}" sandbox="allow-same-origin allow-scripts" referrerpolicy="no-referrer" loading="lazy"></iframe>
                                </div>
                                <div class="p-4 text-xs text-gray-500">Preview is protected: copy/print/download are disabled.</div>
                            @else
                                <div class="p-5 text-sm text-gray-500">Select a document to preview.</div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
