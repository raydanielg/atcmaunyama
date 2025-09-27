<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ELMS-ATC') }} - {{ $post->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/custom.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
</head>
<body class="bg-white text-gray-900">
@include('partials.header')

<main class="max-w-screen-lg mx-auto px-4 py-10">
    <article class="prose prose-indigo max-w-none">
        <h1 class="!mb-2">{{ $post->title }}</h1>
        <div class="flex items-center gap-3 text-sm text-gray-500 !mt-0 !mb-4">
            <span>{{ $post->created_at?->format('d M Y, H:i') }}</span>
            <span>•</span>
            <span id="viewsCount">{{ $post->views }} views</span>
        </div>
        @if($post->image_path)
            <img src="{{ asset('storage/'.$post->image_path) }}" alt="{{ $post->title }}" class="w-full rounded-xl border" />
        @endif
        @if($post->excerpt)
            <p class="text-gray-600 text-lg">{{ $post->excerpt }}</p>
        @endif
        <div class="mt-6 leading-7">{!! nl2br(e($post->content)) !!}</div>
    </article>

    <!-- Reactions -->
    <section class="mt-8 flex items-center gap-4">
        <button id="btnLike" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border hover:bg-gray-50">
            <svg class="w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="currentColor"><path d="M7 22h10a2 2 0 002-2v-7a2 2 0 00-2-2h-5l1-4V3l-5 7v12z"/></svg>
            <span id="likesCount">0</span>
        </button>
        <button id="btnDislike" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border hover:bg-gray-50">
            <svg class="w-5 h-5 text-red-600" viewBox="0 0 24 24" fill="currentColor"><path d="M17 2H7a2 2 0 00-2 2v7a2 2 0 002 2h5l-1 4v3l5-7V2z"/></svg>
            <span id="dislikesCount">0</span>
        </button>
        <span id="reactStatus" class="text-xs text-gray-500"></span>
    </section>

    <!-- Comments -->
    <section class="mt-10">
        <h2 class="text-lg font-semibold text-gray-900">Comments</h2>
        <div id="commentsList" class="mt-4 space-y-4"></div>

        <div class="mt-6 p-4 border rounded-xl">
            <h3 class="text-sm font-medium text-gray-800">Add a comment</h3>
            <form id="commentForm" class="mt-3 space-y-3">
                <input type="hidden" id="blogSlug" value="{{ $post->slug }}" />
                <div>
                    <label class="block text-sm text-gray-700">Your name (optional)</label>
                    <input type="text" id="authorName" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm" placeholder="e.g. John Doe" />
                </div>
                <div>
                    <label class="block text-sm text-gray-700">Email (optional)</label>
                    <input type="email" id="authorEmail" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm" placeholder="e.g. john@example.com" />
                </div>
                <div>
                    <label class="block text-sm text-gray-700">Comment</label>
                    <textarea id="commentBody" rows="3" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm" required></textarea>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm">Post Comment</button>
                    <span id="commentStatus" class="text-xs text-gray-500"></span>
                </div>
            </form>
        </div>
    </section>
</main>

@include('partials.footer')

<script>
(function(){
    const slug = document.getElementById('blogSlug').value;
    const apiBase = `{{ url('/api/public/blog') }}`;
    const likesEl = document.getElementById('likesCount');
    const dislikesEl = document.getElementById('dislikesCount');
    const commentsList = document.getElementById('commentsList');
    const reactStatus = document.getElementById('reactStatus');
    const commentStatus = document.getElementById('commentStatus');

    async function loadDetails(){
        try{
            const res = await fetch(`${apiBase}/${encodeURIComponent(slug)}`);
            if(!res.ok) return;
            const data = await res.json();
            likesEl.textContent = data.likes ?? 0;
            dislikesEl.textContent = data.dislikes ?? 0;
            renderComments(data.comments || []);
        }catch(e){ console.error(e); }
    }

    function renderComments(items){
        commentsList.innerHTML = '';
            commentsList.innerHTML = '<div class="text-sm text-gray-500">No comments yet. Be the first to comment.</div>';
            return;
        }
        const frag = document.createDocumentFragment();
        items.forEach(it => {
            frag.appendChild(renderCommentNode(it));
        });
        commentsList.appendChild(frag);

    function renderCommentNode(c){
        const wrap = document.createElement('div');
        wrap.className = 'p-3 border rounded-lg';
        const author = escapeHtml(c.author || 'Anonymous');
        const email = c.email ? ` <span class=\"text-xs text-gray-400\">(${escapeHtml(c.email)})</span>` : '';
        const adminBadge = c.is_admin ? ` <span class=\"text-[10px] px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 border border-blue-200\">Admin</span>` : '';
        wrap.innerHTML = `<div class=\"text-sm text-gray-900 font-medium flex items-center gap-2\">${author}${email}${adminBadge}</div>
                          <div class=\"text-sm text-gray-700 mt-1\">${escapeHtml(c.content)}</div>
                          <div class=\"text-xs text-gray-400 mt-1\">${c.created_at ? new Date(c.created_at).toLocaleString() : ''}</div>
                          <button class=\"mt-2 text-xs text-indigo-600 hover:underline\" data-reply>Reply</button>
                          <div class=\"mt-3 hidden\" data-reply-form>
                              <div class=\"grid grid-cols-1 md:grid-cols-2 gap-2\">
{{ ... }}
    }

    function renderCommentChild(r){
        const div = document.createElement('div');
        const author = escapeHtml(r.author || 'Anonymous');
        const email = r.email ? ` <span class=\"text-xs text-gray-400\">(${escapeHtml(r.email)})</span>` : '';
        const adminBadge = r.is_admin ? ` <span class=\"text-[10px] px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 border border-blue-200\">Admin</span>` : '';
        div.className = 'p-3 border rounded-lg bg-gray-50';
        div.innerHTML = `<div class=\"text-sm text-gray-900 font-medium flex items-center gap-2\">${author}${email}${adminBadge}</div>
                         <div class=\"text-sm text-gray-700 mt-1\">${escapeHtml(r.content)}</div>
                         <div class=\"text-xs text-gray-400 mt-1\">${r.created_at ? new Date(r.created_at).toLocaleString() : ''}</div>`;
        return div;
    }

        return (s||'').replace(/[&<>"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));
    }

    async function react(type){
{{ ... }}
        try{
            const res = await fetch(`${apiBase}/${encodeURIComponent(slug)}/react`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ type })
            });
            const data = await res.json();
            likesEl.textContent = data.liked ?? likesEl.textContent;
            dislikesEl.textContent = data.disliked ?? dislikesEl.textContent;
            reactStatus.textContent = 'Saved';
            setTimeout(()=> reactStatus.textContent = '', 800);
        }catch(e){
            reactStatus.textContent = 'Failed. Try again.';
        }
    }

    async function submitComment(e){
        e.preventDefault();
        const author_name = document.getElementById('authorName').value.trim();
        const email = document.getElementById('authorEmail').value.trim();
        const content = document.getElementById('commentBody').value.trim();
        if(!content){ return; }
        commentStatus.textContent = 'Posting...';
        try{
            const res = await fetch(`${apiBase}/${encodeURIComponent(slug)}/comments`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ content, author_name, email })
            });
            if(!res.ok){ commentStatus.textContent = 'Failed to post comment.'; return; }
            const data = await res.json();
            // Prepend the new comment
            renderComments([data.comment]);
            commentStatus.textContent = 'Posted';
            document.getElementById('commentBody').value = '';
            setTimeout(()=> commentStatus.textContent = '', 1000);
            // reload counts/comments
            loadDetails();
        }catch(e){ commentStatus.textContent = 'Failed. Try again.'; }
    }

    document.getElementById('btnLike').addEventListener('click', ()=> react('like'));
    document.getElementById('btnDislike').addEventListener('click', ()=> react('dislike'));
    document.getElementById('commentForm').addEventListener('submit', submitComment);

    loadDetails();
})();
</script>
</body>
</html>
