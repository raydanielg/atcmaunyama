<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Support\ActivityLog;
use Illuminate\Http\Request;

class SubjectsController extends Controller
{
    public function index(Request $request)
    {
        $q = (string) $request->get('q');
        $subjects = Subject::query()
            ->with(['classes:id,name'])
            ->when($q, fn($query) => $query->where('name', 'like', "%{$q}%"))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.learning.subjects.index', compact('subjects', 'q'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);
        $subject = Subject::create($data);
        ActivityLog::log('subject.created', 'Created subject #'.$subject->id.': '.$subject->name, auth()->id());
        return redirect()->route('learning.subjects.index')->with('status', 'Subject created.');
    }

    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);
        $subject->update($data);
        ActivityLog::log('subject.updated', 'Updated subject #'.$subject->id.': '.$subject->name, auth()->id());
        return redirect()->route('learning.subjects.index')->with('status', 'Subject updated.');
    }

    public function destroy(Subject $subject)
    {
        $id = $subject->id; $name = $subject->name;
        $subject->delete();
        ActivityLog::log('subject.deleted', "Deleted subject #{$id}: {$name}", auth()->id());
        return redirect()->route('learning.subjects.index')->with('status', 'Subject deleted.');
    }

    public function suggest(Request $request)
    {
        $q = (string) $request->get('q');
        if ($q === '') {
            return response()->json([]);
        }
        $items = Subject::query()
            ->where('name', 'like', "%{$q}%")
            ->orderBy('name')
            ->limit(8)
            ->pluck('name');
        return response()->json($items);
    }

    /**
     * Return all classes and selected class IDs for a subject (JSON), to populate the Assign Classes modal.
     */
    public function classesJson(Subject $subject)
    {
        $classes = SchoolClass::orderBy('name')->get(['id','name']);
        $selected = $subject->classes()->pluck('school_classes.id')->all();
        return response()->json([
            'classes' => $classes,
            'selected' => $selected,
        ]);
    }

    /**
     * Sync assigned classes for a subject.
     */
    public function syncClasses(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'class_ids' => ['array'],
            'class_ids.*' => ['integer','exists:school_classes,id'],
        ]);
        $ids = array_values($data['class_ids'] ?? []);
        $subject->classes()->sync($ids);
        ActivityLog::log('subject.classes.synced', 'Updated classes for subject #'.$subject->id, auth()->id());
        return redirect()->route('learning.subjects.index')->with('status', 'Classes updated for subject.');
    }
}
