<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WazaElimu Installed</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --bg:#0f172a; --card:#111827; --muted:#cbd5e1; --text:#e5e7eb; --accent:#22c55e; }
        * { box-sizing: border-box; font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; }
        body { margin:0; background: linear-gradient(120deg, #0f172a 0%, #0b1323 100%); color: var(--text); }
        .wrap { min-height: 100vh; display:flex; align-items:center; justify-content:center; padding: 32px; }
        .card { width: 100%; max-width: 860px; background: rgba(17,24,39,.7); border: 1px solid rgba(255,255,255,.06); border-radius: 16px; backdrop-filter: blur(8px); box-shadow: 0 10px 25px rgba(0,0,0,.3); overflow:hidden; }
        .header { padding: 28px 32px; border-bottom: 1px solid rgba(255,255,255,.06); display:flex; align-items:center; gap:14px; }
        .badge { padding: 4px 10px; font-size:12px; border-radius:999px; background: rgba(34,197,94,.12); color:#86efac; border:1px solid rgba(34,197,94,.35); }
        h1 { margin:0; font-size: 22px; letter-spacing:.2px; }
        .content { padding: 24px 32px 28px; display:grid; grid-template-columns: 1fr .8fr; gap: 20px; }
        .panel { background: rgba(255,255,255,.02); border:1px solid rgba(255,255,255,.06); border-radius:12px; padding:18px; }
        .panel h2 { margin:4px 0 16px; font-size:14px; color:#cbd5e1; letter-spacing:.4px; text-transform:uppercase; }
        .list { display:flex; flex-wrap:wrap; gap:8px; }
        .tag { padding:6px 10px; font-size:12px; border-radius:999px; background:#0b1220; border:1px solid rgba(255,255,255,.08); color:#cbd5e1 }
        .actions { margin-top:18px; display:flex; gap:12px; }
        .btn { appearance:none; border:none; padding:12px 16px; border-radius:10px; cursor:pointer; font-weight:600; text-decoration:none; text-align:center; }
        .btn-primary { background: linear-gradient(135deg, #22c55e, #16a34a); color:#052e16; }
        .btn-muted { background: #0b1220; color:#cbd5e1; border:1px solid rgba(255,255,255,.08); }
        @media (max-width: 900px) { .content { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <div class="header">
            <span class="badge">Success</span>
            <h1>WazaElimu Installed</h1>
        </div>
        <div class="content">
            <div class="panel">
                <h2>Admin Credentials</h2>
                <p>Email: <strong>{{ $adminEmail }}</strong></p>
                <p>Password: <em>(the one you entered)</em></p>
                <div class="actions">
                    <a class="btn btn-primary" href="{{ url('/login') }}">Go to Login</a>
                    <a class="btn btn-muted" href="{{ url('/') }}">Home</a>
                </div>
            </div>
            <div class="panel">
                <h2>Created Tables</h2>
                <div class="list">
                    @foreach ($tables as $t)
                        <span class="tag">{{ $t }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
