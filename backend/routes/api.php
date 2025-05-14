<?php

use App\Http\Controllers\GlobalPoolController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth

Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password'=> ['required', 'string', 'min:8'],
    ]);

    $user = User::create([
        'name'=> $validated['name'],
        'email'=> $validated['email'],
        'password'=> Hash::make($validated['password']),
    ]);

    return response()->json(['message' => 'Registration successful', 'user' => $user]);
});

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return response()->json(['message' => 'Logged in']);
    }

    return response()->json(['message' => 'Invalid credentials'], 401);
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return response()->json(['message'=> 'Logged out']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/globalPool/balance', [GlobalPoolController::class, 'index']);
