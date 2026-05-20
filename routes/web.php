<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocsChatController;

Route::get('/', function (Illuminate\Http\Request $request) {
    $accessKey = (string) config('api_docs.docs_access_key');
    $submittedKey = (string) $request->query('key', '');

    if ($accessKey !== '' && hash_equals($accessKey, $submittedKey)) {
        session(['docs_access' => true]);
    }

    if (! $request->session()->get('docs_access')) {
        return view('welcome_locked');
    }

    return view('welcome');
});

Route::post('/docs/chat', DocsChatController::class)
    ->middleware('throttle:30,1')
    ->name('docs.chat');
