<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ChallengeInviteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/timer', function () {
        return view('timer');
    })->name('timer');

    Route::get('/challenges', function () {
        return view('challenges');
    })->name('challenges');

    Route::get('/achievements', function () {
        return view('achievements');
    })->name('achievements');

    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
});

// Unified API Routes (Wrapped in web middleware for session support)
Route::prefix('api')->group(function () {
    // Public API routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/challenges', [ChallengeController::class, 'index']);
    Route::get('/achievements', [AchievementController::class, 'index']);
    Route::get('/users/{userId}/profile', [UserController::class, 'profile']);
    Route::get('/leaderboard', [UserController::class, 'leaderboard']);
    Route::post('/invites/{challenge}', [ChallengeInviteController::class, 'store']);

    // Protected API routes
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::get('/user', [UserController::class, 'show']);
        Route::put('/user', [UserController::class, 'update']);
        Route::get('/sessions', [SessionController::class, 'index']);
        Route::post('/sessions', [SessionController::class, 'store']);
        Route::get('/sessions/stats', [SessionController::class, 'stats']);
        Route::get('/sessions/{id}', [SessionController::class, 'show']);
        Route::delete('/sessions/{id}', [SessionController::class, 'destroy']);
        Route::get('/user/challenges', [ChallengeController::class, 'userChallenges']);
        Route::post('/challenges/{challenge}/join', [ChallengeController::class, 'joinChallenge']);
        Route::put('/challenges/{challenge}/progress', [ChallengeController::class, 'updateProgress']);
        Route::get('/challenges/{challenge}', [ChallengeController::class, 'show']);
        Route::get('/user/achievements', [AchievementController::class, 'userAchievements']);
        Route::post('/achievements/{achievement}/unlock', [AchievementController::class, 'unlock']);
        Route::get('/achievements/{achievement}', [AchievementController::class, 'show']);
        Route::get('/tasks', [TaskController::class, 'index']);
        Route::post('/tasks', [TaskController::class, 'store']);
        Route::put('/tasks/{id}', [TaskController::class, 'update']);
        Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
        Route::get('/invites', [ChallengeInviteController::class, 'index']);
        Route::post('/challenges/{challenge}/invite', [ChallengeInviteController::class, 'store']);
        Route::post('/invites/{id}/accept', [ChallengeInviteController::class, 'accept']);
        Route::post('/invites/{id}/decline', [ChallengeInviteController::class, 'decline']);
    });
});

