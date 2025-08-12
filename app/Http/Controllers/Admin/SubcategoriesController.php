<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoriesController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $subcategories = Subcategory::with('category')
            ->when($q !== '', function ($query) use ($q) {
                $query->where('name', 'like', "%$q%");
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get(['id','name']);

        return view('admin.materials.subcategories.index', [
            'subcategories' => $subcategories,
            'categories' => $categories,
            'q' => $q,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'year' => 'nullable|integer|min:1900|max:2100',
            'icon' => 'nullable|string',
        ]);
        Subcategory::create($data);
        return redirect()->route('materials.subcategories.index')->with('status', 'Subcategory added');
    }

    public function update(Request $request, Subcategory $subcategory)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'year' => 'nullable|integer|min:1900|max:2100',
            'icon' => 'nullable|string',
        ]);
        $subcategory->update($data);
        return redirect()->route('materials.subcategories.index')->with('status', 'Subcategory updated');
    }

    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();
        return redirect()->route('materials.subcategories.index')->with('status', 'Subcategory deleted');
    }

    public function suggest(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        if ($q === '') return response()->json([]);
        $names = Subcategory::where('name', 'like', "%$q%")
            ->orderBy('name')
            ->limit(8)
            ->pluck('name');
        return response()->json($names);
    }
}
