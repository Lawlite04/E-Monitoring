<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\LeaderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\ProjectController;
use App\Models\Client;
use App\Models\Leader;
use App\Models\Project;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $leader = Leader::count();
    $client = Client::count();
    $project = Project::count();

    return Inertia::render('Dashboard', [
        'leader' => $leader,
        'client' => $client,
        'project' => $project
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function() {
    Route::get('/leader', [LeaderController::class, 'index'])->name('leader.home');
    Route::post('/leader', [LeaderController::class, 'store'])->name('leader.store');
    Route::post('/leader/{id}/update', [LeaderController::class, 'update'])->name('leader.update');
    Route::delete('/leader/{id}/delete', [LeaderController::class, 'destroy'])->name('leader.destroy');
});

Route::middleware('auth')->group(function() {
    Route::get('/client', [ClientController::class, 'index'])->name('client.home');
    Route::post('/client', [ClientController::class, 'store'])->name('client.store');
    Route::post('/client/{id}/update', [ClientController::class, 'update'])->name('client.update');
    Route::delete('/client/{id}/delete', [ClientController::class, 'destroy'])->name('client.destroy');
});

Route::middleware('auth')->group(function() {
    Route::get('/project', [ProjectController::class, 'index'])->name('project.home');
    Route::get('/project/{id}/detail', [ProjectController::class, 'detail'])->name('project.detail');
    Route::post('/project', [ProjectController::class, 'store'])->name('project.store');
    Route::post('/project/{id}/update', [ProjectController::class, 'update'])->name('project.update');
    Route::delete('/project/{id}/delete', [ProjectController::class, 'destroy'])->name('project.destroy');

    Route::post('/progress/{project_id}', [ProgressController::class, 'update'])->name('progress.update');
});

require __DIR__.'/auth.php';
