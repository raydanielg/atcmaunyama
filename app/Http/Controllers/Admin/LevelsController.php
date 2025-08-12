<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\ActivityLog;
use Illuminate\Support\Facades\Http;

class LevelsController extends Controller
{
    public function index(Request $request)
    {
        $s = trim((string)$request->get('s'));
        $levels = Level::when($s, function($q) use ($s){
                $q->where('name', 'like', "%$s%");
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.learning.levels.index', compact('levels', 's'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'icon' => ['nullable','string','max:100'],
        ]);
        $level = Level::create($data);
        ActivityLog::log('level.created', "Created level #{$level->id}: {$level->name}", auth()->id());
        return redirect()->route('learning.levels.index')->with('status', 'Level added.');
    }

    public function update(Request $request, Level $level)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'icon' => ['nullable','string','max:100'],
        ]);
        $level->update($data);
        ActivityLog::log('level.updated', "Updated level #{$level->id}: {$level->name}", auth()->id());
        return redirect()->route('learning.levels.index')->with('status', 'Level updated.');
    }

    public function destroy(Level $level)
    {
        $id = $level->id; $name = $level->name;
        $level->delete();
        ActivityLog::log('level.deleted', "Deleted level #{$id}: {$name}", auth()->id());
        return redirect()->route('learning.levels.index')->with('status', 'Level deleted.');
    }

    public function classesJson(Level $level)
    {
        $classes = $level->classes()->select(['id','name'])->orderBy('name')->get();
        return response()->json([
            'level' => ['id'=>$level->id,'name'=>$level->name],
            'classes' => $classes,
        ]);
    }

    /**
     * JSON for Assign Classes modal: returns all classes and selected IDs for this level.
     */
    public function classesAssignJson(Level $level)
    {
        $all = SchoolClass::query()->orderBy('name')->get(['id','name','level_id']);
        $selected = $all->where('level_id', $level->id)->pluck('id')->values();
        return response()->json([
            'classes' => $all,
            'selected' => $selected,
        ]);
    }

    /**
     * Sync classes assigned to a level: selected IDs get this level_id; previous unselected are cleared.
     */
    public function syncClasses(Request $request, Level $level)
    {
        $data = $request->validate([
            'class_ids' => ['array'],
            'class_ids.*' => ['integer','exists:school_classes,id'],
        ]);
        $selected = collect($data['class_ids'] ?? [])->map(fn($v)=> (int)$v)->unique()->values();

        // 1) Clear from this level any classes not in selected
        SchoolClass::query()
            ->where('level_id', $level->id)
            ->whereNotIn('id', $selected)
            ->update(['level_id' => null]);

        // 2) Assign selected to this level (even if previously assigned elsewhere)
        if ($selected->isNotEmpty()) {
            SchoolClass::query()->whereIn('id', $selected)->update(['level_id' => $level->id]);
        }

        ActivityLog::log('level.classes.synced', 'Updated classes for level #'.$level->id, auth()->id());
        return redirect()->route('learning.levels.index')->with('status', 'Classes updated for level.');
    }

    /**
     * Generate a short, high-quality description for a level name via Google Generative Language API (Gemini).
     */
    public function aiSuggestDescription(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
        ]);
        $name = trim($data['name']);
        $apiKey = env('GOOGLE_GENAI_KEY') ?: env('GOOGLE_API_KEY');
        if (!$apiKey) {
            return response()->json(['error' => 'Missing GOOGLE_GENAI_KEY or GOOGLE_API_KEY in .env'], 400);
        }

        $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';
        $prompt = "Write a concise (1-2 sentences), clear, student-friendly description for the education level named `{$name}`. Keep it under 25 words, avoid emojis, and be professional.";

        try {
            $resp = Http::asJson()
                ->withOptions(['query' => ['key' => $apiKey]])
                ->post($endpoint, [
                    'contents' => [
                        [
                            'parts' => [ ['text' => $prompt] ]
                        ]
                    ]
                ]);
            if (!$resp->ok()) {
                return response()->json(['error' => 'AI request failed', 'details' => $resp->json()], 500);
            }
            $json = $resp->json();
            $text = '';
            // parse candidates[0].content.parts[0].text
            if (!empty($json['candidates'][0]['content']['parts'][0]['text'])) {
                $text = trim($json['candidates'][0]['content']['parts'][0]['text']);
            }
            if ($text === '') {
                $text = "A concise description for {$name}.";
            }
            // Ensure max ~ 25 words
            $words = preg_split('/\s+/', $text);
            if (count($words) > 26) {
                $text = implode(' ', array_slice($words, 0, 26));
            }
            return response()->json(['description' => $text]);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'AI exception', 'message' => $e->getMessage()], 500);
        }
    }
}
