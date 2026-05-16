<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function (Illuminate\Http\Request $request) {
    // Kunci akses default: spk123
    $accessKey = env('DOCS_ACCESS_KEY', '#bekayo25YB');
    
    if ($request->get('key') === $accessKey) {
        session(['docs_access' => true]);
    }

    if (!session('docs_access')) {
        return view('welcome_locked');
    }

    return view('welcome');
});
