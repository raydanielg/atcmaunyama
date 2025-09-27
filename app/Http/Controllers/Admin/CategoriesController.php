<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $categories = Category::with('types')
            ->where('name', '!=', 'Unassigned')
            ->when($q !== '', function ($query) use ($q) {
                $query->where('name', 'like', "%$q%");
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('admin.materials.categories.index', [
            'categories' => $categories,
            'q' => $q,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150|unique:categories,name',
            'icon' => 'nullable|string', // raw SVG markup allowed
        ]);
        Category::create($data);
        return redirect()->route('materials.categories.index')->with('status', 'Category added');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150|unique:categories,name,' . $category->id,
            'icon' => 'nullable|string',
        ]);
        $category->update($data);
        return redirect()->route('materials.categories.index')->with('status', 'Category updated');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('materials.categories.index')->with('status', 'Category deleted');
    }

    public function suggest(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        if ($q === '') return response()->json([]);
        $names = Category::where('name', 'like', "%$q%")
            ->orderBy('name')
            ->limit(8)
            ->pluck('name');
        return response()->json($names);
    }

    /**
     * List all Material Types with assignment status for a given Level (Category).
     */
    public function typesJson(Category $category)
    {
        $assignedIds = $category->types()->pluck('subcategories.id')->all();
        $types = \App\Models\Subcategory::orderBy('name')->get(['id','name','category_id'])
            ->map(function($t) use ($assignedIds){
                return [
                    'id' => $t->id,
                    'name' => $t->name,
                    'assigned' => in_array($t->id, $assignedIds, true),
                ];
            });
        return response()->json($types);
    }

    /**
     * Assign selected Material Types to the given Level.
     * This will set category_id on the selected types to the current category.
     * It will NOT unassign other types from this category.
     */
    public function typesSync(Request $request, Category $category)
    {
        $data = $request->validate([
            'type_ids' => 'array',
            'type_ids.*' => 'integer|exists:subcategories,id',
        ]);

        $selected = collect($data['type_ids'] ?? [])->filter()->unique()->values();
        $selectedIds = $selected->all();

        // Sync pivot for this category only (attach selected, detach others for this category)
        $category->types()->sync($selectedIds);

        return response()->json(['success' => true]);
    }
}
