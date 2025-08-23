<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubSubcategory;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubSubcategoriesController extends Controller
{
    /**
     * Display the Sub Sub Categories management page (placeholder for now).
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $items = SubSubcategory::with('subcategory')
            ->when($q !== '', function ($query) use ($q) {
                $query->where('name', 'like', "%$q%");
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('admin.materials.subsubcategories.index', [
            'subsubcategories' => $items,
            'q' => $q,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'subcategory_id' => 'required|exists:subcategories,id',
            'year' => 'nullable|integer|min:1900|max:2100',
            'icon' => 'nullable|string',
        ]);
        SubSubcategory::create($data);
        return redirect()->route('materials.subsubcategories.index')->with('status', 'Sub Sub Category added');
    }

    public function update(Request $request, SubSubcategory $subsubcategory)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'subcategory_id' => 'required|exists:subcategories,id',
            'year' => 'nullable|integer|min:1900|max:2100',
            'icon' => 'nullable|string',
        ]);
        $subsubcategory->update($data);
        return redirect()->route('materials.subsubcategories.index')->with('status', 'Sub Sub Category updated');
    }

    public function destroy(SubSubcategory $subsubcategory)
    {
        $subsubcategory->delete();
        return redirect()->route('materials.subsubcategories.index')->with('status', 'Sub Sub Category deleted');
    }
}
