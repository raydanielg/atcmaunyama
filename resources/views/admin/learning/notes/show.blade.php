<x-admin-layout>
    <!-- Masthead strip -->
    <div class="h-1 bg-gradient-to-r from-indigo-500 via-sky-500 to-emerald-500 rounded-t-xl"></div>

    <div class="py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Note Details</h1>
                <p class="text-sm text-gray-500 mt-1">View note content and actions.</p>
            </div>
            <a href="{{ route('learning.notes.index') }}" class="inline-flex items-center px-3 py-1.5 rounded border text-gray-700 border-gray-300 hover:bg-gray-100 text-xs">Back to list</a>
        </div>

        <!-- Dashed divider under heading -->
        <div class="mt-3 border-t border-dashed border-gray-300"></div>

        @if(session('status'))
            <div class="mt-4 p-3 rounded bg-green-50 text-green-700 border border-green-200">{{ session('status') }}</div>
        @endif

        <div class="mt-4 bg-white rounded-lg border p-4 space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs uppercase text-gray-500">Title</div>
                    <div class="text-lg font-semibold text-gray-900">{{ $note->title }}</div>
                </div>
                <div class="flex items-center gap-2">
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
            </div>

            <div>
                <div class="text-xs uppercase text-gray-500">Author</div>
                <div class="text-sm text-gray-800">{{ optional($note->user ?? null)->name ?? '—' }}</div>
            </div>

            <div>
                <div class="text-xs uppercase text-gray-500">Body</div>
                <div class="prose prose-sm max-w-none">{!! nl2br(e($note->body)) !!}</div>
            </div>

            @php
                $fileUrl = $note->file_path ? asset('storage/'.$note->file_path) : null;
                $mime = $note->mime_type ?? null;
                $ext = $note->original_name ? strtolower(pathinfo($note->original_name, PATHINFO_EXTENSION)) : null;
                $isPdf = $mime === 'application/pdf' || $ext === 'pdf';
                $isOffice = in_array($ext, ['doc','docx','ppt','pptx']);
                // Office Online viewer requires publicly accessible URL
                $officeViewer = $fileUrl ? 'https://view.officeapps.live.com/op/embed.aspx?src='.urlencode($fileUrl) : null;
            @endphp

            @if($fileUrl)
                <div class="pt-2">
                    <div class="text-xs uppercase text-gray-500 mb-2">Document Preview</div>

                    @if($isPdf)
                        <div class="border rounded-lg overflow-hidden">
                            <iframe src="{{ $fileUrl }}" class="w-full" style="height: 650px;" loading="lazy"></iframe>
                        </div>
                    @elseif($isOffice)
                        <div class="border rounded-lg overflow-hidden">
                            <iframe src="{{ $officeViewer }}" class="w-full" style="height: 650px;" loading="lazy"></iframe>
                        </div>
                    @else
                        <div class="p-3 bg-gray-50 border rounded">
                            <div class="text-sm text-gray-700">Preview not available for this file type.</div>
                        </div>
                    @endif

                    <div class="mt-3 flex items-center gap-2">
                        <a href="{{ $fileUrl }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded border text-gray-700 border-gray-300 hover:bg-gray-100 text-xs">
                            <span class="material-symbols-outlined text-sm">open_in_new</span>
                            <span>Open in new tab</span>
                        </a>
                        <a href="{{ $fileUrl }}" download class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded border text-gray-700 border-gray-300 hover:bg-gray-100 text-xs">
                            <span class="material-symbols-outlined text-sm">download</span>
                            <span>Download</span>
                        </a>
                    </div>
                </div>
            @else
                <div class="pt-2 p-3 bg-yellow-50 text-yellow-800 border border-yellow-200 rounded">No document uploaded for this note.</div>
            @endif
        </div>
    </div>
</x-admin-layout>
