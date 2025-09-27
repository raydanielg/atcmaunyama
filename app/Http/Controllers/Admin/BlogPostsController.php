<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogPostsController extends Controller
{
    public function index(Request $request)
    {
        $q = BlogPost::query();
        if ($s = trim((string)$request->get('s', ''))) {
            $q->where(function($w) use ($s){
                $w->where('title','like',"%{$s}%")
                  ->orWhere('excerpt','like',"%{$s}%")
                  ->orWhere('content','like',"%{$s}%");
            });
        }
        $posts = $q->latest('id')->paginate(10)->withQueryString();
        return view('admin.cms.blog.index', compact('posts','s'));
    }

    public function create()
    {
        return view('admin.cms.blog.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'excerpt' => ['nullable','string','max:500'],
            'content' => ['required','string'],
            'image' => ['nullable','image','max:4096'],
        ]);
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blog', 'public');
        }
        $post = BlogPost::create([
            'user_id' => auth()->id(),
            'title' => $data['title'],
            'slug' => Str::slug($data['title']).'-'.Str::random(5),
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'],
            'image_path' => $imagePath,
        ]);
        return redirect()->route('cms.blog.posts.index')->with('status', 'Blog post created.');
    }

    public function edit(BlogPost $post)
    {
        return view('admin.cms.blog.edit', compact('post'));
    }

    public function update(Request $request, BlogPost $post)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'excerpt' => ['nullable','string','max:500'],
            'content' => ['required','string'],
            'image' => ['nullable','image','max:4096'],
        ]);
        $payload = [
            'title' => $data['title'],
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'],
        ];
        if ($request->hasFile('image')) {
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }
            $payload['image_path'] = $request->file('image')->store('blog', 'public');
        }
        $post->update($payload);
        return redirect()->route('cms.blog.posts.index')->with('status', 'Blog post updated.');
    }

    public function destroy(BlogPost $post)
    {
        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }
        $post->delete();
        return redirect()->route('cms.blog.posts.index')->with('status', 'Blog post deleted.');
    }
}
