<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK SISWA - Full API Documentation & AI Assistant</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --bg-dark: #0f172a;
            --bg-card: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent: #10b981;
            --border: #334155;
            --sidebar-width: 280px;
            --chat-primary: #8b5cf6;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-dark); color: var(--text-main); line-height: 1.6; overflow-x: hidden; scroll-behavior: smooth; }
        
        .layout { display: flex; min-height: 100vh; }
        
        /* SIDEBAR */
        aside {
            width: var(--sidebar-width);
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(10px);
            border-right: 1px solid var(--border);
            padding: 2rem 1.5rem;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
        }
        .logo { font-size: 1.5rem; font-weight: 800; color: var(--primary); margin-bottom: 2.5rem; display: flex; align-items: center; gap: 0.5rem; }
        .nav-group { margin-bottom: 1.5rem; }
        .nav-title { text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.1em; color: var(--text-muted); margin-bottom: 0.5rem; font-weight: 700; }
        .nav-link { display: block; padding: 0.4rem 0.75rem; color: var(--text-muted); text-decoration: none; font-size: 0.85rem; border-radius: 0.5rem; transition: all 0.2s; margin-bottom: 0.2rem; }
        .nav-link:hover { background: rgba(99, 102, 241, 0.1); color: var(--primary); }
        .nav-link.active { background: var(--primary); color: white; }

        /* MAIN CONTENT */
        main { flex: 1; margin-left: var(--sidebar-width); padding: 4rem 3rem; max-width: 1200px; }
        header { margin-bottom: 4rem; }
        h1 { font-size: 3rem; font-weight: 800; margin-bottom: 1rem; background: linear-gradient(to right, #fff, #94a3b8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .description { color: var(--text-muted); font-size: 1.1rem; max-width: 800px; }

        .section { margin-bottom: 5rem; scroll-margin-top: 2rem; }
        .section-title { font-size: 1.8rem; margin-bottom: 1.5rem; border-bottom: 2px solid var(--border); padding-bottom: 0.5rem; color: #e2e8f0; }

        /* ENDPOINT CARD */
        .endpoint-card { background: var(--bg-card); border-radius: 1rem; border: 1px solid var(--border); margin-bottom: 2rem; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .endpoint-header { padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1rem; background: rgba(255, 255, 255, 0.02); border-bottom: 1px solid var(--border); }
        
        .method { padding: 0.3rem 0.8rem; border-radius: 0.4rem; font-weight: 800; font-size: 0.75rem; font-family: 'Fira Code', monospace; min-width: 70px; text-align: center; }
        .method.get { background: rgba(16, 185, 129, 0.2); color: #34d399; }
        .method.post { background: rgba(59, 130, 246, 0.2); color: #60a5fa; }
        .method.put { background: rgba(245, 158, 11, 0.2); color: #fbbf24; }
        .method.delete { background: rgba(239, 68, 68, 0.2); color: #f87171; }

        .path { font-family: 'Fira Code', monospace; font-size: 0.95rem; color: #e2e8f0; font-weight: 600; }
        .auth-badge { margin-left: auto; font-size: 0.7rem; color: #fbbf24; background: rgba(251, 191, 36, 0.1); padding: 0.2rem 0.5rem; border-radius: 0.3rem; border: 1px solid rgba(251, 191, 36, 0.2); }

        .endpoint-body { padding: 1.5rem; }
        .endpoint-info { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }
        .info-label { font-weight: 700; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem; color: var(--primary); }
        
        .param-table { width: 100%; border-collapse: collapse; margin-top: 0.5rem; }
        .param-table th { text-align: left; padding: 0.5rem; font-size: 0.8rem; color: var(--text-muted); border-bottom: 1px solid var(--border); }
        .param-table td { padding: 0.75rem 0.5rem; font-size: 0.85rem; border-bottom: 1px solid rgba(51, 65, 85, 0.5); }
        .param-name { font-family: 'Fira Code', monospace; color: #e2e8f0; font-weight: 600; }
        .param-type { color: #94a3b8; font-style: italic; font-size: 0.75rem; }

        .code-block { background: #020617; padding: 1.25rem; border-radius: 0.75rem; font-family: 'Fira Code', monospace; font-size: 0.8rem; color: #cbd5e1; overflow-x: auto; border: 1px solid var(--border); margin-bottom: 1rem; }
        
        footer { margin-top: 5rem; padding: 3rem; border-top: 1px solid var(--border); text-align: center; color: var(--text-muted); font-size: 0.9rem; }

        /* AI CHAT WIDGET */
        .ai-fab {
            position: fixed; bottom: 2rem; right: 2rem; width: 60px; height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--chat-primary));
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 10px 25px -5px rgba(139, 92, 246, 0.5); cursor: pointer;
            z-index: 999; transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            font-size: 1.5rem;
        }
        .ai-fab:hover { transform: scale(1.1); }
        
        .ai-chat-window {
            position: fixed; bottom: 6rem; right: 2rem; width: 380px; height: 500px;
            background: var(--bg-card); border-radius: 1.5rem; border: 1px solid var(--border);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); display: flex; flex-direction: column;
            overflow: hidden; z-index: 998; opacity: 0; pointer-events: none;
            transform: translateY(20px) scale(0.95); transform-origin: bottom right;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .ai-chat-window.open { opacity: 1; pointer-events: auto; transform: translateY(0) scale(1); }
        
        .ai-header {
            background: linear-gradient(135deg, var(--primary), var(--chat-primary)); padding: 1.25rem;
            display: flex; justify-content: space-between; align-items: center;
        }
        .ai-header-title { font-weight: 700; display: flex; align-items: center; gap: 0.5rem; color: white; }
        .ai-close { cursor: pointer; color: rgba(255,255,255,0.8); background: none; border: none; font-size: 1.2rem; }
        
        .ai-body { flex: 1; padding: 1rem; overflow-y: auto; display: flex; flex-direction: column; gap: 1rem; }
        .msg { max-width: 85%; padding: 0.75rem 1rem; border-radius: 1rem; font-size: 0.9rem; line-height: 1.4; }
        .msg-ai { background: rgba(139, 92, 246, 0.1); border: 1px solid rgba(139, 92, 246, 0.2); color: #e2e8f0; align-self: flex-start; border-bottom-left-radius: 0.25rem; }
        .msg-ai code { background: rgba(0,0,0,0.3); padding: 0.1rem 0.3rem; border-radius: 0.2rem; font-family: 'Fira Code', monospace; font-size: 0.8rem; color: #a78bfa; }
        .msg-user { background: var(--primary); color: white; align-self: flex-end; border-bottom-right-radius: 0.25rem; }
        
        .ai-footer { padding: 1rem; border-top: 1px solid var(--border); background: var(--bg-dark); }
        .ai-input-wrap { display: flex; gap: 0.5rem; }
        .ai-input-wrap input { flex: 1; background: var(--bg-card); border: 1px solid var(--border); color: white; padding: 0.75rem 1rem; border-radius: 2rem; outline: none; font-family: 'Inter'; }
        .ai-input-wrap input:focus { border-color: var(--primary); }
        .ai-send { background: var(--primary); color: white; border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.2s; }
        .ai-send:hover { background: var(--primary-hover); }

        /* Loader */
        .typing-indicator { display: flex; gap: 4px; padding: 0.5rem; align-items: center; }
        .dot { width: 6px; height: 6px; background: var(--chat-primary); border-radius: 50%; animation: bounce 1.4s infinite ease-in-out both; }
        .dot:nth-child(1) { animation-delay: -0.32s; }
        .dot:nth-child(2) { animation-delay: -0.16s; }
        @keyframes bounce { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1); } }

        @media (max-width: 1100px) {
            .endpoint-info { grid-template-columns: 1fr; }
            aside { display: none; }
            main { margin-left: 0; padding: 2rem; }
            .ai-chat-window { width: calc(100vw - 4rem); right: 2rem; }
        }
    </style>
</head>
<body>
    <div class="layout">
        <!-- SIDEBAR NAVIGATION -->
        <aside>
            <div class="logo">🚀 SPK SISTEM</div>
            <div class="description" style="font-size: 0.75rem; margin-bottom: 2rem;">API ver. 1.0.0</div>
            
            <div class="nav-group">
                <div class="nav-title">Autentikasi (Sanctum)</div>
                <a href="#post-login" class="nav-link">POST /api/login</a>
                <a href="#post-register" class="nav-link">POST /api/register</a>
                <a href="#get-me" class="nav-link">GET /api/me</a>
                <a href="#post-logout" class="nav-link">POST /api/logout</a>
            </div>

            <div class="nav-group">
                <div class="nav-title">Data Master</div>
                <a href="#res-kelas" class="nav-link">Resource Kelas</a>
                <a href="#res-siswa" class="nav-link">Resource Siswa</a>
                <a href="#get-siswa-kelas" class="nav-link">GET Siswa by Kelas</a>
                <a href="#res-guru" class="nav-link">Resource Guru</a>
                <a href="#get-guru-mapel" class="nav-link">GET Guru by Mapel</a>
                <a href="#res-mapel" class="nav-link">Resource Mapel</a>
            </div>

            <div class="nav-group">
                <div class="nav-title">Jadwal Pelajaran</div>
                <a href="#res-jadwal" class="nav-link">Resource Jadwal</a>
                <a href="#get-jadwal-guru" class="nav-link">GET Jadwal Guru</a>
                <a href="#get-jadwal-kelas" class="nav-link">GET Jadwal Kelas</a>
                <a href="#post-jadwal-batch" class="nav-link">POST Jadwal Batch</a>
            </div>

            <div class="nav-group">
                <div class="nav-title">Presensi & Kehadiran</div>
                <a href="#res-presensi" class="nav-link">Resource Presensi</a>
                <a href="#get-presensi-tgl" class="nav-link">GET Presensi by Tanggal</a>
            </div>

            <div class="nav-group">
                <div class="nav-title">SPK (Ranking)</div>
                <a href="#get-ranking-guru" class="nav-link">GET Ranking Guru</a>
                <a href="#get-ranking-siswa" class="nav-link">GET Ranking Siswa</a>
                <a href="#res-spk-criteria" class="nav-link">Resource Kriteria</a>
                <a href="#res-spk-scores" class="nav-link">Resource Penilaian</a>
            </div>
        </aside>

        <main>
            <header>
                <h1>Dokumentasi API Super Lengkap</h1>
                <p class="description">
                    Referensi resmi backend SPK SISWA. Seluruh request state-modifying (POST, PUT, DELETE) membutuhkan header: <code>Accept: application/json</code> dan <code>Authorization: Bearer {token}</code>.
                </p>
            </header>

            <!-- AUTHENTICATION -->
            <section id="authentication" class="section">
                <h2 class="section-title">1. Autentikasi (Auth)</h2>
                
                <div id="post-login" class="endpoint-card">
                    <div class="endpoint-header"><span class="method post">POST</span><span class="path">/api/login</span></div>
                    <div class="endpoint-body">
                        <div class="endpoint-info">
                            <div>
                                <div class="info-label">Keterangan</div>
                                <p>Endpoint tunggal untuk login Admin dan Guru. Backend otomatis mendeteksi role berdasarkan email.</p>
                                <table class="param-table">
                                    <tr><th>Key</th><th>Tipe</th><th>Wajib</th></tr>
                                    <tr><td><span class="param-name">email</span></td><td><span class="param-type">string</span></td><td>Ya</td></tr>
                                    <tr><td><span class="param-name">password</span></td><td><span class="param-type">string</span></td><td>Ya</td></tr>
                                </table>
                            </div>
                            <div>
                                <div class="info-label">200 Response</div>
                                <div class="code-block">{
  "access_token": "1|abc...",
  "token_type": "bearer",
  "user": { "id": 1, "nama": "Admin" }
}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="get-me" class="endpoint-card">
                    <div class="endpoint-header"><span class="method get">GET</span><span class="path">/api/me</span><span class="auth-badge">Auth Required</span></div>
                    <div class="endpoint-body">
                        <div class="endpoint-info">
                            <div>
                                <div class="info-label">Keterangan</div><p>Mendapatkan data user login saat ini. Jika user adalah Guru, response akan memuat array <code>kelas_asuh_ids</code> dan <code>mapel_ids</code>.</p>
                            </div>
                            <div>
                                <div class="info-label">200 Response</div>
                                <div class="code-block">{
  "id": 1,
  "nama": "Guru A",
  "kelas_asuh_id": 2, // Wali dari kelas ID 2
  "mapel_id": 1 // Mengajar mapel ID 1
}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- DATA MASTER -->
            <section id="master" class="section">
                <h2 class="section-title">2. Data Master (Siswa, Guru, Kelas)</h2>

                <div id="get-siswa-kelas" class="endpoint-card">
                    <div class="endpoint-header"><span class="method get">GET</span><span class="path">/api/siswa/kelas/{kelas_id}</span><span class="auth-badge">Auth</span></div>
                    <div class="endpoint-body">
                        <div class="endpoint-info">
                            <div>
                                <div class="info-label">Keterangan</div>
                                <p>Mengambil daftar seluruh siswa yang tergabung di dalam kelas tertentu. Sangat berguna untuk absensi wali kelas.</p>
                                <p><strong>Path Var:</strong> <code>kelas_id</code> (Integer)</p>
                            </div>
                            <div>
                                <div class="info-label">200 Response (Array)</div>
                                <div class="code-block">[
  { "id": 10, "nama": "Ahmad", "nis": "1001", "kelas_id": 2 }
]</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="res-siswa" class="endpoint-card">
                    <div class="endpoint-header"><span class="method get">GET/POST</span><span class="path">/api/siswa</span><span class="auth-badge">Auth</span></div>
                    <div class="endpoint-body">
                        <p style="margin-bottom: 1rem; color: var(--text-muted);">Standard CRUD operations. <strong>POST</strong> body membutuhkan: <code>nama, nis, jenis_kelamin, kelas_id</code>. Mendukung query params: <code>?kelas_id=1&search=budi</code>.</p>
                    </div>
                </div>

                <div id="res-guru" class="endpoint-card">
                    <div class="endpoint-header"><span class="method get">GET/POST</span><span class="path">/api/guru</span><span class="auth-badge">Auth</span></div>
                    <div class="endpoint-body">
                        <p style="margin-bottom: 1rem; color: var(--text-muted);">Standard CRUD operations untuk Guru. <strong>POST</strong> body: <code>nama, email, nip, jenis_kelamin, password</code>.</p>
                    </div>
                </div>
            </section>

            <!-- JADWAL -->
            <section id="jadwalsection" class="section">
                <h2 class="section-title">3. Penjadwalan</h2>

                <div id="get-jadwal-guru" class="endpoint-card">
                    <div class="endpoint-header"><span class="method get">GET</span><span class="path">/api/jadwal/guru/{guru_id}</span><span class="auth-badge">Auth</span></div>
                    <div class="endpoint-body">
                        <div class="endpoint-info">
                            <div>
                                <div class="info-label">Keterangan</div>
                                <p>Menampilkan jadwal mengajar spesifik untuk 1 guru. Menampilkan relasi <code>kelas</code> dan <code>mapel</code> secara otomatis.</p>
                            </div>
                            <div>
                                <div class="info-label">200 Response</div>
                                <div class="code-block">[
  {
    "id": 5,
    "hari": "Senin",
    "jam_mulai": "08:00",
    "jam_selesai": "09:30",
    "kelas": { "nama_kelas": "X-A" },
    "mapel": { "nama_mapel": "Matematika" }
  }
]</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="post-jadwal-batch" class="endpoint-card">
                    <div class="endpoint-header"><span class="method post">POST</span><span class="path">/api/jadwal/batch</span><span class="auth-badge">Auth</span></div>
                    <div class="endpoint-body">
                        <div class="endpoint-info">
                            <div>
                                <div class="info-label">Keterangan</div>
                                <p>Menyimpan data jadwal secara massal (batch array) ke database dalam satu transaksi.</p>
                            </div>
                            <div>
                                <div class="info-label">Payload Request</div>
                                <div class="code-block">{
  "items": [
    {
      "kelas_id": 1,
      "guru_id": 2,
      "mapel_id": 3,
      "hari": "Senin",
      "jam_mulai": "08:00",
      "jam_selesai": "09:30"
    }
  ]
}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- PRESENSI -->
            <section id="presensisection" class="section">
                <h2 class="section-title">4. Presensi</h2>

                <div id="get-presensi-tgl" class="endpoint-card">
                    <div class="endpoint-header"><span class="method get">GET</span><span class="path">/api/presensi/tanggal/{tanggal}</span><span class="auth-badge">Auth</span></div>
                    <div class="endpoint-body">
                        <div class="endpoint-info">
                            <div>
                                <div class="info-label">Keterangan</div>
                                <p>Mengambil rekapitulasi presensi pada tanggal tertentu (Format YYYY-MM-DD).</p>
                            </div>
                            <div>
                                <div class="info-label">200 Response</div>
                                <div class="code-block">[
  {
    "id": 100,
    "siswa_id": 10,
    "jadwal_id": 5,
    "status": "hadir",
    "waktu_masuk": "08:05:00"
  }
]</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="res-presensi" class="endpoint-card">
                    <div class="endpoint-header"><span class="method post">POST</span><span class="path">/api/presensi</span><span class="auth-badge">Auth</span></div>
                    <div class="endpoint-body">
                        <p style="color: var(--text-muted);">Payload Wajib: <code>siswa_id</code>, <code>jadwal_id</code>, <code>guru_id</code>, <code>tanggal</code>, <code>status (hadir/sakit/izin/alpa)</code>.</p>
                    </div>
                </div>
            </section>

            <!-- SPK -->
            <section id="spksection" class="section">
                <h2 class="section-title">5. SPK Engine (Sistem Pendukung Keputusan)</h2>

                <div id="get-ranking-guru" class="endpoint-card">
                    <div class="endpoint-header"><span class="method get">GET</span><span class="path">/api/spk/ranking-guru</span><span class="auth-badge">Auth</span></div>
                    <div class="endpoint-body">
                        <div class="endpoint-info">
                            <div>
                                <div class="info-label">Keterangan</div>
                                <p>Menghitung dan memunculkan ranking performa Guru menggunakan metode SAW (Simple Additive Weighting). Array sudah diurutkan dari skor tertinggi.</p>
                            </div>
                            <div>
                                <div class="info-label">200 Response</div>
                                <div class="code-block">[
  {
    "guru_id": 2,
    "nama": "Budi Santoso",
    "final_score": 0.95
  }
]</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <footer>
                &copy; {{ date('Y') }} SPK SISTEM - Developed for Excellence
            </footer>
        </main>
    </div>

    <!-- AI AGENT CHAT WIDGET -->
    <div class="ai-fab" id="ai-fab" title="Tanya AI Assistant API">
        🤖
    </div>

    <div class="ai-chat-window" id="ai-chat">
        <div class="ai-header">
            <div class="ai-header-title">🤖 SPK Docs AI Agent</div>
            <button class="ai-close" id="ai-close">&times;</button>
        </div>
        <div class="ai-body" id="ai-body">
            <div class="msg msg-ai">
                Halo! Saya AI Agent khusus untuk Dokumentasi API ini. Jika Anda bingung mencari endpoint atau cara memanggil API, ketikkan pertanyaan Anda di bawah! (Misal: "Cara batch jadwal?" atau "Endpoint login?")
            </div>
        </div>
        <div class="ai-footer">
            <form class="ai-input-wrap" id="ai-form">
                <input type="text" id="ai-input" placeholder="Tanya sesuatu..." autocomplete="off">
                <button type="submit" class="ai-send">➤</button>
            </form>
        </div>
    </div>

    <script>
        // Smooth scrolling
        document.querySelectorAll('.nav-link').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    window.scrollTo({ top: targetElement.offsetTop - 30, behavior: 'smooth' });
                    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });

        // AI Agent Logic
        const fab = document.getElementById('ai-fab');
        const chat = document.getElementById('ai-chat');
        const closeBtn = document.getElementById('ai-close');
        const form = document.getElementById('ai-form');
        const input = document.getElementById('ai-input');
        const body = document.getElementById('ai-body');

        fab.addEventListener('click', () => { chat.classList.add('open'); input.focus(); });
        closeBtn.addEventListener('click', () => chat.classList.remove('open'));

        // Simple KB for the AI Agent
        const knowledgeBase = [
            { keywords: ['login', 'auth', 'masuk'], reply: 'Untuk login, gunakan endpoint <code>POST /api/login</code> dengan payload JSON <code>email</code> dan <code>password</code>. Anda akan menerima bearer token yang wajib dipasang di header <code>Authorization: Bearer {token}</code> untuk request selanjutnya.' },
            { keywords: ['batch', 'jadwal banyak', 'sekaligus'], reply: 'Anda bisa menyimpan banyak jadwal sekaligus menggunakan <code>POST /api/jadwal/batch</code>. Format payloadnya adalah sebuah objek JSON dengan key <code>items</code> yang berisi array data jadwal.' },
            { keywords: ['kelas guru', 'jadwal guru', 'mengajar'], reply: 'Untuk melihat jadwal spesifik seorang guru, panggil <code>GET /api/jadwal/guru/{guru_id}</code>.' },
            { keywords: ['siswa kelas', 'daftar siswa'], reply: 'Anda bisa mendapatkan siswa berdasarkan kelasnya dengan memanggil <code>GET /api/siswa/kelas/{kelas_id}</code>.' },
            { keywords: ['rekap', 'presensi tanggal', 'absensi hari ini'], reply: 'Untuk melihat rekap presensi pada tanggal tertentu, gunakan <code>GET /api/presensi/tanggal/{YYYY-MM-DD}</code>.' },
            { keywords: ['spk', 'ranking', 'peringkat'], reply: 'Sistem pendukung keputusan (SPK) dapat diakses melalui <code>GET /api/spk/ranking-guru</code> atau <code>GET /api/spk/ranking-siswa</code>. Data yang dikembalikan sudah diurutkan berdasarkan skor tertinggi.' },
            { keywords: ['logout', 'keluar'], reply: 'Panggil <code>POST /api/logout</code> dengan header Authorization untuk menghapus sesi token saat ini.' },
            { keywords: ['error 401', 'unauthorized'], reply: 'Error 401 berarti Anda belum mengirimkan Bearer Token di header, atau token tersebut sudah expired. Pastikan header <code>Authorization: Bearer {token}</code> sudah terpasang.' },
            { keywords: ['halo', 'hai', 'bantuan'], reply: 'Halo! Saya di sini untuk membantu Anda mengintegrasikan API ini. Silakan sebutkan kata kunci seperti "login", "jadwal guru", "batch jadwal", atau "ranking spk".' }
        ];

        function addMessage(text, isAi) {
            const div = document.createElement('div');
            div.className = 'msg ' + (isAi ? 'msg-ai' : 'msg-user');
            div.innerHTML = text;
            body.appendChild(div);
            body.scrollTop = body.scrollHeight;
        }

        function showTyping() {
            const div = document.createElement('div');
            div.className = 'msg msg-ai typing-indicator';
            div.id = 'typing';
            div.innerHTML = '<div class="dot"></div><div class="dot"></div><div class="dot"></div>';
            body.appendChild(div);
            body.scrollTop = body.scrollHeight;
        }

        function removeTyping() {
            const el = document.getElementById('typing');
            if (el) el.remove();
        }

        function processInput(userText) {
            const text = userText.toLowerCase();
            let found = false;
            
            for (const item of knowledgeBase) {
                if (item.keywords.some(kw => text.includes(kw))) {
                    setTimeout(() => {
                        removeTyping();
                        addMessage(item.reply, true);
                    }, 800); // Simulate thinking delay
                    found = true;
                    break;
                }
            }

            if (!found) {
                setTimeout(() => {
                    removeTyping();
                    addMessage('Maaf, saya tidak menemukan jawaban spesifik untuk itu di basis data saya. Namun Anda bisa mengecek panel sebelah kiri untuk daftar lengkap resource API yang tersedia.', true);
                }, 1000);
            }
        }

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const text = input.value.trim();
            if (!text) return;
            
            addMessage(text, false);
            input.value = '';
            showTyping();
            processInput(text);
        });
    </script>
</body>
</html>
