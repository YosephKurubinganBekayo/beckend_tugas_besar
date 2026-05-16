<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Locked - API Documentation</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f172a;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            background: #1e293b;
            padding: 2.5rem;
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            text-align: center;
            border: 1px solid #334155;
        }
        h1 { font-size: 1.5rem; margin-bottom: 0.5rem; font-weight: 800; }
        p { color: #94a3b8; margin-bottom: 2rem; font-size: 0.9rem; }
        input {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 0.5rem;
            color: white;
            margin-bottom: 1rem;
            font-size: 1rem;
            text-align: center;
        }
        button {
            width: 100%;
            padding: 0.75rem;
            background: #6366f1;
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        button:hover { background: #4f46e5; }
        .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">🔐</div>
        <h1>API Documentation</h1>
        <p>Silakan masukkan kunci akses untuk melihat dokumentasi teknis.</p>
        <form action="/" method="GET">
            <input type="password" name="key" placeholder="Access Key..." required autofocus>
            <button type="submit">Buka Dokumentasi</button>
        </form>
    </div>
</body>
</html>
