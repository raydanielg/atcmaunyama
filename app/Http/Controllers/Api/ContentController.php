<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\Note;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\SubSubcategory;
use App\Models\Material;
use App\Models\Semister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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

    public function semisters(Request $request)
    {
        $items = Semister::query()
            ->where('is_active', true)
            ->orderByDesc('start_date')
            ->get(['id','name','start_date','end_date']);
        return response()->json(['data' => $items]);
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

    public function categories(Request $request)
    {
        $q = Category::query();
        if ($search = $request->query('q')) {
            $q->where('name', 'like', "%{$search}%");
        }
        $categories = $q->orderBy('name')->paginate(50, ['id','name','icon']);
        return response()->json([
            'data' => $categories->items(),
            'pagination' => [
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total(),
            ],
        ]);
    }

    public function subcategories(Request $request)
    {
        $q = Subcategory::query()->with('category:id,name');
        if ($cid = $request->query('category_id')) { $q->where('category_id', $cid); }
        if (($year = $request->query('year')) !== null && $year !== '') { $q->where('year', $year); }
        if ($search = $request->query('q')) { $q->where('name','like',"%{$search}%"); }

        $subcats = $q->orderBy('name')->paginate(50, ['id','name','category_id','year','icon']);
        $data = collect($subcats->items())->map(function ($s) {
            return [
                'id' => $s->id,
                'name' => $s->name,
                'category_id' => $s->category_id,
                'year' => $s->year,
                'icon' => $s->icon,
                'category' => $s->category ? ['id' => $s->category->id, 'name' => $s->category->name] : null,
            ];
        });

        return response()->json([
            'data' => $data,
            'pagination' => [
                'current_page' => $subcats->currentPage(),
                'last_page' => $subcats->lastPage(),
                'per_page' => $subcats->perPage(),
                'total' => $subcats->total(),
            ],
        ]);
    }

    public function subsubcategories(Request $request)
    {
        $q = SubSubcategory::query()->with('subcategory:id,name');
        
        // Filter by subcategory_id if provided
        if ($sid = $request->query('subcategory_id')) { 
            $q->where('subcategory_id', $sid); 
        }
        
        // Filter by category_id if provided (through subcategory relationship)
        if ($cid = $request->query('category_id')) { 
            $q->whereHas('subcategory', function($query) use ($cid) {
                $query->where('category_id', $cid);
            });
        }
        
        // Filter by year if provided
        if (($year = $request->query('year')) !== null && $year !== '') { 
            $q->where('year', $year); 
        }
        
        // Search by name if provided
        if ($search = $request->query('q')) { 
            $q->where('name','like',"%{$search}%"); 
        }

        // Get paginated results
        $subsubcats = $q->orderBy('name')->paginate(50, ['id','name','subcategory_id','year','icon']);
        
        // Transform the data to include related subcategory information
        $data = collect($subsubcats->items())->map(function ($ssc) {
            return [
                'id' => $ssc->id,
                'name' => $ssc->name,
                'subcategory_id' => $ssc->subcategory_id,
                'year' => $ssc->year,
                'icon' => $ssc->icon,
                'subcategory' => $ssc->subcategory ? [
                    'id' => $ssc->subcategory->id, 
                    'name' => $ssc->subcategory->name
                ] : null,
            ];
        });

        return response()->json([
            'data' => $data,
            'pagination' => [
                'current_page' => $subsubcats->currentPage(),
                'last_page' => $subsubcats->lastPage(),
                'per_page' => $subsubcats->perPage(),
                'total' => $subsubcats->total(),
            ],
        ]);
    }

    public function materials(Request $request)
    {
        $q = Material::query()->with(['category:id,name', 'subcategory:id,name']);
        if ($cid = $request->query('category_id')) { $q->where('category_id', $cid); }
        if ($sid = $request->query('subcategory_id')) { $q->where('subcategory_id', $sid); }
        if ($search = $request->query('q')) { $q->where('title','like',"%{$search}%"); }

        $materials = $q->orderByDesc('id')->paginate(20, ['id','title','slug','category_id','subcategory_id','path','url','mime','size']);

        $data = collect($materials->items())->map(function ($m) {
            // Prefer stored URL if available, fallback to Storage disk path if 'path' is set
            $previewUrl = $m->url ?: ($m->path ? Storage::url($m->path) : null);
            return [
                'id' => $m->id,
                'title' => $m->title,
                'slug' => $m->slug,
                'category_id' => $m->category_id,
                'subcategory_id' => $m->subcategory_id,
                'mime' => $m->mime,
                'size' => $m->size,
                'previewUrl' => $previewUrl,
                'category' => $m->category ? ['id' => $m->category->id, 'name' => $m->category->name] : null,
                'subcategory' => $m->subcategory ? ['id' => $m->subcategory->id, 'name' => $m->subcategory->name] : null,
            ];
        });

        return response()->json([
            'data' => $data,
            'pagination' => [
                'current_page' => $materials->currentPage(),
                'last_page' => $materials->lastPage(),
                'per_page' => $materials->perPage(),
                'total' => $materials->total(),
            ],
        ]);
    }

    public function notes(Request $request)
    {
        $q = Note::query();
        // Require a semister_id to be specified
        $sem = $request->query('semister_id');
        if (!$sem) {
            return response()->json([
                'message' => 'semister_id is required',
                'data' => [],
                'pagination' => ['current_page'=>1,'last_page'=>1,'per_page'=>20,'total'=>0],
            ], 422);
        }
        $q->where('semister_id', $sem);
        if ($sid = $request->query('subject_id')) { $q->where('subject_id', $sid); }
        if ($lid = $request->query('level_id')) { $q->where('level_id', $lid); }
        if ($cid = $request->query('class_id')) { $q->where('class_id', $cid); }
        if ($search = $request->query('q')) { $q->where('title','like',"%{$search}%"); }

        $notes = $q->orderByDesc('created_at')->paginate(20, ['id','title','subject_id','level_id','class_id','semister_id','file_path','original_name','mime_type','file_size','created_at']);

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
                'previewUrl' => route('api.notes.preview', ['id' => $n->id]), // public inline preview
                'canDownload' => $premium,
                'downloadUrl' => $premium ? route('api.notes.download', ['id' => $n->id]) : null,
                'semister_id' => $n->semister_id,
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
            'previewUrl' => route('api.notes.preview', ['id' => $n->id]),
            'canDownload' => $premium,
            'downloadUrl' => $premium ? route('api.notes.download', ['id' => $n->id]) : null,
            'created_at' => optional($n->created_at)->toIso8601String(),
        ]);
    }

    /**
     * Public inline preview for a note file.
     * Streams the file without requiring premium/admin.
     */
    public function preview(Request $request, int $id)
    {
        $n = Note::query()->findOrFail($id);
        // Files are stored on the 'public' disk by Admin NotesController::store()
        $disk = Storage::disk('public');
        if (!$n->file_path || !$disk->exists($n->file_path)) {
            return response()->json(['message' => 'File not found'], 404);
        }
        // Stream inline with original filename
        return $disk->response(
            $n->file_path,
            $n->original_name,
            [
                'Content-Disposition' => 'inline; filename="' . $n->original_name . '"',
                // Best-effort protections (cannot fully prevent downloads/screenshots)
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'X-Content-Type-Options' => 'nosniff',
            ]
        );
    }

    public function download(Request $request, int $id)
    {
        $premium = $this->isPremium($request);
        if (!$premium) {
            return response()->json(['message' => 'Premium required for downloads'], 403);
        }
        $n = Note::query()->findOrFail($id);
        // Ensure we read from the same disk where uploads are saved ('public')
        $disk = Storage::disk('public');
        if (!$n->file_path || !$disk->exists($n->file_path)) {
            return response()->json(['message' => 'File not found'], 404);
        }
        return $disk->download($n->file_path, $n->original_name);
    }

    /**
     * Return subject(s) for a given class.
     * - primarySubject: from school_classes.subject_id relation
     * - extraSubjects: from pivot table school_class_subject (if any)
     */
    public function classSubject(Request $request, int $id)
    {
        $class = SchoolClass::query()
            ->with(['subject:id,name', 'subjects:id,name'])
            ->findOrFail($id, ['id','name','subject_id']);

        $primary = $class->subject ? [
            'id' => $class->subject->id,
            'name' => $class->subject->name,
        ] : null;

        $extras = $class->subjects->map(function ($s) {
            return [ 'id' => $s->id, 'name' => $s->name ];
        })->values();

        return response()->json([
            'class' => [ 'id' => $class->id, 'name' => $class->name ],
            'primarySubject' => $primary,
            'extraSubjects' => $extras,
        ]);
    }

    /**
     * Mobile flow helper: subjects available for a given class and semister, based on notes presence.
     * Params: class_id (required), semister_id (required), level_id (optional)
     * Returns distinct subjects that have at least one note matching filters.
     */
    public function subjectsForClassSemister(Request $request)
    {
        $classId = (int) $request->query('class_id');
        $semId = (int) $request->query('semister_id');
        $levelId = $request->query('level_id');
        if (!$classId || !$semId) {
            return response()->json([
                'message' => 'class_id and semister_id are required',
                'data' => []
            ], 422);
        }

        $q = Note::query()
            ->where('class_id', $classId)
            ->where('semister_id', $semId)
            ->when($levelId, fn($qq) => $qq->where('level_id', $levelId))
            ->whereNotNull('subject_id')
            ->join('subjects', 'subjects.id', '=', 'notes.subject_id')
            ->groupBy('subjects.id','subjects.name')
            ->orderBy('subjects.name')
            ->select([
                'subjects.id',
                'subjects.name',
                DB::raw('COUNT(notes.id) as notesCount')
            ]);

        $subjects = $q->get();
        return response()->json(['data' => $subjects]);
    }

    /**
     * Next-options helper for client flow.
     * Given partial selections, returns what the client should load next.
     * Params (all optional): level_id, class_id, semister_id
     * Response may contain: levels[], classes[], semisters[], subjects[] (subjects include notesCount)
     */
    public function nextOptions(Request $request)
    {
        $levelId = $request->query('level_id');
        $classId = $request->query('class_id');
        $semId = $request->query('semister_id');

        $out = [];

        if (!$levelId) {
            $out['levels'] = Level::query()->orderBy('name')->get(['id','name','description','icon']);
            return response()->json($out);
        }

        if ($levelId && !$classId) {
            $classes = SchoolClass::query()
                ->where('level_id', $levelId)
                ->orderBy('name')
                ->get(['id','name','subject_id','level_id','description']);
            $out['classes'] = $classes;
            return response()->json($out);
        }

        if ($levelId && $classId && !$semId) {
            $out['semisters'] = Semister::query()
                ->where('is_active', true)
                ->orderByDesc('start_date')
                ->get(['id','name','start_date','end_date']);
            return response()->json($out);
        }

        if ($levelId && $classId && $semId) {
            // Reuse logic from subjectsForClassSemister including counts
            $subjects = Note::query()
                ->where('class_id', $classId)
                ->where('semister_id', $semId)
                ->where('level_id', $levelId)
                ->whereNotNull('subject_id')
                ->join('subjects', 'subjects.id', '=', 'notes.subject_id')
                ->groupBy('subjects.id','subjects.name')
                ->orderBy('subjects.name')
                ->select([
                    'subjects.id',
                    'subjects.name',
                    DB::raw('COUNT(notes.id) as notesCount')
                ])->get();
            $out['subjects'] = $subjects;
            return response()->json($out);
        }

        return response()->json($out);
    }

    /**
     * PUBLIC MATERIALS BROWSING API
     */
    public function publicMaterialsTypes(Request $request)
    {
        $types = Subcategory::query()
            ->where('name', '!=', 'Unassigned')
            ->orderBy('name')
            ->get(['id','name']);
        return response()->json($types);
    }

    public function publicMaterialsLevels(Request $request, int $type_id)
    {
        $levels = DB::table('materials')
            ->join('levels','levels.id','=','materials.level_id')
            ->where('materials.subcategory_id', $type_id)
            ->whereNotNull('materials.level_id')
            ->select('levels.id','levels.name')
            ->distinct()
            ->orderBy('levels.name')
            ->get();
        return response()->json($levels);
    }

    public function publicMaterialsSubjects(Request $request, int $type_id, int $level_id)
    {
        $subjects = DB::table('materials')
            ->join('subjects','subjects.id','=','materials.subject_id')
            ->where('materials.subcategory_id', $type_id)
            ->where('materials.level_id', $level_id)
            ->whereNotNull('materials.subject_id')
            ->orderBy('subjects.name')
            ->distinct()
            ->pluck('subjects.name');
        return response()->json(array_values($subjects->toArray()));
    }

    public function publicMaterialsList(Request $request)
    {
        $typeId = $request->query('type_id');
        $levelId = $request->query('level_id');
        $subjectName = $request->query('subject_name');
        $q = trim((string)$request->query('q', ''));

        $rows = DB::table('materials')
            ->leftJoin('subcategories','subcategories.id','=','materials.subcategory_id')
            ->leftJoin('levels','levels.id','=','materials.level_id')
            ->leftJoin('subjects','subjects.id','=','materials.subject_id')
            ->when($typeId, fn($qq)=>$qq->where('materials.subcategory_id', $typeId))
            ->when($levelId, fn($qq)=>$qq->where('materials.level_id', $levelId))
            ->when($subjectName, fn($qq)=>$qq->where('subjects.name','like', "%$subjectName%"))
            ->when($q !== '', fn($qq)=>$qq->where('materials.title','like', "%$q%"))
            ->orderByDesc('materials.id')
            ->select([
                'materials.id',
                'materials.title',
                DB::raw("COALESCE(subcategories.name,'') as type_name"),
                DB::raw("COALESCE(levels.name,'') as level_name"),
                DB::raw("COALESCE(subjects.name,'') as subject_name"),
                'materials.slug',
                'materials.path',
            ])
            ->limit(200)
            ->get();

        $data = $rows->map(function($r){
            // Prefer public download route via slug; fallback to inline preview by id if no slug
            $url = null;
            if (!empty($r->slug)) {
                $url = route('materials.download', ['slug' => $r->slug]);
            } elseif (!empty($r->id)) {
                $url = route('materials.preview', ['material' => $r->id]);
            }
            return [
                'id' => $r->id,
                'title' => $r->title,
                'type' => $r->type_name,
                'level' => $r->level_name,
                'subject' => $r->subject_name,
                'url' => $url,
            ];
        });

        return response()->json($data);
    }
}
