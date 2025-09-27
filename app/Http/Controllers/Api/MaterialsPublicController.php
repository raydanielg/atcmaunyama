<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Subcategory; // Material Type
use App\Models\SubSubcategory; // Material Sub Type
use App\Models\Level;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;

class MaterialsPublicController extends Controller
{
    // GET /public/materials/types
    public function types()
    {
        $types = Subcategory::where('name', '!=', 'Unassigned')->orderBy('name')->get(['id','name']);
        return response()->json($types);
    }

    // GET /public/materials/subtypes?type_id=
    public function subtypes(Request $request)
    {
        $data = $request->validate([
            'type_id' => 'required|integer|exists:subcategories,id',
        ]);
        $rows = SubSubcategory::where('subcategory_id', $data['type_id'])
            ->orderBy('name')
            ->get(['id','name','subcategory_id']);
        return response()->json($rows);
    }

    // GET /public/materials/levels?type_id=&subtype_id?
    public function levels(Request $request)
    {
        $typeId = (int) $request->get('type_id', 0);
        $subtypeId = (int) $request->get('subtype_id', 0);
        // If a type is provided, return levels that have at least one material of that type (and subtype if provided)
        if ($typeId) {
            $levels = Level::query()
                ->select('levels.id','levels.name')
                ->join('school_classes', 'school_classes.level_id', '=', 'levels.id')
                ->join('materials', 'materials.class_id', '=', 'school_classes.id')
                ->where('materials.subcategory_id', $typeId)
                ->when($subtypeId > 0, function($q) use ($subtypeId){
                    $q->where('materials.sub_subcategory_id', $subtypeId);
                })
                ->distinct()
                ->orderBy('levels.name')
                ->get();
            return response()->json($levels);
        }
        // Else return all levels
        return response()->json(Level::orderBy('name')->get(['id','name']));
    }

    // GET /public/materials/classes?level_id=
    public function classes(Request $request)
    {
        $data = $request->validate([
            'level_id' => 'required|integer|exists:levels,id',
        ]);
        $rows = SchoolClass::where('level_id', $data['level_id'])->orderBy('name')->get(['id','name','level_id']);
        return response()->json($rows);
    }

    // GET /public/materials/subjects?class_id=&type_id?&subtype_id?
    public function subjects(Request $request)
    {
        $data = $request->validate([
            'class_id' => 'required|integer|exists:school_classes,id',
            'type_id' => 'nullable|integer|exists:subcategories,id',
            'subtype_id' => 'nullable|integer|exists:sub_subcategories,id',
        ]);
        $q = Subject::query()
            ->select('subjects.id','subjects.name')
            ->join('materials','materials.subject_id','=','subjects.id')
            ->where('materials.class_id', $data['class_id']);
        if (!empty($data['type_id'])) {
            $q->where('materials.subcategory_id', $data['type_id']);
        }
        if (!empty($data['subtype_id'])) {
            $q->where('materials.sub_subcategory_id', $data['subtype_id']);
        }
        $subjects = $q->distinct()->orderBy('subjects.name')->get();
        return response()->json($subjects);
    }

    // GET /public/materials
    // Query: type_id, class_id, subject_id, subtype_id?, q?
    public function materials(Request $request)
    {
        $data = $request->validate([
            'type_id' => 'required|integer|exists:subcategories,id',
            'class_id' => 'required|integer|exists:school_classes,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'subtype_id' => 'nullable|integer|exists:sub_subcategories,id',
            'q' => 'nullable|string|max:200',
        ]);

        $query = Material::query()
            ->with(['subcategory:id,name', 'class:id,name', 'subject:id,name'])
            ->where('subcategory_id', $data['type_id'])
            ->where('class_id', $data['class_id'])
            ->where('subject_id', $data['subject_id'])
            ->when(!empty($data['subtype_id']), function($q) use ($data){
                $q->where('sub_subcategory_id', $data['subtype_id']);
            })
            ->orderByDesc('id');

        if (!empty($data['q'])) {
            $qstr = $data['q'];
            $query->where('title', 'like', "%$qstr%");
        }

        $rows = $query->limit(50)->get(['id','title','slug','url','subcategory_id','class_id','subject_id']);

        $out = $rows->map(function($m){
            return [
                'id' => $m->id,
                'title' => $m->title,
                'type' => $m->subcategory->name ?? null,
                'class' => $m->class->name ?? null,
                'subject' => $m->subject->name ?? null,
                'url' => $m->slug ? route('materials.download', ['slug' => $m->slug]) : route('materials.preview', $m),
            ];
        });

        return response()->json(['data' => $out]);
    }
}
