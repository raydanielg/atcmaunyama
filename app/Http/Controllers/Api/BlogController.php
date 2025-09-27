<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            ->with(['comments' => function($q){
                $q->whereNull('parent_id')->orderBy('id','desc');
            }, 'comments.replies' => function($q){ $q->orderBy('id','asc'); }, 'comments.user', 'comments.replies.user'])
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
                $isAdmin = $c->user ? (method_exists($c->user, 'isAdmin') ? (bool)$c->user->isAdmin() : false) : false;
                return [
                    'id' => $c->id,
                    'author' => $c->user?->name ?? $c->author_name ?? 'Anonymous',
                    'email' => $c->user?->email ?? $c->email,
                    'is_admin' => $isAdmin,
                    'content' => $c->content,
                    'created_at' => $c->created_at?->toIso8601String(),
                    'replies' => $c->replies->map(function($r){
                        $isAdminR = $r->user ? (method_exists($r->user, 'isAdmin') ? (bool)$r->user->isAdmin() : false) : false;
                        return [
                            'id' => $r->id,
                            'author' => $r->user?->name ?? $r->author_name ?? 'Anonymous',
                            'email' => $r->user?->email ?? $r->email,
                            'is_admin' => $isAdminR,
                            'content' => $r->content,
                            'created_at' => $r->created_at?->toIso8601String(),
                        ];
                    }),
                ];
            }),
        ]);
    }

    // GET /api/public/blog/id/{id}
    public function showById(Request $request, int $id)
    {
        $post = BlogPost::query()
            ->with(['comments' => function($q){
                $q->whereNull('parent_id')->orderBy('id','desc');
            }, 'comments.replies' => function($q){ $q->orderBy('id','asc'); }, 'comments.user', 'comments.replies.user'])
            ->findOrFail($id);
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
                    'email' => $c->user?->email ?? $c->email,
                    'content' => $c->content,
                    'created_at' => $c->created_at?->toIso8601String(),
                    'replies' => $c->replies->map(function($r){
                        return [
                            'id' => $r->id,
                            'author' => $r->user?->name ?? $r->author_name ?? 'Anonymous',
                            'email' => $r->user?->email ?? $r->email,
                            'content' => $r->content,
                            'created_at' => $r->created_at?->toIso8601String(),
                        ];
                    }),
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
            'email' => ['nullable','email','max:255'],
            'parent_id' => ['nullable','integer','exists:blog_comments,id'],
        ]);
        $comment = $post->comments()->create([
            'user_id' => auth('sanctum')->id(),
            'author_name' => $data['author_name'] ?? null,
            'email' => $data['email'] ?? null,
            'content' => $data['content'],
            'parent_id' => $data['parent_id'] ?? null,
        ]);
        return response()->json([
            'ok' => true,
            'comment' => [
                'id' => $comment->id,
                'author' => $comment->user?->name ?? $comment->author_name ?? 'Anonymous',
                'email' => $comment->user?->email ?? $comment->email,
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

    // GET /api/public/blog/{slug}/image - streams the post hero image (if any)
    public function image(string $slug)
    {
        $post = BlogPost::where('slug', $slug)->firstOrFail();
        if (!$post->image_path) {
            abort(404);
        }
        $disk = Storage::disk('public');
        if (!$disk->exists($post->image_path)) {
            abort(404);
        }
        // Use the original filename if present in path; otherwise default name
        $basename = basename($post->image_path);
        $filename = $basename ?: (str_replace(' ', '-', strtolower($post->title)).'.jpg');
        return $disk->response($post->image_path, $filename);
    }
}
