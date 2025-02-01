<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClockController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : redirect('/login');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/dashboard', [TeamController::class, 'getAllUserTeams'])->name('dashboard');
    Route::get('/teams/{teamId}', [TeamController::class, 'show'])->name('userTeams');

    /* Teams */
    Route::get('/teams', [TeamController::class, 'index'])->name('teams');
    Route::post('/teams', [TeamController::class, 'store'])->name('createTeam');
    Route::delete('/teams', [TeamController::class, 'destroy'])->name('team.delete');
    Route::post('/teams/join', [TeamController::class, 'joinTeam'])->name('team.join');
    Route::patch('/teams/{teamId}/config', [TeamController::class, 'updateTeamConfig'])->name('team.config.update');

    /* Fetch API */
    Route::get('/teams/{teamId}/clocks', [ClockController::class, 'getCurrentClock']);
    Route::get('/teams/{teamId}/clocks/{clockId}', [ClockController::class, 'show']);
    Route::post('/teams/{teamId}/clocks/{clockId}/change-state', [ClockController::class, 'changeState']);
    Route::post('/teams/{teamId}/clocks/{clockId}/reset-clock', [ClockController::class, 'resetClock']);

    /* Admin */
    Route::get('/admin/users', [AdminController::class, 'usersIndex'])->name('admin.users');
    Route::get('/admin/users/{userId}', [AdminController::class, 'usersEdit'])->name('admin.users.edit');
    Route::patch('/admin/users/{userId}', [AdminController::class, 'usersUpdate'])->name('admin.users.update');
    Route::put('/admin/users/{userId}/password', [AdminController::class, 'usersUpdatePassword'])->name('admin.users.password');
    Route::delete('/admin/users/{userId}', [AdminController::class, 'usersDestroy'])->name('admin.users.destroy');
});

require __DIR__ . '/auth.php';
