<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TalkController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
});

Route::get('dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('talks/create', [TalkController::class, 'create'])->name('talks.create');
    Route::get('talks', [TalkController::class, 'index'])->name('talks.index');
    Route::get('talks/{talk}', [TalkController::class, 'show'])->name('talks.show')->can('view', 'talk');
    Route::patch('talks/{talk}', [TalkController::class, 'update'])->name('talks.update')->can('update', 'talk');
    Route::get('talks/{talk}/edit', [TalkController::class, 'edit'])->name('talks.edit');
    Route::delete('talks/{talk}', [TalkController::class, 'destroy'])->name('talks.destroy')->can('delete', 'talk');
    Route::post('talks', [TalkController::class, 'store'])->name('talks.store');
});

require __DIR__ . '/auth.php';

Route::get('auth/redirect', function () {
    return Socialite::driver('github')->redirect();
});

Route::get('auth/callback', function () {
    $githubUser = Socialite::driver('github')->user();

    $user = User::updateOrCreate([
        'github_id' => $githubUser->id,
    ], [
        'name' => $githubUser->name,
        'email' => $githubUser->email,
        'github_token' => $githubUser->token,
        'github_refresh_token' => $githubUser->refreshToken,
        'avatar' => $githubUser->avatar,
    ]);

    Auth::login($user);

    return redirect('/dashboard');
});
