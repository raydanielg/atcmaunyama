<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Subcategory;
use App\Models\SubSubcategory;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MaterialsController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $materials = Material::with(['subcategory','level','subject','class'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where('title', 'like', "%$q%");
            })
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('admin.materials.index', compact('materials', 'q'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:200',
            'subcategory_id' => 'required|exists:subcategories,id',
            'sub_subcategory_id' => 'nullable|exists:sub_subcategories,id',
            'level_id' => 'required|integer|exists:levels,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'class_id' => 'required|integer|exists:school_classes,id',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx|max:25600',
        ]);

        // If the chosen Material Type has sub types, force sub_subcategory_id to be present
        $hasSubTypes = SubSubcategory::where('subcategory_id', $data['subcategory_id'])->exists();
        if ($hasSubTypes && empty($data['sub_subcategory_id'])) {
            return back()
                ->withErrors(['sub_subcategory_id' => 'Please select a Material Sub Type for the chosen Material Type.'])
                ->withInput();
        }
        $data['user_id'] = Auth::id();
        // Store file and auto-fill path/mime/size and generated url via slug
        if ($request->hasFile('file')) {
            $storedPath = $request->file('file')->store('materials', 'public');
            $data['path'] = $storedPath;
            $data['mime'] = $request->file('file')->getClientMimeType();
            $data['size'] = $request->file('file')->getSize();
            // Generate unique slug and set public download route as URL
            $base = Str::slug($data['title']);
            $slug = $base;
            $i = 1;
            while (Material::where('slug', $slug)->exists()) {
                $slug = $base.'-'.(++$i);
            }
            $data['slug'] = $slug;
            $data['url'] = route('materials.download', ['slug' => $slug]);
        }
        Material::create($data);
        return redirect()->route('materials.index')->with('status', 'Material added');
    }

    public function update(Request $request, Material $material)
    {
        $data = $request->validate([
            'title' => 'required|string|max:200',
            'subcategory_id' => 'required|exists:subcategories,id',
            'sub_subcategory_id' => 'nullable|exists:sub_subcategories,id',
            'level_id' => 'required|integer|exists:levels,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'class_id' => 'required|integer|exists:school_classes,id',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx|max:25600',
        ]);

        // If the chosen Material Type has sub types, force sub_subcategory_id to be present
        $hasSubTypes = SubSubcategory::where('subcategory_id', $data['subcategory_id'])->exists();
        if ($hasSubTypes && empty($data['sub_subcategory_id'])) {
            return back()
                ->withErrors(['sub_subcategory_id' => 'Please select a Material Sub Type for the chosen Material Type.'])
                ->withInput();
        }
        if ($request->hasFile('file')) {
            $storedPath = $request->file('file')->store('materials', 'public');
            $data['path'] = $storedPath;
            $data['mime'] = $request->file('file')->getClientMimeType();
            $data['size'] = $request->file('file')->getSize();
            // Keep existing slug if present, otherwise create
            $slug = $material->slug;
            if (!$slug) {
                $base = Str::slug($data['title']);
                $slug = $base;
                $i = 1;
                while (Material::where('slug', $slug)->exists()) {
                    $slug = $base.'-'.(++$i);
                }
            }
            $data['slug'] = $slug;
            $data['url'] = route('materials.download', ['slug' => $slug]);
        }
        $material->update($data);
        return redirect()->route('materials.index')->with('status', 'Material updated');
    }

    public function destroy(Material $material)
    {
        $material->delete();
        return redirect()->route('materials.index')->with('status', 'Material deleted');
    }

    public function suggest(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        if ($q === '') return response()->json([]);
        $titles = Material::where('title', 'like', "%$q%")
            ->orderBy('title')
            ->limit(8)
            ->pluck('title');
        return response()->json($titles);
    }

    // JSON helper for Material Types (Subcategories)
    public function subcategories(Request $request)
    {
        $rows = Subcategory::query()
            ->where('name', '!=', 'Unassigned')
            ->orderBy('name')
            ->get(['id','name']);
        return response()->json($rows);
    }

    /**
     * JSON helper for Material Sub Types by Material Type
     */
    public function subsubcategoriesByType(Request $request)
    {
        $subcategoryId = (int) $request->query('subcategory_id', 0);
        if (!$subcategoryId) return response()->json([]);
        $rows = SubSubcategory::query()
            ->where('subcategory_id', $subcategoryId)
            ->orderBy('name')
            ->get(['id','name']);
        return response()->json($rows);
    }

    // Public download by slug
    public function download(string $slug)
    {
        $mat = Material::where('slug', $slug)->firstOrFail();
        if (!$mat->path) {
            // If no stored file path, redirect to external URL if set
            if ($mat->url) return redirect($mat->url);
            abort(404);
        }
        $disk = Storage::disk('public');
        if (!$disk->exists($mat->path)) abort(404);
        // Use original title as suggested download name
        $filename = Str::slug(pathinfo($mat->title, PATHINFO_FILENAME));
        $ext = pathinfo($mat->path, PATHINFO_EXTENSION);
        $downloadName = $ext ? ($filename.'.'.$ext) : $filename;
        return $disk->download($mat->path, $downloadName);
    }

    // Inline preview (for admin UI)
    public function preview(Material $material)
    {
        // If we have a stored file, stream it inline
        if ($material->path) {
            $disk = Storage::disk('public');
            if (!$disk->exists($material->path)) abort(404);

            $filename = Str::slug(pathinfo($material->title, PATHINFO_FILENAME));
            $ext = pathinfo($material->path, PATHINFO_EXTENSION);
            $name = $ext ? ($filename.'.'.$ext) : $filename;

            // Return as inline response to allow browser preview where supported (e.g., PDFs)
            return $disk->response($material->path, $name);
        }

        // Fallback: if external URL is set, redirect (iframe may or may not allow embedding)
        if ($material->url) {
            return redirect($material->url);
        }

        abort(404);
    }
}
