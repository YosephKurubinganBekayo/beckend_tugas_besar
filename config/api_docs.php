<?php

return [
    'version' => '1.0.0',
    'base_url' => env('APP_URL', 'http://localhost'),
    'access_key_env' => 'DOCS_ACCESS_KEY',
    'docs_access_key' => env('DOCS_ACCESS_KEY'),
    'auth' => [
        'type' => 'Bearer Token',
        'header' => 'Authorization: Bearer {access_token}',
        'required_headers' => [
            'Accept: application/json',
            'Content-Type: application/json',
        ],
        'notes' => [
            'Gunakan POST /api/login untuk mendapatkan access_token.',
            'Semua endpoint selain /api/login dan /api/register membutuhkan token Sanctum.',
            'Simpan token di client secara aman dan kirim melalui header Authorization.',
        ],
    ],
    'features' => [
        'Autentikasi Admin dan Guru memakai Laravel Sanctum bearer token.',
        'CRUD lengkap untuk Admin, User, Guru, Siswa, Kelas, Mata Pelajaran, Guru Mapel, Jadwal, Presensi, Embedding, Kriteria SPK, dan Skor SPK.',
        'Filter data untuk kebutuhan aplikasi: siswa per kelas, guru per mapel, jadwal per guru, jadwal per kelas, presensi per tanggal, dan ranking per periode.',
        'SPK ranking Guru dan Siswa memakai kalkulator SAW.',
        'Laravel Reverb + WebSocket + Broadcasting Event untuk update data real-time antar device.',
        'Redis dipakai untuk queue broadcasting dan cache.',
        'Chatbot dokumentasi dapat menjawab pertanyaan berdasarkan konfigurasi endpoint di project ini.',
    ],
    'installation' => [
        [
            'title' => 'Install dependency PHP dan Node',
            'commands' => [
                'composer install',
                'npm install',
            ],
        ],
        [
            'title' => 'Siapkan environment',
            'commands' => [
                'copy .env.example .env',
                'php artisan key:generate',
            ],
            'notes' => [
                'Isi DB_CONNECTION, DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD sesuai database lokal.',
                'Isi DOCS_ACCESS_KEY untuk mengunci halaman dokumentasi.',
            ],
        ],
        [
            'title' => 'Konfigurasi Reverb dan Redis',
            'commands' => [
                'BROADCAST_CONNECTION=reverb',
                'QUEUE_CONNECTION=redis',
                'CACHE_STORE=redis',
                'REVERB_HOST=localhost',
                'REVERB_PORT=8080',
                'REVERB_SCHEME=http',
            ],
            'notes' => [
                'Pastikan Redis server berjalan di REDIS_HOST dan REDIS_PORT.',
                'Untuk production, ganti REVERB_APP_KEY dan REVERB_APP_SECRET dengan nilai rahasia.',
            ],
        ],
        [
            'title' => 'Migrasi database dan build asset',
            'commands' => [
                'php artisan migrate --seed',
                'npm run build',
            ],
        ],
        [
            'title' => 'Jalankan development server',
            'commands' => [
                'composer run dev',
            ],
            'notes' => [
                'Script ini menjalankan Laravel server, Reverb, Redis queue worker, log, dan Vite.',
                'Alternatif manual: php artisan serve, php artisan reverb:start, php artisan queue:work redis, npm run dev.',
            ],
        ],
        [
            'title' => 'Aktifkan chatbot AI',
            'commands' => [
                'OPENAI_API_KEY=sk-...',
                'OPENAI_MODEL=gpt-4o-mini',
            ],
            'notes' => [
                'Jika OPENAI_API_KEY kosong, chatbot tetap menjawab dengan fallback lokal berbasis dokumentasi project.',
                'Endpoint chatbot hanya tersedia setelah halaman dokumentasi dibuka memakai DOCS_ACCESS_KEY.',
            ],
        ],
    ],
    'realtime' => [
        'channel' => 'private-app-updates',
        'listen' => '.model.changed',
        'auth_endpoint' => '/api/broadcasting/auth',
        'payload' => [
            'action' => 'created|updated|deleted',
            'model' => 'Nama model, contoh Siswa',
            'table' => 'Nama tabel, contoh siswa',
            'id' => 'Primary key data yang berubah',
            'data' => 'Snapshot data model',
        ],
        'client_example' => <<<'JS'
window.setRealtimeToken(accessToken);

window.listenForAppUpdates((event) => {
    console.log(event.action, event.model, event.id);
    // Refresh list/table sesuai event.table atau event.model.
});
JS,
    ],
    'groups' => [
        [
            'title' => 'Autentikasi',
            'description' => 'Login, registrasi Admin, profil user aktif, logout, dan auth private channel WebSocket.',
            'endpoints' => [
                ['method' => 'POST', 'uri' => '/api/login', 'auth' => false, 'description' => 'Login Admin atau Guru.', 'body' => ['email' => 'required|string|email', 'password' => 'required|string'], 'response' => ['access_token', 'token_type', 'user']],
                ['method' => 'POST', 'uri' => '/api/register', 'auth' => false, 'description' => 'Registrasi Admin baru.', 'body' => ['nama' => 'required|string', 'email' => 'required|string|email|unique', 'password' => 'required|string'], 'response' => ['access_token', 'token_type', 'user']],
                ['method' => 'GET', 'uri' => '/api/me', 'auth' => true, 'description' => 'Mengambil profil user yang sedang login.', 'query' => [], 'response' => ['id', 'nama', 'email', 'role']],
                ['method' => 'POST', 'uri' => '/api/logout', 'auth' => true, 'description' => 'Menghapus token akses saat ini.', 'body' => [], 'response' => ['message']],
                ['method' => 'POST', 'uri' => '/api/broadcasting/auth', 'auth' => true, 'description' => 'Authorize Laravel Echo untuk private channel app-updates.', 'body' => ['socket_id' => 'required|string', 'channel_name' => 'required|string'], 'response' => ['auth']],
            ],
        ],
        [
            'title' => 'Admin',
            'description' => 'CRUD data admin aplikasi.',
            'endpoints' => [
                ['method' => 'GET', 'uri' => '/api/admin', 'auth' => true, 'description' => 'List admin.', 'query' => ['search' => 'optional|string', 'limit' => 'optional|integer']],
                ['method' => 'POST', 'uri' => '/api/admin', 'auth' => true, 'description' => 'Tambah admin.', 'body' => ['nama' => 'required|string', 'email' => 'required|email', 'password' => 'required|string', 'foto_url' => 'optional|string']],
                ['method' => 'GET', 'uri' => '/api/admin/{admin}', 'auth' => true, 'description' => 'Detail admin.'],
                ['method' => 'PUT/PATCH', 'uri' => '/api/admin/{admin}', 'auth' => true, 'description' => 'Update admin.', 'body' => ['nama' => 'optional|string', 'email' => 'optional|email', 'password' => 'optional|string', 'foto_url' => 'optional|string']],
                ['method' => 'DELETE', 'uri' => '/api/admin/{admin}', 'auth' => true, 'description' => 'Hapus admin.'],
            ],
        ],
        [
            'title' => 'Kelas',
            'description' => 'CRUD kelas dan wali kelas.',
            'endpoints' => [
                ['method' => 'GET', 'uri' => '/api/kelas', 'auth' => true, 'description' => 'List kelas.', 'query' => ['search' => 'optional|string', 'wali_kelas_id' => 'optional|integer', 'limit' => 'optional|integer']],
                ['method' => 'POST', 'uri' => '/api/kelas', 'auth' => true, 'description' => 'Tambah kelas.', 'body' => ['nama_kelas' => 'required|string', 'wali_kelas_id' => 'optional|exists:guru,id']],
                ['method' => 'GET', 'uri' => '/api/kelas/{kelas}', 'auth' => true, 'description' => 'Detail kelas beserta wali kelas dan jumlah siswa/jadwal.'],
                ['method' => 'PUT/PATCH', 'uri' => '/api/kelas/{kelas}', 'auth' => true, 'description' => 'Update kelas.', 'body' => ['nama_kelas' => 'optional|string', 'wali_kelas_id' => 'optional|exists:guru,id']],
                ['method' => 'DELETE', 'uri' => '/api/kelas/{kelas}', 'auth' => true, 'description' => 'Hapus kelas.'],
            ],
        ],
        [
            'title' => 'Siswa',
            'description' => 'CRUD siswa, filter siswa per kelas, dan status embedding.',
            'endpoints' => [
                ['method' => 'GET', 'uri' => '/api/siswa', 'auth' => true, 'description' => 'List siswa.', 'query' => ['search' => 'optional|string', 'kelas_id' => 'optional|integer', 'limit' => 'optional|integer']],
                ['method' => 'POST', 'uri' => '/api/siswa', 'auth' => true, 'description' => 'Tambah siswa.', 'body' => ['nama' => 'required|string', 'nis' => 'required|string|unique', 'jenis_kelamin' => 'required|string', 'kelas_id' => 'required|exists:kelas,id', 'alamat' => 'optional|string', 'foto_url' => 'optional|string']],
                ['method' => 'GET', 'uri' => '/api/siswa/kelas/{kelas}', 'auth' => true, 'description' => 'List siswa berdasarkan kelas.'],
                ['method' => 'GET', 'uri' => '/api/siswa/{siswa}', 'auth' => true, 'description' => 'Detail siswa beserta kelas dan jumlah embedding.'],
                ['method' => 'PUT/PATCH', 'uri' => '/api/siswa/{siswa}', 'auth' => true, 'description' => 'Update siswa.', 'body' => ['nama' => 'optional|string', 'nis' => 'optional|string', 'jenis_kelamin' => 'optional|string', 'kelas_id' => 'optional|exists:kelas,id', 'alamat' => 'optional|string', 'foto_url' => 'optional|string', 'embedding_status' => 'optional|string']],
                ['method' => 'DELETE', 'uri' => '/api/siswa/{siswa}', 'auth' => true, 'description' => 'Hapus siswa.'],
            ],
        ],
        [
            'title' => 'Guru',
            'description' => 'CRUD guru, relasi mata pelajaran, dan filter guru berdasarkan mapel.',
            'endpoints' => [
                ['method' => 'GET', 'uri' => '/api/guru', 'auth' => true, 'description' => 'List guru.', 'query' => ['search' => 'optional|string', 'mapel_id' => 'optional|integer', 'limit' => 'optional|integer']],
                ['method' => 'POST', 'uri' => '/api/guru', 'auth' => true, 'description' => 'Tambah guru.', 'body' => ['nama' => 'required|string', 'email' => 'required|email', 'password' => 'required|string', 'nip' => 'required|string', 'jenis_kelamin' => 'required|string', 'foto_url' => 'optional|string']],
                ['method' => 'GET', 'uri' => '/api/guru/mapel/{mapel}', 'auth' => true, 'description' => 'List guru berdasarkan mata pelajaran.'],
                ['method' => 'GET', 'uri' => '/api/guru/{guru}', 'auth' => true, 'description' => 'Detail guru.'],
                ['method' => 'PUT/PATCH', 'uri' => '/api/guru/{guru}', 'auth' => true, 'description' => 'Update guru.', 'body' => ['nama' => 'optional|string', 'email' => 'optional|email', 'password' => 'optional|string', 'nip' => 'optional|string', 'jenis_kelamin' => 'optional|string']],
                ['method' => 'DELETE', 'uri' => '/api/guru/{guru}', 'auth' => true, 'description' => 'Hapus guru.'],
            ],
        ],
        [
            'title' => 'Mata Pelajaran & Guru Mapel',
            'description' => 'CRUD mapel dan relasi guru dengan mapel.',
            'endpoints' => [
                ['method' => 'GET', 'uri' => '/api/mata-pelajaran', 'auth' => true, 'description' => 'List mata pelajaran.'],
                ['method' => 'POST', 'uri' => '/api/mata-pelajaran', 'auth' => true, 'description' => 'Tambah mata pelajaran.', 'body' => ['nama_mapel' => 'required|string', 'kode_mapel' => 'optional|string']],
                ['method' => 'GET', 'uri' => '/api/mata-pelajaran/{mataPelajaran}', 'auth' => true, 'description' => 'Detail mata pelajaran.'],
                ['method' => 'PUT/PATCH', 'uri' => '/api/mata-pelajaran/{mataPelajaran}', 'auth' => true, 'description' => 'Update mata pelajaran.'],
                ['method' => 'DELETE', 'uri' => '/api/mata-pelajaran/{mataPelajaran}', 'auth' => true, 'description' => 'Hapus mata pelajaran.'],
                ['method' => 'GET', 'uri' => '/api/guru-mapel', 'auth' => true, 'description' => 'List relasi guru dan mapel.'],
                ['method' => 'POST', 'uri' => '/api/guru-mapel', 'auth' => true, 'description' => 'Tambah relasi guru-mapel.', 'body' => ['guru_id' => 'required|exists:guru,id', 'mapel_id' => 'required|exists:mata_pelajaran,id']],
                ['method' => 'GET', 'uri' => '/api/guru-mapel/{guruMapel}', 'auth' => true, 'description' => 'Detail relasi guru-mapel.'],
                ['method' => 'PUT/PATCH', 'uri' => '/api/guru-mapel/{guruMapel}', 'auth' => true, 'description' => 'Update relasi guru-mapel.'],
                ['method' => 'DELETE', 'uri' => '/api/guru-mapel/{guruMapel}', 'auth' => true, 'description' => 'Hapus relasi guru-mapel.'],
            ],
        ],
        [
            'title' => 'Jadwal',
            'description' => 'CRUD jadwal, filter per guru/kelas, dan batch insert jadwal.',
            'endpoints' => [
                ['method' => 'GET', 'uri' => '/api/jadwal', 'auth' => true, 'description' => 'List jadwal.', 'query' => ['guru_id' => 'optional|integer', 'kelas_id' => 'optional|integer', 'mapel_id' => 'optional|integer', 'hari' => 'optional|string']],
                ['method' => 'POST', 'uri' => '/api/jadwal', 'auth' => true, 'description' => 'Tambah jadwal.', 'body' => ['kelas_id' => 'required|exists:kelas,id', 'guru_id' => 'required|exists:guru,id', 'mapel_id' => 'required|exists:mata_pelajaran,id', 'hari' => 'required|string', 'jam_mulai' => 'required', 'jam_selesai' => 'required']],
                ['method' => 'POST', 'uri' => '/api/jadwal/batch', 'auth' => true, 'description' => 'Tambah banyak jadwal dalam satu request.', 'body' => ['items' => 'required|array', 'items.*.kelas_id' => 'required', 'items.*.guru_id' => 'required', 'items.*.mapel_id' => 'required', 'items.*.hari' => 'required', 'items.*.jam_mulai' => 'required', 'items.*.jam_selesai' => 'required']],
                ['method' => 'GET', 'uri' => '/api/jadwal/guru/{guru}', 'auth' => true, 'description' => 'List jadwal berdasarkan guru.'],
                ['method' => 'GET', 'uri' => '/api/jadwal/kelas/{kelas}', 'auth' => true, 'description' => 'List jadwal berdasarkan kelas.'],
                ['method' => 'GET', 'uri' => '/api/jadwal/{jadwal}', 'auth' => true, 'description' => 'Detail jadwal beserta kelas, mapel, dan guru.'],
                ['method' => 'PUT/PATCH', 'uri' => '/api/jadwal/{jadwal}', 'auth' => true, 'description' => 'Update jadwal.'],
                ['method' => 'DELETE', 'uri' => '/api/jadwal/{jadwal}', 'auth' => true, 'description' => 'Hapus jadwal.'],
            ],
        ],
        [
            'title' => 'Presensi',
            'description' => 'CRUD presensi dan rekap berdasarkan tanggal.',
            'endpoints' => [
                ['method' => 'GET', 'uri' => '/api/presensi', 'auth' => true, 'description' => 'List presensi.', 'query' => ['tanggal' => 'optional|date', 'siswa_id' => 'optional|integer', 'guru_id' => 'optional|integer', 'jadwal_id' => 'optional|integer']],
                ['method' => 'POST', 'uri' => '/api/presensi', 'auth' => true, 'description' => 'Tambah presensi.', 'body' => ['siswa_id' => 'required|exists:siswa,id', 'jadwal_id' => 'required|exists:jadwal,id', 'guru_id' => 'required|exists:guru,id', 'status' => 'required|string', 'tanggal' => 'required|date', 'jam_presensi' => 'optional']],
                ['method' => 'GET', 'uri' => '/api/presensi/tanggal/{tanggal}', 'auth' => true, 'description' => 'Rekap presensi berdasarkan tanggal YYYY-MM-DD.'],
                ['method' => 'GET', 'uri' => '/api/presensi/{presensi}', 'auth' => true, 'description' => 'Detail presensi.'],
                ['method' => 'PUT/PATCH', 'uri' => '/api/presensi/{presensi}', 'auth' => true, 'description' => 'Update presensi.'],
                ['method' => 'DELETE', 'uri' => '/api/presensi/{presensi}', 'auth' => true, 'description' => 'Hapus presensi.'],
            ],
        ],
        [
            'title' => 'Embedding',
            'description' => 'CRUD face embedding siswa.',
            'endpoints' => [
                ['method' => 'GET', 'uri' => '/api/embedding', 'auth' => true, 'description' => 'List embedding.', 'query' => ['siswa_id' => 'optional|integer', 'limit' => 'optional|integer']],
                ['method' => 'POST', 'uri' => '/api/embedding', 'auth' => true, 'description' => 'Tambah embedding.', 'body' => ['siswa_id' => 'required|exists:siswa,id', 'embedding' => 'required|array|string']],
                ['method' => 'GET', 'uri' => '/api/embedding/{embedding}', 'auth' => true, 'description' => 'Detail embedding.'],
                ['method' => 'PUT/PATCH', 'uri' => '/api/embedding/{embedding}', 'auth' => true, 'description' => 'Update embedding.'],
                ['method' => 'DELETE', 'uri' => '/api/embedding/{embedding}', 'auth' => true, 'description' => 'Hapus embedding.'],
            ],
        ],
        [
            'title' => 'SPK',
            'description' => 'Kriteria, skor, dan ranking SPK.',
            'endpoints' => [
                ['method' => 'GET', 'uri' => '/api/spk/ranking-guru', 'auth' => true, 'description' => 'Ranking guru.', 'query' => ['periode' => 'optional|string']],
                ['method' => 'GET', 'uri' => '/api/spk/ranking-siswa', 'auth' => true, 'description' => 'Ranking siswa.', 'query' => ['periode' => 'optional|string']],
                ['method' => 'GET', 'uri' => '/api/spk/criteria', 'auth' => true, 'description' => 'List kriteria SPK.'],
                ['method' => 'POST', 'uri' => '/api/spk/criteria', 'auth' => true, 'description' => 'Tambah kriteria SPK.', 'body' => ['nama' => 'required|string', 'kode' => 'required|string', 'bobot' => 'required|numeric', 'tipe' => 'required|string']],
                ['method' => 'GET', 'uri' => '/api/spk/criteria/{criterion}', 'auth' => true, 'description' => 'Detail kriteria SPK.'],
                ['method' => 'PUT/PATCH', 'uri' => '/api/spk/criteria/{criterion}', 'auth' => true, 'description' => 'Update kriteria SPK.'],
                ['method' => 'DELETE', 'uri' => '/api/spk/criteria/{criterion}', 'auth' => true, 'description' => 'Hapus kriteria SPK.'],
                ['method' => 'GET', 'uri' => '/api/spk/scores', 'auth' => true, 'description' => 'List skor SPK.'],
                ['method' => 'POST', 'uri' => '/api/spk/scores', 'auth' => true, 'description' => 'Tambah skor SPK.', 'body' => ['criterion_id' => 'required|exists:spk_criteria,id', 'scorable_type' => 'required|string', 'scorable_id' => 'required|integer', 'nilai' => 'required|numeric', 'periode' => 'required|string']],
                ['method' => 'GET', 'uri' => '/api/spk/scores/{score}', 'auth' => true, 'description' => 'Detail skor SPK.'],
                ['method' => 'PUT/PATCH', 'uri' => '/api/spk/scores/{score}', 'auth' => true, 'description' => 'Update skor SPK.'],
                ['method' => 'DELETE', 'uri' => '/api/spk/scores/{score}', 'auth' => true, 'description' => 'Hapus skor SPK.'],
            ],
        ],
        [
            'title' => 'Users',
            'description' => 'CRUD user bawaan aplikasi.',
            'endpoints' => [
                ['method' => 'GET', 'uri' => '/api/users', 'auth' => true, 'description' => 'List user.'],
                ['method' => 'POST', 'uri' => '/api/users', 'auth' => true, 'description' => 'Tambah user.', 'body' => ['name' => 'required|string', 'email' => 'required|email', 'password' => 'required|string']],
                ['method' => 'GET', 'uri' => '/api/users/{user}', 'auth' => true, 'description' => 'Detail user.'],
                ['method' => 'PUT/PATCH', 'uri' => '/api/users/{user}', 'auth' => true, 'description' => 'Update user.'],
                ['method' => 'DELETE', 'uri' => '/api/users/{user}', 'auth' => true, 'description' => 'Hapus user.'],
            ],
        ],
    ],
];
