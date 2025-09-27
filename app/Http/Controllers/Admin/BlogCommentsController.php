<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogComment;
use Illuminate\Http\Request;

class BlogCommentsController extends Controller
{
    public function index(Request $request)
    {
        $q = BlogComment::query()->with(['post:id,title,slug', 'user:id,name,email']);
        if ($s = trim((string)$request->get('s', ''))) {
            $q->where(function($w) use ($s){
                $w->where('author_name','like',"%{$s}%")
                  ->orWhere('email','like',"%{$s}%")
                  ->orWhere('content','like',"%{$s}%");
            });
        }
        if ($post = (int) $request->get('post_id')) {
            $q->where('post_id', $post);
        }
        $comments = $q->latest('id')->paginate(15)->withQueryString();
        return view('admin.cms.blog.comments.index', compact('comments','s','post'));
    }

    public function show(BlogComment $comment)
    {
        $comment->load(['post:id,title,slug','user:id,name,email','replies.user:id,name,email']);
        // fetch responders (unique users who replied under this thread)
        $responders = $comment->replies
            ->map(fn($r)=> $r->user ? ['name'=>$r->user->name,'email'=>$r->user->email] : ['name'=>$r->author_name,'email'=>$r->email])
            ->filter(fn($r)=> !!($r['name']||$r['email']))
            ->unique(function($r){ return ($r['name']??'').'|'.($r['email']??''); })
            ->values();
        return view('admin.cms.blog.comments.show', [
            'comment' => $comment,
            'responders' => $responders,
        ]);
    }

    public function reply(Request $request, BlogComment $comment)
    {
        $data = $request->validate([
            'content' => ['required','string','max:2000'],
        ]);
        // Admin reply is authored by current admin user
        $comment->replies()->create([
            'post_id' => $comment->post_id,
            'user_id' => auth()->id(),
            'author_name' => null,
            'email' => null,
            'content' => $data['content'],
        ]);
        return redirect()->route('cms.blog.comments.show', $comment)->with('status', 'Reply posted.');
    }

    public function destroy(BlogComment $comment)
    {
        $parent = $comment->parent_id ? BlogComment::find($comment->parent_id) : null;
        $comment->delete();
        if ($parent) {
            return redirect()->route('cms.blog.comments.show', $parent)->with('status', 'Comment deleted.');
        }
        return redirect()->route('cms.blog.comments.index')->with('status', 'Comment deleted.');
    }
}
