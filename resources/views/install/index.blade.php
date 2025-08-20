<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WazaElimu Installer</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --bg:#0f172a; --card:#111827; --muted:#cbd5e1; --text:#e5e7eb; --accent:#22c55e; --danger:#ef4444; }
        * { box-sizing: border-box; font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; }
        body { margin:0; background: linear-gradient(120deg, #0f172a 0%, #0b1323 100%); color: var(--text); }
        .wrap { min-height: 100vh; display:flex; align-items:center; justify-content:center; padding: 32px; }
        .card { width: 100%; max-width: 920px; background: rgba(17,24,39,.7); border: 1px solid rgba(255,255,255,.06); border-radius: 16px; backdrop-filter: blur(8px); box-shadow: 0 10px 25px rgba(0,0,0,.3); overflow:hidden; }
        .header { padding: 28px 32px; border-bottom: 1px solid rgba(255,255,255,.06); display:flex; align-items:center; gap:14px; }
        .badge { padding: 4px 10px; font-size:12px; border-radius:999px; background: rgba(34,197,94,.12); color:#86efac; border:1px solid rgba(34,197,94,.35); }
        h1 { margin:0; font-size: 22px; letter-spacing:.2px; }
        .grid { display:grid; grid-template-columns: 1.1fr .9fr; gap: 24px; padding: 24px 32px 28px; }
        .panel { background: rgba(255,255,255,.02); border:1px solid rgba(255,255,255,.06); border-radius:12px; padding:18px; }
        .panel h2 { margin:4px 0 16px; font-size:14px; color:#cbd5e1; letter-spacing:.4px; text-transform:uppercase; }
        label { display:block; font-size:13px; color:#cbd5e1; margin:10px 0 6px; }
        input { width:100%; padding:12px 12px; background:#0b1220; border:1px solid rgba(255,255,255,.08); color:var(--text); border-radius:10px; outline:none; }
        input:focus { border-color: rgba(34,197,94,.5); box-shadow: 0 0 0 3px rgba(34,197,94,.15); }
        .row { display:flex; gap:12px; }
        .actions { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-top:16px; }
        .btn { appearance:none; border:none; padding:12px 16px; border-radius:10px; cursor:pointer; font-weight:600; }
        .btn-primary { background: linear-gradient(135deg, #22c55e, #16a34a); color:#052e16; }
        .btn-primary:hover { filter: brightness(1.05); }
        .hint { font-size:12px; color:#94a3b8; }
        .errors { background: rgba(239,68,68,.08); border:1px solid rgba(239,68,68,.25); color:#fecaca; padding:10px 12px; border-radius:10px; margin-bottom:12px; }
        ul { margin:8px 0 0 16px; }
        .ok { color:#86efac }
        .bad { color:#fecaca }
        .kv { display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px dashed rgba(255,255,255,.08); font-size:13px }
        .kv span:first-child { color:#cbd5e1 }
        .muted { color:#9aa6b2; font-size:12px; margin-top:8px }
        @media (max-width: 900px) { .grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <div class="header">
            <span class="badge">Setup</span>
            <h1>WazaElimu — Installer</h1>
        </div>
        <form method="post" action="{{ route('install.store') }}">
            @csrf
            <div class="grid">
                <div class="panel">
                    <h2>Database (MySQL)</h2>
                    @if ($errors->any())
                        <div class="errors">
                            <strong>Issues detected:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="panel" style="background:transparent;border:none;padding:0;margin-bottom:8px">
                        <h2>Preflight Checks</h2>
                        <div class="kv"><span>PHP >= 8.2</span><span class="{{ ($checks['requirements']['php_version']??false)?'ok':'bad' }}">{{ ($checks['requirements']['php_version']??false)?'OK':'Missing' }}</span></div>
                        <div class="kv"><span>pdo_mysql</span><span class="{{ ($checks['requirements']['ext_pdo_mysql']??false)?'ok':'bad' }}">{{ ($checks['requirements']['ext_pdo_mysql']??false)?'OK':'Missing' }}</span></div>
                        <div class="kv"><span>mbstring</span><span class="{{ ($checks['requirements']['ext_mbstring']??false)?'ok':'bad' }}">{{ ($checks['requirements']['ext_mbstring']??false)?'OK':'Missing' }}</span></div>
                        <div class="kv"><span>openssl</span><span class="{{ ($checks['requirements']['ext_openssl']??false)?'ok':'bad' }}">{{ ($checks['requirements']['ext_openssl']??false)?'OK':'Missing' }}</span></div>
                        <div class="kv"><span>intl</span><span class="{{ ($checks['requirements']['ext_intl']??false)?'ok':'bad' }}">{{ ($checks['requirements']['ext_intl']??false)?'OK':'Missing' }}</span></div>
                        <div class="kv"><span>storage writable</span><span class="{{ ($checks['permissions']['storage']??false)?'ok':'bad' }}">{{ ($checks['permissions']['storage']??false)?'OK':'Not writable' }}</span></div>
                        <div class="kv"><span>bootstrap/cache writable</span><span class="{{ ($checks['permissions']['bootstrap_cache']??false)?'ok':'bad' }}">{{ ($checks['permissions']['bootstrap_cache']??false)?'OK':'Not writable' }}</span></div>
                        <div class="kv"><span>.env writable or creatable</span><span class="{{ ($checks['permissions']['env_writable']??false)?'ok':'bad' }}">{{ ($checks['permissions']['env_writable']??false)?'OK':'Not writable' }}</span></div>
                        <div class="muted">If any item is red, fix it in your server (cPanel: PHP selector/extensions, folder permissions) then reload.</div>
                    </div>
                    <label>DB Host</label>
                    <input name="db_host" value="{{ old('db_host', '127.0.0.1') }}" required />
                    <div class="row">
                        <div style="flex:1">
                            <label>DB Port</label>
                            <input name="db_port" type="number" value="{{ old('db_port', '3306') }}" required />
                        </div>
                        <div style="flex:2">
                            <label>DB Name</label>
                            <input name="db_database" value="{{ old('db_database') }}" required />
                        </div>
                    </div>
                    <div class="row">
                        <div style="flex:1">
                            <label>DB Username</label>
                            <input name="db_username" value="{{ old('db_username') }}" required />
                        </div>
                        <div style="flex:1">
                            <label>DB Password</label>
                            <input name="db_password" type="password" value="{{ old('db_password') }}" />
                        </div>
                    </div>
                    <label>App URL</label>
                    <input name="app_url" value="{{ old('app_url', url('/')) }}" placeholder="http://example.com" />
                </div>
                <div class="panel">
                    <h2>Admin Account</h2>
                    <label>Admin Name</label>
                    <input name="admin_name" value="{{ old('admin_name', 'Administrator') }}" required />
                    <label>Admin Email</label>
                    <input name="admin_email" type="email" value="{{ old('admin_email') }}" required />
                    <label>Admin Password</label>
                    <input name="admin_password" type="password" required />
                    <div class="actions">
                        <div class="hint">This runs migrations and seeders on MySQL.</div>
                        <button class="btn btn-primary" type="submit" {{ empty($allOk) || !$allOk ? 'disabled title=\'Fix preflight checks first\'' : '' }}>Install</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
