<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoriesController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $subcategories = Subcategory::query()
            // Hide only the placeholder type itself
            ->where('name', '!=', 'Unassigned')
            ->when($q !== '', function ($query) use ($q) {
                $query->where('name', 'like', "%$q%");
            })
            ->latest('created_at')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('admin.materials.subcategories.index', [
            'subcategories' => $subcategories,
            'q' => $q,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
        ]);
        // Disallow reserved name
        if (strcasecmp(trim($data['name']), 'Unassigned') === 0) {
            return redirect()->back()->withErrors(['name' => 'This name is reserved. Please choose a different type name.'])->withInput();
        }
        Subcategory::create([
            'name' => $data['name'],
        ]);
        return redirect()->route('materials.subcategories.index')->with('status', 'Subcategory added');
    }

    public function update(Request $request, Subcategory $subcategory)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
        ]);
        if (strcasecmp(trim($data['name']), 'Unassigned') === 0) {
            return redirect()->back()->withErrors(['name' => 'This name is reserved. Please choose a different type name.'])->withInput();
        }
        $subcategory->update($data);
        return redirect()->route('materials.subcategories.index')->with('status', 'Subcategory updated');
    }

    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();
        return redirect()->route('materials.subcategories.index')->with('status', 'Subcategory deleted');
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:subcategories,id',
        ]);
        $ids = collect($data['ids'])->unique()->values();
        if ($ids->isEmpty()) {
            return redirect()->route('materials.subcategories.index')->with('status', 'No types selected');
        }
        Subcategory::whereIn('id', $ids)->delete();
        return redirect()->route('materials.subcategories.index')->with('status', 'Deleted '.count($ids).' type(s)');
    }

    public function suggest(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        if ($q === '') return response()->json([]);
        $names = Subcategory::where('name', '!=', 'Unassigned')
            ->where('name', 'like', "%$q%")
            ->orderBy('name')
            ->limit(8)
            ->pluck('name');
        return response()->json($names);
    }

    // Subject assignment endpoints removed as Material Subjects module is deprecated
}
