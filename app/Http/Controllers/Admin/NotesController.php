<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Level;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Support\ActivityLog;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    public function index(Request $request)
    {
        $query = Note::query();
        if ($s = $request->get('s')) {
            $query->where('title', 'like', "%{$s}%");
        }
        $notes = $query->latest('id')->paginate(10)->withQueryString();
        return view('admin.learning.notes.index', compact('notes', 's'));
    }

    public function create()
    {
        return view('admin.learning.notes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'body' => ['nullable','string'],
            'level_id' => ['nullable','integer'],
            'subject_id' => ['nullable','integer'],
            'class_id' => ['nullable','integer'],
            'file' => ['nullable','file','mimes:pdf,doc,docx,ppt,pptx,txt','max:20480'],
        ]);

        $filePath = null; $original = null; $mime = null; $size = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $original = $file->getClientOriginalName();
            $mime = $file->getClientMimeType();
            $size = $file->getSize();
            $filePath = $file->store('notes', 'public');
        }

        $note = Note::create([
            'title' => $data['title'],
            'body' => $data['body'] ?? null,
            'user_id' => auth()->id(),
            'level_id' => $data['level_id'] ?? null,
            'subject_id' => $data['subject_id'] ?? null,
            'class_id' => $data['class_id'] ?? null,
            'file_path' => $filePath,
            'original_name' => $original,
            'mime_type' => $mime,
            'file_size' => $size,
        ]);
        ActivityLog::log('note.created', "Created note #{$note->id}: {$note->title}", auth()->id());

        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'id' => $note->id]);
        }
        return redirect()->route('learning.notes.index')->with('status', 'Note created.');
    }

    public function show(Note $note)
    {
        return view('admin.learning.notes.show', compact('note'));
    }

    public function edit(Note $note)
    {
        return view('admin.learning.notes.edit', compact('note'));
    }

    public function update(Request $request, Note $note)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'body' => ['nullable','string'],
        ]);
        $note->update($data);
        ActivityLog::log('note.updated', "Updated note #{$note->id}", auth()->id());
        return redirect()->route('learning.notes.index')->with('status', 'Note updated.');
    }

    public function destroy(Note $note)
    {
        $id = $note->id; $title = $note->title;
        $note->delete();
        ActivityLog::log('note.deleted', "Deleted note #{$id}: {$title}", auth()->id());
        return redirect()->route('learning.notes.index')->with('status', 'Note deleted.');
    }

    // Taxonomy endpoints for dependent selects (fetch real data from DB)
    public function levels(Request $request)
    {
        $levels = Level::query()->orderBy('name')->get(['id','name']);
        return response()->json($levels);
    }

    public function subjects(Request $request)
    {
        // Fetch subjects through the selected class (primary + additional via pivot)
        $classId = (int) $request->get('class_id');
        if (!$classId) {
            return response()->json([]);
        }
        $class = SchoolClass::with(['subjects:id'])->find($classId);
        if (!$class) {
            return response()->json([]);
        }
        $ids = [];
        if ($class->subject_id) { $ids[] = (int) $class->subject_id; }
        $pivotIds = $class->subjects->pluck('id')->map(fn($v)=>(int)$v)->all();
        $ids = array_values(array_unique(array_merge($ids, $pivotIds)));
        if (empty($ids)) {
            return response()->json([]);
        }
        $subjects = Subject::whereIn('id', $ids)->orderBy('name')->get(['id','name']);
        return response()->json($subjects);
    }

    public function classes(Request $request)
    {
        // Fetch classes filtered by selected level
        $levelId = (int) $request->get('level_id');
        if (!$levelId) {
            return response()->json([]);
        }
        $classes = SchoolClass::where('level_id', $levelId)
            ->orderBy('name')
            ->get(['id','name']);
        return response()->json($classes);
    }
}
