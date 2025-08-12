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
        $categories = Category::when($q !== '', function ($query) use ($q) {
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
}
