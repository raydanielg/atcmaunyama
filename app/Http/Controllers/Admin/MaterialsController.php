<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Material;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MaterialsController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $materials = Material::with(['category', 'subcategory'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where('title', 'like', "%$q%");
            })
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get(['id', 'name']);

        return view('admin.materials.index', compact('materials', 'categories', 'q'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'url' => 'nullable|required_without:file|url|max:2048',
            'file' => 'nullable|required_without:url|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx|max:25600',
        ]);
        $data['user_id'] = Auth::id();
        // If a file is uploaded, store it and auto-fill URL/path/mime/size
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
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'url' => 'nullable|required_without:file|url|max:2048',
            'file' => 'nullable|required_without:url|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx|max:25600',
        ]);
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

    public function subcategories(Request $request)
    {
        $categoryId = $request->get('category_id');
        if (!$categoryId) return response()->json([]);
        $subs = Subcategory::where('category_id', $categoryId)
            ->orderBy('name')
            ->get(['id','name']);
        return response()->json($subs);
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
}
