<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Semister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SemisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $semisters = Semister::orderBy('start_date', 'desc')->get();
        return view('admin.semisters.index', compact('semisters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.semisters.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Auto-fill sensible defaults so user only types the name
        $today = now()->startOfDay();
        $defaultEnd = (clone $today)->addMonths(4); // 4 months as a default window

        Semister::create([
            'name' => $validated['name'],
            'start_date' => $today->toDateString(),
            'end_date' => $defaultEnd->toDateString(),
            'is_active' => true,
            'description' => null,
        ]);

        return redirect()->route('admin.semisters.index')
            ->with('success', 'Semester created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Semister $semister)
    {
        // Get notes for this semester
        $notes = $semister->notes()->with(['level', 'subject', 'class'])->get();

        return view('admin.semisters.show', compact('semister', 'notes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Semister $semister)
    {
        return view('admin.semisters.edit', compact('semister'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Semister $semister)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
            'description' => 'nullable|string'
        ]);

        $semister->update($validated);

        return redirect()->route('admin.semisters.index')
            ->with('success', 'Semester updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Semister $semister)
    {
        // Check if semester has notes
        if ($semister->notes()->count() > 0) {
            return redirect()->route('admin.semisters.index')
                ->with('error', 'Cannot delete semester that has notes associated with it.');
        }

        $semister->delete();

        return redirect()->route('admin.semisters.index')
            ->with('success', 'Semester deleted successfully.');
    }

    /**
     * Toggle semester active status
     */
    public function toggleStatus(Semister $semister)
    {
        $semister->update(['is_active' => !$semister->is_active]);

        return redirect()->route('admin.semisters.index')
            ->with('success', 'Semester status updated successfully.');
    }
}
