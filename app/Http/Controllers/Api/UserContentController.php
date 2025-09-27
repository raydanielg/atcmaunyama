<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Material;
use App\Models\Level;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\Semister;
use App\Models\Subcategory;
use App\Models\SubSubcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserContentController extends Controller
{
    /**
     * Create a new note (for authenticated users)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeNote(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'body' => ['nullable', 'string'],
                'level_id' => ['nullable', 'integer', 'exists:levels,id'],
                'subject_id' => ['nullable', 'integer', 'exists:subjects,id'],
                'class_id' => ['nullable', 'integer', 'exists:school_classes,id'],
                'semister_id' => ['nullable', 'integer', 'exists:semisters,id'],
                'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,txt', 'max:20480'],
            ]);

            // Validate that user has access to the specified class/subject if provided
            if ($data['class_id'] && $data['subject_id']) {
                $class = SchoolClass::with(['subjects'])->find($data['class_id']);
                if (!$class) {
                    throw ValidationException::withMessages([
                        'class_id' => ['Selected class not found.']
                    ]);
                }

                // Check if the subject belongs to this class
                $subjectIds = collect([$class->subject_id])->filter()->merge($class->subjects->pluck('id'));
                if (!$subjectIds->contains($data['subject_id'])) {
                    throw ValidationException::withMessages([
                        'subject_id' => ['Selected subject does not belong to the selected class.']
                    ]);
                }
            }

            $filePath = null;
            $originalName = null;
            $mimeType = null;
            $fileSize = null;

            // Handle file upload if provided
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $originalName = $file->getClientOriginalName();
                $mimeType = $file->getClientMimeType();
                $fileSize = $file->getSize();
                $filePath = $file->store('user-notes', 'public');
            }

            // Create the note
            $note = Note::create([
                'title' => $data['title'],
                'body' => $data['body'] ?? null,
                'user_id' => auth()->id(),
                'level_id' => $data['level_id'] ?? null,
                'subject_id' => $data['subject_id'] ?? null,
                'class_id' => $data['class_id'] ?? null,
                'semister_id' => $data['semister_id'] ?? null,
                'file_path' => $filePath,
                'original_name' => $originalName,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Note created successfully',
                'data' => [
                    'id' => $note->id,
                    'title' => $note->title,
                    'created_at' => $note->created_at->toISOString(),
                    'file_attached' => !is_null($note->file_path),
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create note: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new material (for authenticated users)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeMaterial(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => 'required|string|max:200',
                'subcategory_id' => 'required|exists:subcategories,id',
                'sub_subcategory_id' => 'nullable|exists:sub_subcategories,id',
                'level_id' => 'required|integer|exists:levels,id',
                'subject_id' => 'required|integer|exists:subjects,id',
                'class_id' => 'required|integer|exists:school_classes,id',
                'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx|max:25600',
            ]);

            // Validate that user has access to the specified class/subject
            $class = SchoolClass::with(['subjects'])->find($data['class_id']);
            if (!$class) {
                throw ValidationException::withMessages([
                    'class_id' => ['Selected class not found.']
                ]);
            }

            // Check if the subject belongs to this class
            $subjectIds = collect([$class->subject_id])->filter()->merge($class->subjects->pluck('id'));
            if (!$subjectIds->contains($data['subject_id'])) {
                throw ValidationException::withMessages([
                    'subject_id' => ['Selected subject does not belong to the selected class.']
                ]);
            }

            // Validate material type and sub-type requirements
            $hasSubTypes = SubSubcategory::where('subcategory_id', $data['subcategory_id'])->exists();
            if ($hasSubTypes && empty($data['sub_subcategory_id'])) {
                throw ValidationException::withMessages([
                    'sub_subcategory_id' => ['Please select a Material Sub Type for the chosen Material Type.']
                ]);
            }

            // Store file and generate metadata
            if ($request->hasFile('file')) {
                $storedPath = $request->file('file')->store('user-materials', 'public');
                $data['path'] = $storedPath;
                $data['mime'] = $request->file('file')->getClientMimeType();
                $data['size'] = $request->file('file')->getSize();
                $data['user_id'] = auth()->id();

                // Generate unique slug for public access
                $base = Str::slug($data['title']);
                $slug = $base;
                $i = 1;
                while (Material::where('slug', $slug)->exists()) {
                    $slug = $base.'-'.(++$i);
                }
                $data['slug'] = $slug;
                $data['url'] = route('materials.download', ['slug' => $slug]);
            }

            $material = Material::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Material uploaded successfully',
                'data' => [
                    'id' => $material->id,
                    'title' => $material->title,
                    'slug' => $material->slug,
                    'download_url' => $material->url,
                    'created_at' => $material->created_at->toISOString(),
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload material: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's own notes
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function myNotes(Request $request)
    {
        $query = Note::where('user_id', auth()->id());

        if ($search = $request->query('q')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($levelId = $request->query('level_id')) {
            $query->where('level_id', $levelId);
        }

        if ($classId = $request->query('class_id')) {
            $query->where('class_id', $classId);
        }

        if ($subjectId = $request->query('subject_id')) {
            $query->where('subject_id', $subjectId);
        }

        $notes = $query->orderByDesc('created_at')
                      ->paginate(20, ['id', 'title', 'level_id', 'class_id', 'subject_id', 'semister_id', 'created_at', 'file_path']);

        $data = collect($notes->items())->map(function ($note) {
            return [
                'id' => $note->id,
                'title' => $note->title,
                'level_id' => $note->level_id,
                'class_id' => $note->class_id,
                'subject_id' => $note->subject_id,
                'semister_id' => $note->semister_id,
                'has_file' => !is_null($note->file_path),
                'created_at' => $note->created_at->toISOString(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'pagination' => [
                'current_page' => $notes->currentPage(),
                'last_page' => $notes->lastPage(),
                'per_page' => $notes->perPage(),
                'total' => $notes->total(),
            ]
        ]);
    }

    /**
     * Get user's own materials
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function myMaterials(Request $request)
    {
        $query = Material::where('user_id', auth()->id());

        if ($search = $request->query('q')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($levelId = $request->query('level_id')) {
            $query->where('level_id', $levelId);
        }

        if ($classId = $request->query('class_id')) {
            $query->where('class_id', $classId);
        }

        if ($subjectId = $request->query('subject_id')) {
            $query->where('subject_id', $subjectId);
        }

        $materials = $query->orderByDesc('created_at')
                          ->paginate(20, ['id', 'title', 'slug', 'level_id', 'class_id', 'subject_id', 'created_at', 'url']);

        return response()->json([
            'success' => true,
            'data' => $materials->items(),
            'pagination' => [
                'current_page' => $materials->currentPage(),
                'last_page' => $materials->lastPage(),
                'per_page' => $materials->perPage(),
                'total' => $materials->total(),
            ]
        ]);
    }

    /**
     * Get taxonomy data for user content creation
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function taxonomy(Request $request)
    {
        $userId = auth()->id();

        // Get user's accessible classes (based on their permissions or admin status)
        $userClasses = SchoolClass::query()
            ->when(!auth()->user()->isAdmin(), function($q) use ($userId) {
                // For non-admin users, only show classes they have access to
                // This could be based on enrollment, permissions, etc.
                // For now, we'll show all classes
            })
            ->with(['subject:id,name', 'subjects:id,name'])
            ->get(['id', 'name', 'subject_id', 'level_id']);

        // Get all levels and subjects (could be filtered based on user permissions)
        $levels = Level::orderBy('name')->get(['id', 'name']);
        $subjects = Subject::orderBy('name')->get(['id', 'name']);
        $semisters = Semister::active()->orderBy('start_date', 'desc')->get(['id', 'name']);
        $materialTypes = Subcategory::orderBy('name')->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'data' => [
                'classes' => $userClasses,
                'levels' => $levels,
                'subjects' => $subjects,
                'semisters' => $semisters,
                'material_types' => $materialTypes,
            ]
        ]);
    }
}
