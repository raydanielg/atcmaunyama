<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    // GET /api/public/blog
    public function index(Request $request)
    {
        $q = BlogPost::query();
        if ($s = trim((string)$request->query('q', ''))) {
            $q->where(function($w) use ($s) {
                $w->where('title', 'like', "%{$s}%")
                  ->orWhere('excerpt', 'like', "%{$s}%");
            });
        }
        $q->latest('id');
        $items = $q->paginate(10)->through(function (BlogPost $p) {
            return [
                'id' => $p->id,
                'title' => $p->title,
                'slug' => $p->slug,
                'excerpt' => $p->excerpt,
                'image_url' => $p->image_path ? asset('storage/'.$p->image_path) : null,
                'likes' => $p->reactions()->where('type','like')->count(),
                'dislikes' => $p->reactions()->where('type','dislike')->count(),
                'created_at' => $p->created_at?->toIso8601String(),
            ];
        });
        return response()->json($items);
    }

    // GET /api/public/blog/{slug}
    public function show(Request $request, string $slug)
    {
        $post = BlogPost::query()
            ->where('slug', $slug)
            ->with(['comments' => function($q){ $q->latest('id'); }])
            ->firstOrFail();

        // Optionally increment views
        $post->increment('views');

        return response()->json([
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'content' => $post->content,
            'excerpt' => $post->excerpt,
            'image_url' => $post->image_path ? asset('storage/'.$post->image_path) : null,
            'likes' => $post->reactions()->where('type','like')->count(),
            'dislikes' => $post->reactions()->where('type','dislike')->count(),
            'created_at' => $post->created_at?->toIso8601String(),
            'comments' => $post->comments->map(function($c){
                return [
                    'id' => $c->id,
                    'author' => $c->user?->name ?? $c->author_name ?? 'Anonymous',
                    'content' => $c->content,
                    'created_at' => $c->created_at?->toIso8601String(),
                ];
            }),
        ]);
    }

    // GET /api/public/blog/id/{id}
    public function showById(Request $request, int $id)
    {
        $post = BlogPost::with(['comments' => function($q){ $q->latest('id'); }])->findOrFail($id);
        $post->increment('views');
        return response()->json([
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'content' => $post->content,
            'excerpt' => $post->excerpt,
            'image_url' => $post->image_path ? asset('storage/'.$post->image_path) : null,
            'likes' => $post->reactions()->where('type','like')->count(),
            'dislikes' => $post->reactions()->where('type','dislike')->count(),
            'created_at' => $post->created_at?->toIso8601String(),
            'comments' => $post->comments->map(function($c){
                return [
                    'id' => $c->id,
                    'author' => $c->user?->name ?? $c->author_name ?? 'Anonymous',
                    'content' => $c->content,
                    'created_at' => $c->created_at?->toIso8601String(),
                ];
            }),
        ]);
    }

    // POST /api/public/blog/{slug}/comments
    public function storeComment(Request $request, string $slug)
    {
        $post = BlogPost::where('slug', $slug)->firstOrFail();
        $data = $request->validate([
            'content' => ['required','string','max:2000'],
            'author_name' => ['nullable','string','max:100'],
        ]);
        $comment = $post->comments()->create([
            'user_id' => auth('sanctum')->id(),
            'author_name' => $data['author_name'] ?? null,
            'content' => $data['content'],
        ]);
        return response()->json([
            'ok' => true,
            'comment' => [
                'id' => $comment->id,
                'author' => $comment->user?->name ?? $comment->author_name ?? 'Anonymous',
                'content' => $comment->content,
                'created_at' => $comment->created_at?->toIso8601String(),
            ],
        ], 201);
    }

    // POST /api/public/blog/{slug}/react  { type: like|dislike }
    public function react(Request $request, string $slug)
    {
        $post = BlogPost::where('slug', $slug)->firstOrFail();
        $data = $request->validate([
            'type' => ['required','in:like,dislike'],
        ]);
        $userId = auth('sanctum')->id();
        // If user is authenticated, ensure single reaction per type per user
        if ($userId) {
            $existing = $post->reactions()->where('user_id', $userId)->where('type', $data['type'])->first();
            if ($existing) {
                // toggle off if same type sent again
                $existing->delete();
                $liked = $post->reactions()->where('type','like')->count();
                $disliked = $post->reactions()->where('type','dislike')->count();
                return response()->json(['ok'=>true,'liked'=>$liked,'disliked'=>$disliked,'toggled'=>true]);
            }
            // Remove opposite reaction if exists
            $post->reactions()->where('user_id', $userId)->where('type', $data['type']==='like'?'dislike':'like')->delete();
            $post->reactions()->create(['user_id'=>$userId,'type'=>$data['type']]);
        } else {
            // For guests, just increment counters logically using DB record with null user_id
            $post->reactions()->create(['user_id'=>null,'type'=>$data['type']]);
        }
        $liked = $post->reactions()->where('type','like')->count();
        $disliked = $post->reactions()->where('type','dislike')->count();
        return response()->json(['ok'=>true,'liked'=>$liked,'disliked'=>$disliked]);
    }
}
