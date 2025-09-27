<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogPublicController extends Controller
{
    public function index(Request $request)
    {
        $s = trim((string)$request->query('q', ''));
        $q = BlogPost::query();
        if ($s !== '') {
            $q->where(function($w) use ($s){
                $w->where('title','like',"%{$s}%")
                  ->orWhere('excerpt','like',"%{$s}%");
            });
        }
        $posts = $q->latest('id')->paginate(9)->withQueryString();
        return view('blog.index', compact('posts','s'));
    }

    public function show(string $slug)
    {
        $post = BlogPost::where('slug', $slug)->firstOrFail();
        return view('blog.show', compact('post'));
    }
}
