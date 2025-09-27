<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubSubcategory;
use App\Models\Subcategory;
use App\Models\Material;
use Illuminate\Http\Request;

class SubSubcategoriesController extends Controller
{
    /**
     * Display the Sub Sub Categories management page (placeholder for now).
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        // 1) Paginate distinct subject names
        $names = SubSubcategory::select('name')
            ->when($q !== '', function ($query) use ($q) {
                $query->where('name', 'like', "%$q%");
            })
            ->groupBy('name')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        // 2) Load all rows for the names on this page, with relations
        $pageNames = $names->getCollection()->pluck('name')->all();
        $allForPage = SubSubcategory::with(['subcategory'])
            ->whereIn('name', $pageNames)
            ->get();

        // 3) Group by name and collect distinct types and levels
        $grouped = [];
        foreach ($allForPage as $row) {
            $key = (string) $row->name;
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'name' => $row->name,
                    'types' => [], // distinct type names
                    'levels' => [], // distinct level names
                ];
            }
            if ($row->subcategory && $row->subcategory->name && $row->subcategory->name !== 'Unassigned') {
                $grouped[$key]['types'][$row->subcategory->name] = true;
            }
            // Levels display removed (categories module deprecated)
        }

        // Normalize to arrays of strings sorted
        $groupedSubjects = array_map(function ($g) {
            $g['types'] = array_keys($g['types']);
            sort($g['types']);
            $g['levels'] = array_keys($g['levels']);
            sort($g['levels']);
            return $g;
        }, $grouped);
        // Preserve ordering by the paginated names
        $ordered = [];
        foreach ($pageNames as $n) {
            if (isset($groupedSubjects[$n])) $ordered[] = $groupedSubjects[$n];
            else $ordered[] = ['name' => $n, 'types' => [], 'levels' => []];
        }

        return view('admin.materials.subsubcategories.index', [
            'namesPage' => $names,
            'groupedSubjects' => $ordered,
            'q' => $q,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'subcategory_id' => 'required|exists:subcategories,id',
        ]);

        SubSubcategory::create([
            'name' => $data['name'],
            'subcategory_id' => $data['subcategory_id'],
        ]);
        return redirect()->route('materials.subsubcategories.index')->with('status', 'Material Sub Type added');
    }

    public function update(Request $request, SubSubcategory $subsubcategory)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'subcategory_id' => 'nullable|exists:subcategories,id',
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

    /**
     * Bulk update subject name across all rows with the given current name.
     */
    public function updateByName(Request $request, string $name)
    {
        $data = $request->validate([
            'new_name' => 'required|string|max:150',
        ]);
        SubSubcategory::where('name', $name)->update(['name' => $data['new_name']]);
        if ($request->wantsJson()) return response()->json(['success' => true]);
        return redirect()->route('materials.subsubcategories.index')->with('status', 'Subject name updated');
    }

    /**
     * Bulk delete: remove all subject rows with this name.
     */
    public function destroyByName(Request $request, string $name)
    {
        SubSubcategory::where('name', $name)->delete();
        if ($request->wantsJson()) return response()->json(['success' => true]);
        return redirect()->route('materials.subsubcategories.index')->with('status', 'Subject deleted');
    }

    /**
     * Unassign all materials from a subject (reset assignments).
     */
    public function resetMaterials(SubSubcategory $subsubcategory)
    {
        // Set sub_subcategory_id to null for all materials assigned to this subject
        Material::where('sub_subcategory_id', $subsubcategory->id)->update(['sub_subcategory_id' => null]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('status', 'All materials have been unassigned from this subject');
    }
}
