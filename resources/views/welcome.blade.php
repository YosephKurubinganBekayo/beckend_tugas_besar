@php
    $docs = config('api_docs');
    $methodClass = fn (string $method) => strtolower(str_contains($method, '/') ? explode('/', $method)[0] : $method);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SPK SISWA - Dokumentasi API</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Fira+Code:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --bg-dark: #0f172a;
            --bg-card: #1e293b;
            --bg-soft: #111827;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent: #10b981;
            --border: #334155;
            --sidebar-width: 290px;
            --chat-primary: #8b5cf6;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: Inter, sans-serif; background: var(--bg-dark); color: var(--text-main); line-height: 1.6; overflow-x: hidden; }
        code, pre { font-family: 'Fira Code', monospace; }
        code { color: #c7d2fe; background: rgba(99, 102, 241, 0.12); padding: 0.1rem 0.3rem; border-radius: 0.25rem; }
        .layout { display: flex; min-height: 100vh; }
        aside { width: var(--sidebar-width); background: rgba(30, 41, 59, 0.96); border-right: 1px solid var(--border); padding: 2rem 1.35rem; position: fixed; inset: 0 auto 0 0; overflow-y: auto; z-index: 20; }
        .logo { font-size: 1.25rem; font-weight: 800; color: #c7d2fe; margin-bottom: 0.5rem; }
        .version { color: var(--text-muted); font-size: 0.8rem; margin-bottom: 2rem; }
        .nav-group { margin-bottom: 1.35rem; }
        .nav-title { text-transform: uppercase; font-size: 0.72rem; letter-spacing: 0.08em; color: var(--text-muted); margin-bottom: 0.45rem; font-weight: 800; }
        .nav-link { display: block; padding: 0.42rem 0.65rem; color: var(--text-muted); text-decoration: none; font-size: 0.84rem; border-radius: 0.5rem; margin-bottom: 0.15rem; }
        .nav-link:hover, .nav-link.active { background: rgba(99, 102, 241, 0.16); color: #e0e7ff; }
        main { flex: 1; margin-left: var(--sidebar-width); padding: 4rem 3rem; max-width: 1280px; }
        header { margin-bottom: 3.5rem; }
        h1 { font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 800; line-height: 1.05; margin-bottom: 1rem; }
        .description { color: var(--text-muted); font-size: 1rem; max-width: 900px; }
        .section { margin-bottom: 4.5rem; scroll-margin-top: 2rem; }
        .section-title { font-size: 1.7rem; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border); padding-bottom: 0.6rem; }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; }
        .card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 0.8rem; padding: 1.15rem; }
        .card h3 { font-size: 1rem; margin-bottom: 0.45rem; }
        .card p, .card li { color: var(--text-muted); font-size: 0.9rem; }
        .card ul { padding-left: 1.2rem; }
        .step { counter-increment: step; position: relative; padding-left: 3.2rem; }
        .step::before { content: counter(step); position: absolute; left: 1rem; top: 1.1rem; width: 1.45rem; height: 1.45rem; border-radius: 999px; background: var(--primary); color: white; display: grid; place-items: center; font-weight: 800; font-size: 0.8rem; }
        .steps { counter-reset: step; display: grid; gap: 1rem; }
        .code-block { background: #020617; border: 1px solid var(--border); color: #cbd5e1; padding: 1rem; border-radius: 0.65rem; overflow-x: auto; font-size: 0.82rem; margin-top: 0.75rem; white-space: pre-wrap; }
        .endpoint-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 0.85rem; margin-bottom: 1rem; overflow: hidden; }
        .endpoint-header { padding: 1rem 1.2rem; display: flex; align-items: center; gap: 0.8rem; background: rgba(255,255,255,0.025); border-bottom: 1px solid var(--border); }
        .method { padding: 0.28rem 0.7rem; border-radius: 0.45rem; font-weight: 800; font-size: 0.72rem; min-width: 78px; text-align: center; }
        .method.get { background: rgba(16, 185, 129, 0.16); color: #34d399; }
        .method.post { background: rgba(59, 130, 246, 0.16); color: #60a5fa; }
        .method.put { background: rgba(245, 158, 11, 0.16); color: #fbbf24; }
        .method.delete { background: rgba(239, 68, 68, 0.16); color: #f87171; }
        .path { font-family: 'Fira Code', monospace; font-weight: 600; color: #e2e8f0; overflow-wrap: anywhere; }
        .auth-badge { margin-left: auto; font-size: 0.72rem; color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.25); border-radius: 0.4rem; padding: 0.18rem 0.5rem; }
        .public-badge { margin-left: auto; font-size: 0.72rem; color: #34d399; border: 1px solid rgba(52, 211, 153, 0.25); border-radius: 0.4rem; padding: 0.18rem 0.5rem; }
        .endpoint-body { padding: 1rem 1.2rem 1.2rem; }
        .endpoint-body p { color: var(--text-muted); margin-bottom: 0.8rem; }
        .meta-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
        .meta-title { color: #c7d2fe; font-weight: 800; font-size: 0.8rem; margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
        .table th, .table td { text-align: left; border-bottom: 1px solid rgba(51, 65, 85, 0.65); padding: 0.45rem; vertical-align: top; }
        .table th { color: var(--text-muted); font-size: 0.75rem; }
        footer { border-top: 1px solid var(--border); color: var(--text-muted); padding-top: 2rem; margin-top: 4rem; }
        .ai-fab { position: fixed; right: 2rem; bottom: 2rem; width: 58px; height: 58px; border-radius: 50%; border: 0; color: white; background: linear-gradient(135deg, var(--primary), var(--chat-primary)); box-shadow: 0 18px 40px rgba(99, 102, 241, 0.35); cursor: pointer; font-size: 1.35rem; z-index: 50; }
        .ai-chat-window { position: fixed; right: 2rem; bottom: 6rem; width: 420px; height: 560px; background: var(--bg-card); border: 1px solid var(--border); border-radius: 1rem; display: flex; flex-direction: column; overflow: hidden; opacity: 0; pointer-events: none; transform: translateY(14px); transition: 0.2s ease; z-index: 49; }
        .ai-chat-window.open { opacity: 1; pointer-events: auto; transform: translateY(0); }
        .ai-header { padding: 1rem 1.1rem; background: linear-gradient(135deg, var(--primary), var(--chat-primary)); display: flex; justify-content: space-between; align-items: center; }
        .ai-close { background: transparent; border: 0; color: white; font-size: 1.3rem; cursor: pointer; }
        .ai-body { flex: 1; padding: 1rem; overflow-y: auto; display: flex; flex-direction: column; gap: 0.8rem; }
        .msg { max-width: 88%; padding: 0.75rem 0.9rem; border-radius: 0.85rem; font-size: 0.9rem; white-space: pre-wrap; }
        .msg-ai { background: rgba(139, 92, 246, 0.12); border: 1px solid rgba(139, 92, 246, 0.22); align-self: flex-start; color: #e2e8f0; }
        .msg-user { background: var(--primary); color: white; align-self: flex-end; }
        .ai-footer { padding: 1rem; border-top: 1px solid var(--border); background: var(--bg-soft); }
        .ai-input-wrap { display: flex; gap: 0.5rem; }
        .ai-input-wrap input { flex: 1; background: var(--bg-card); border: 1px solid var(--border); color: white; padding: 0.7rem 0.9rem; border-radius: 0.7rem; outline: none; }
        .ai-send { background: var(--primary); color: white; border: 0; border-radius: 0.7rem; padding: 0 1rem; cursor: pointer; font-weight: 700; }
        @media (max-width: 1100px) {
            aside { display: none; }
            main { margin-left: 0; padding: 2rem 1rem; }
            .grid, .grid-3, .meta-grid { grid-template-columns: 1fr; }
            .ai-chat-window { width: calc(100vw - 2rem); right: 1rem; bottom: 5.5rem; }
            .endpoint-header { align-items: flex-start; flex-wrap: wrap; }
            .auth-badge, .public-badge { margin-left: 0; }
        }
    </style>
</head>
<body>
<div class="layout">
    <aside>
        <div class="logo">SPK SISWA API</div>
        <div class="version">Versi {{ $docs['version'] }}</div>

        <div class="nav-group">
            <div class="nav-title">Panduan</div>
            <a class="nav-link" href="#fitur">Fitur API</a>
            <a class="nav-link" href="#akses">API Key & Auth</a>
            <a class="nav-link" href="#instalasi">Instalasi</a>
            <a class="nav-link" href="#realtime">Real-time</a>
            <a class="nav-link" href="#endpoints">Semua Endpoint</a>
        </div>

        @foreach ($docs['groups'] as $group)
            <div class="nav-group">
                <div class="nav-title">{{ $group['title'] }}</div>
                @foreach ($group['endpoints'] as $endpoint)
                    <a class="nav-link" href="#{{ Str::slug($endpoint['method'].'-'.$endpoint['uri']) }}">{{ $endpoint['method'] }} {{ $endpoint['uri'] }}</a>
                @endforeach
            </div>
        @endforeach
    </aside>

    <main>
        <header>
            <h1>Dokumentasi Integrasi API SPK SISWA</h1>
            <p class="description">
                Halaman ini terkunci dengan <code>{{ $docs['access_key_env'] }}</code>. Setelah masuk, integrator dapat melihat seluruh fitur, langkah instalasi, konfigurasi Reverb/Redis/WebSocket, cara autentikasi, dan semua endpoint GET, POST, PUT/PATCH, serta DELETE.
            </p>
        </header>

        <section id="fitur" class="section">
            <h2 class="section-title">Fitur API</h2>
            <div class="grid">
                @foreach ($docs['features'] as $feature)
                    <div class="card"><p>{{ $feature }}</p></div>
                @endforeach
            </div>
        </section>

        <section id="akses" class="section">
            <h2 class="section-title">API Key Dokumentasi & Autentikasi API</h2>
            <div class="grid">
                <div class="card">
                    <h3>Akses Halaman Dokumentasi</h3>
                    <p>Pengunjung harus memasukkan API key dokumentasi terlebih dahulu. Nilainya diatur melalui environment <code>{{ $docs['access_key_env'] }}</code>.</p>
                    <div class="code-block">{{ $docs['access_key_env'] }}=isi-key-rahasia-anda</div>
                </div>
                <div class="card">
                    <h3>Header Integrasi API</h3>
                    <p>API aplikasi memakai {{ $docs['auth']['type'] }}. Login dulu, lalu kirim token untuk request berikutnya.</p>
                    <div class="code-block">{{ implode("\n", $docs['auth']['required_headers']) }}
{{ $docs['auth']['header'] }}</div>
                </div>
            </div>
        </section>

        <section id="instalasi" class="section">
            <h2 class="section-title">Cara Instalasi & Menjalankan Project</h2>
            <div class="steps">
                @foreach ($docs['installation'] as $step)
                    <div class="card step">
                        <h3>{{ $step['title'] }}</h3>
                        <div class="code-block">{{ implode("\n", $step['commands']) }}</div>
                        @isset($step['notes'])
                            <ul>
                                @foreach ($step['notes'] as $note)
                                    <li>{{ $note }}</li>
                                @endforeach
                            </ul>
                        @endisset
                    </div>
                @endforeach
            </div>
        </section>

        <section id="realtime" class="section">
            <h2 class="section-title">Integrasi Real-time Reverb, Redis, Broadcasting Event, WebSocket</h2>
            <div class="grid">
                <div class="card">
                    <h3>Konsep</h3>
                    <p>Observer model akan memancarkan event setiap data utama dibuat, diubah, atau dihapus. Client subscribe ke channel privat lalu refresh tampilan ketika event diterima.</p>
                    <div class="code-block">Channel: {{ $docs['realtime']['channel'] }}
Event: {{ $docs['realtime']['listen'] }}
Auth Endpoint: {{ $docs['realtime']['auth_endpoint'] }}</div>
                </div>
                <div class="card">
                    <h3>Payload Event</h3>
                    <table class="table">
                        <tbody>
                        @foreach ($docs['realtime']['payload'] as $key => $value)
                            <tr><td><code>{{ $key }}</code></td><td>{{ $value }}</td></tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card" style="margin-top: 1rem;">
                <h3>Contoh Client JavaScript</h3>
                <div class="code-block">{{ $docs['realtime']['client_example'] }}</div>
            </div>
        </section>

        <section id="endpoints" class="section">
            <h2 class="section-title">Semua Endpoint API</h2>
            @foreach ($docs['groups'] as $group)
                <div class="section" id="{{ Str::slug($group['title']) }}" style="margin-bottom: 2.5rem;">
                    <h2 class="section-title">{{ $group['title'] }}</h2>
                    <p class="description" style="margin-bottom: 1rem;">{{ $group['description'] }}</p>

                    @foreach ($group['endpoints'] as $endpoint)
                        <article class="endpoint-card" id="{{ Str::slug($endpoint['method'].'-'.$endpoint['uri']) }}">
                            <div class="endpoint-header">
                                <span class="method {{ $methodClass($endpoint['method']) }}">{{ $endpoint['method'] }}</span>
                                <span class="path">{{ $endpoint['uri'] }}</span>
                                <span class="{{ $endpoint['auth'] ? 'auth-badge' : 'public-badge' }}">{{ $endpoint['auth'] ? 'Bearer Token' : 'Public' }}</span>
                            </div>
                            <div class="endpoint-body">
                                <p>{{ $endpoint['description'] }}</p>
                                <div class="meta-grid">
                                    @isset($endpoint['query'])
                                        <div>
                                            <div class="meta-title">Query Params</div>
                                            <table class="table">
                                                @foreach ($endpoint['query'] as $key => $rule)
                                                    <tr><td><code>{{ $key }}</code></td><td>{{ $rule }}</td></tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    @endisset
                                    @isset($endpoint['body'])
                                        <div>
                                            <div class="meta-title">Body JSON</div>
                                            <table class="table">
                                                @foreach ($endpoint['body'] as $key => $rule)
                                                    <tr><td><code>{{ $key }}</code></td><td>{{ $rule }}</td></tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    @endisset
                                </div>
                                <div class="code-block">curl -X {{ explode('/', $endpoint['method'])[0] }} "{{ rtrim($docs['base_url'], '/') }}{{ $endpoint['uri'] }}" \
  -H "Accept: application/json"{{ $endpoint['auth'] ? ' \\'."\n".'  -H "Authorization: Bearer {access_token}"' : '' }}@isset($endpoint['body']) \
  -H "Content-Type: application/json" \
  -d '{{ json_encode(array_fill_keys(array_keys($endpoint['body']), '...'), JSON_PRETTY_PRINT) }}'@endisset</div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endforeach
        </section>

        <footer>
            &copy; {{ date('Y') }} SPK SISWA API. Dokumentasi ini dibuka setelah validasi API key dokumentasi.
        </footer>
    </main>
</div>

<button class="ai-fab" id="ai-fab" title="Tanya AI Dokumentasi">AI</button>

<div class="ai-chat-window" id="ai-chat">
    <div class="ai-header">
        <strong>AI Dokumentasi Project</strong>
        <button class="ai-close" id="ai-close" type="button">&times;</button>
    </div>
    <div class="ai-body" id="ai-body">
        <div class="msg msg-ai">Halo. Saya bisa menjawab pertanyaan konsumen berdasarkan endpoint, instalasi, auth, Reverb, Redis, WebSocket, dan data dokumentasi project ini.</div>
    </div>
    <div class="ai-footer">
        <form class="ai-input-wrap" id="ai-form">
            <input type="text" id="ai-input" placeholder="Contoh: cara update siswa atau instal Reverb..." autocomplete="off">
            <button class="ai-send" type="submit">Kirim</button>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('.nav-link').forEach((link) => {
        link.addEventListener('click', () => {
            document.querySelectorAll('.nav-link').forEach((item) => item.classList.remove('active'));
            link.classList.add('active');
        });
    });

    const fab = document.getElementById('ai-fab');
    const chat = document.getElementById('ai-chat');
    const closeBtn = document.getElementById('ai-close');
    const form = document.getElementById('ai-form');
    const input = document.getElementById('ai-input');
    const body = document.getElementById('ai-body');
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    fab.addEventListener('click', () => {
        chat.classList.add('open');
        input.focus();
    });

    closeBtn.addEventListener('click', () => chat.classList.remove('open'));

    function addMessage(text, isAi) {
        const div = document.createElement('div');
        div.className = `msg ${isAi ? 'msg-ai' : 'msg-user'}`;
        div.textContent = text;
        body.appendChild(div);
        body.scrollTop = body.scrollHeight;
        return div;
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const message = input.value.trim();

        if (!message) return;

        addMessage(message, false);
        input.value = '';
        const loading = addMessage('Sedang membaca dokumentasi project...', true);

        try {
            const response = await fetch('{{ route('docs.chat') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify({ message }),
            });

            const data = await response.json();
            loading.textContent = data.answer || 'Tidak ada jawaban dari chatbot.';
        } catch (error) {
            loading.textContent = 'Chatbot belum bisa dihubungi. Periksa koneksi server Laravel atau konfigurasi OPENAI_API_KEY.';
        }
    });
</script>
</body>
</html>
