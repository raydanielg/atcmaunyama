<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    protected function isPremium(Request $request): bool
    {
        // Temporary gating: accept header or query param. To be replaced by real auth/payment check.
        $flag = $request->header('X-Premium', $request->query('premium'));
        if (is_string($flag)) {
            $flag = strtolower($flag);
            return in_array($flag, ['1','true','yes','on'], true);
        }
        return (bool) $flag;
    }

    public function levels(Request $request)
    {
        $levels = Level::query()
            ->orderBy('name')
            ->get(['id','name','description','icon']);

        return response()->json(['data' => $levels]);
    }

    public function subjects(Request $request)
    {
        $q = Subject::query();
        if ($search = $request->query('q')) {
            $q->where('name', 'like', "%{$search}%");
        }
        $subjects = $q->orderBy('name')->paginate(20, ['id','name','description','icon']);
        return response()->json([
            'data' => $subjects->items(),
            'pagination' => [
                'current_page' => $subjects->currentPage(),
                'last_page' => $subjects->lastPage(),
                'per_page' => $subjects->perPage(),
                'total' => $subjects->total(),
            ],
        ]);
    }

    public function classes(Request $request)
    {
        $q = SchoolClass::query()->with('subject:id,name');
        if ($sid = $request->query('subject_id')) {
            $q->where('subject_id', $sid);
        }
        if ($lid = $request->query('level_id')) {
            $q->where('level_id', $lid);
        }
        $classes = $q->orderBy('name')->paginate(20, ['id','name','subject_id','level_id','description']);
        $data = collect($classes->items())->map(function ($c) {
            return [
                'id' => $c->id,
                'name' => $c->name,
                'subject_id' => $c->subject_id,
                'level_id' => $c->level_id,
                'description' => $c->description,
                'subject' => $c->subject ? [ 'id' => $c->subject->id, 'name' => $c->subject->name ] : null,
            ];
        });
        return response()->json([
            'data' => $data,
            'pagination' => [
                'current_page' => $classes->currentPage(),
                'last_page' => $classes->lastPage(),
                'per_page' => $classes->perPage(),
                'total' => $classes->total(),
            ],
        ]);
    }

    public function notes(Request $request)
    {
        $q = Note::query();
        if ($sid = $request->query('subject_id')) { $q->where('subject_id', $sid); }
        if ($lid = $request->query('level_id')) { $q->where('level_id', $lid); }
        if ($cid = $request->query('class_id')) { $q->where('class_id', $cid); }
        if ($search = $request->query('q')) { $q->where('title','like',"%{$search}%"); }

        $notes = $q->orderByDesc('created_at')->paginate(20, ['id','title','subject_id','level_id','class_id','file_path','original_name','mime_type','file_size','created_at']);

        $premium = $this->isPremium($request);
        $data = collect($notes->items())->map(function ($n) use ($premium) {
            $url = $n->file_path ? Storage::url($n->file_path) : null;
            return [
                'id' => $n->id,
                'title' => $n->title,
                'subject_id' => $n->subject_id,
                'level_id' => $n->level_id,
                'class_id' => $n->class_id,
                'mime_type' => $n->mime_type,
                'file_size' => $n->file_size,
                'previewUrl' => $url, // client should render preview only for non-premium
                'canDownload' => $premium,
                'downloadUrl' => $premium ? route('api.notes.download', ['id' => $n->id]) : null,
                'created_at' => optional($n->created_at)->toIso8601String(),
            ];
        });

        return response()->json([
            'data' => $data,
            'pagination' => [
                'current_page' => $notes->currentPage(),
                'last_page' => $notes->lastPage(),
                'per_page' => $notes->perPage(),
                'total' => $notes->total(),
            ],
        ]);
    }

    public function note(Request $request, int $id)
    {
        $n = Note::query()->findOrFail($id);
        $premium = $this->isPremium($request);
        $url = $n->file_path ? Storage::url($n->file_path) : null;
        return response()->json([
            'id' => $n->id,
            'title' => $n->title,
            'subject_id' => $n->subject_id,
            'level_id' => $n->level_id,
            'class_id' => $n->class_id,
            'mime_type' => $n->mime_type,
            'file_size' => $n->file_size,
            'previewUrl' => $url,
            'canDownload' => $premium,
            'downloadUrl' => $premium ? route('api.notes.download', ['id' => $n->id]) : null,
            'created_at' => optional($n->created_at)->toIso8601String(),
        ]);
    }

    public function download(Request $request, int $id)
    {
        $premium = $this->isPremium($request);
        if (!$premium) {
            return response()->json(['message' => 'Premium required for downloads'], 403);
        }
        $n = Note::query()->findOrFail($id);
        if (!$n->file_path || !Storage::exists($n->file_path)) {
            return response()->json(['message' => 'File not found'], 404);
        }
        return Storage::download($n->file_path, $n->original_name);
    }
}
