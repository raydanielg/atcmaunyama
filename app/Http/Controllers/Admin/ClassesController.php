<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;

class ClassesController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q', ''));
        $classes = SchoolClass::with(['subject','subjects'])
            ->when($q !== '', function($query) use ($q) {
                $query->where('name', 'like', "%$q%");
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $subjects = Subject::orderBy('name')->get(['id','name']);

        return view('admin.learning.classes.index', compact('classes', 'subjects', 'q'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'subject_id' => 'required|exists:subjects,id',
            'description' => 'nullable|string|max:1000',
        ]);
        SchoolClass::create($data);
        return redirect()->route('learning.classes.index')->with('status', 'Class added');
    }

    public function update(Request $request, SchoolClass $class)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'subject_id' => 'required|exists:subjects,id',
            'description' => 'nullable|string|max:1000',
        ]);
        $class->update($data);
        return redirect()->route('learning.classes.index')->with('status', 'Class updated');
    }

    public function destroy(SchoolClass $class)
    {
        $class->delete();
        return redirect()->route('learning.classes.index')->with('status', 'Class deleted');
    }

    public function suggest(Request $request)
    {
        $q = trim((string)$request->get('q', ''));
        if ($q === '') return response()->json([]);
        $names = SchoolClass::where('name', 'like', "%$q%")
            ->orderBy('name')
            ->limit(8)
            ->pluck('name');
        return response()->json($names);
    }

    /**
     * Sync additional subjects to a class.
     */
    public function syncSubjects(Request $request, SchoolClass $class)
    {
        $data = $request->validate([
            'subject_ids' => ['array'],
            'subject_ids.*' => ['integer','exists:subjects,id'],
        ]);
        $ids = array_values($data['subject_ids'] ?? []);
        $class->subjects()->sync($ids);
        return redirect()->route('learning.classes.index')->with('status', 'Subjects updated for class');
    }
}
