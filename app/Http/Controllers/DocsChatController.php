<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DocsChatController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        abort_unless($request->session()->get('docs_access'), 403);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $context = $this->documentationContext();
        $answer = $this->askAi($validated['message'], $context)
            ?? $this->localAnswer($validated['message'], $context);

        return response()->json([
            'answer' => $answer,
            'source' => config('services.openai.key') ? 'ai' : 'local',
        ]);
    }

    private function askAi(string $message, string $context): ?string
    {
        $apiKey = config('services.openai.key');

        if (! $apiKey) {
            return null;
        }

        $response = Http::withToken($apiKey)
            ->timeout(20)
            ->retry(2, 300)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('services.openai.model', 'gpt-4o-mini'),
                'messages' => [
                    [
                        'role' => 'developer',
                        'content' => 'Jawab sebagai asisten dokumentasi API Laravel 13. Gunakan hanya konteks project yang diberikan. Jika informasi tidak tersedia, katakan tidak ada di dokumentasi project. Jawab dalam Bahasa Indonesia, ringkas, praktis, dan sertakan endpoint atau command jika relevan.',
                    ],
                    [
                        'role' => 'user',
                        'content' => "KONTEKS PROJECT:\n{$context}\n\nPERTANYAAN:\n{$message}",
                    ],
                ],
                'temperature' => 0.2,
            ]);

        if (! $response->successful()) {
            report('Docs AI request failed: '.$response->body());

            return null;
        }

        return Arr::get($response->json(), 'choices.0.message.content');
    }

    private function localAnswer(string $message, string $context): string
    {
        $needle = Str::lower($message);
        $docs = config('api_docs');
        $matches = [];

        foreach ($docs['groups'] as $group) {
            foreach ($group['endpoints'] as $endpoint) {
                $haystack = Str::lower($group['title'].' '.$endpoint['method'].' '.$endpoint['uri'].' '.$endpoint['description']);

                if (Str::contains($haystack, explode(' ', $needle))) {
                    $matches[] = $endpoint['method'].' '.$endpoint['uri'].' - '.$endpoint['description'];
                }
            }
        }

        if ($matches !== []) {
            return "Saya menemukan endpoint terkait:\n- ".implode("\n- ", array_slice($matches, 0, 8));
        }

        if (Str::contains($needle, ['install', 'instal', 'reverb', 'redis', 'websocket'])) {
            return "Instalasi ringkas:\n- composer install\n- npm install\n- copy .env.example .env\n- php artisan key:generate\n- set BROADCAST_CONNECTION=reverb, QUEUE_CONNECTION=redis, CACHE_STORE=redis\n- jalankan Redis\n- php artisan migrate --seed\n- composer run dev";
        }

        if (Str::contains($needle, ['token', 'api key', 'login', 'authorization'])) {
            return "Gunakan POST /api/login dengan email dan password. Ambil access_token dari response, lalu kirim header Authorization: Bearer {access_token} dan Accept: application/json untuk endpoint protected.";
        }

        return "Saya belum menemukan jawaban spesifik di dokumentasi lokal. Coba sebutkan nama fitur seperti siswa, guru, jadwal, presensi, spk, embedding, reverb, redis, login, atau endpoint yang ingin dipakai.";
    }

    private function documentationContext(): string
    {
        $docs = config('api_docs');
        $lines = [
            'Nama: SPK SISWA API',
            'Versi: '.$docs['version'],
            'Base URL: '.$docs['base_url'],
            'Auth: '.$docs['auth']['header'],
            'Fitur: '.implode('; ', $docs['features']),
            'Realtime: channel '.$docs['realtime']['channel'].', event '.$docs['realtime']['listen'].', auth '.$docs['realtime']['auth_endpoint'],
        ];

        foreach ($docs['installation'] as $step) {
            $lines[] = 'Install - '.$step['title'].': '.implode(', ', $step['commands']);
        }

        foreach ($docs['groups'] as $group) {
            $lines[] = 'Group: '.$group['title'].' - '.$group['description'];

            foreach ($group['endpoints'] as $endpoint) {
                $details = [
                    $endpoint['method'].' '.$endpoint['uri'],
                    $endpoint['auth'] ? 'auth required' : 'public',
                    $endpoint['description'],
                ];

                if (isset($endpoint['query'])) {
                    $details[] = 'query: '.json_encode($endpoint['query']);
                }

                if (isset($endpoint['body'])) {
                    $details[] = 'body: '.json_encode($endpoint['body']);
                }

                $lines[] = implode(' | ', $details);
            }
        }

        return implode("\n", $lines);
    }
}
