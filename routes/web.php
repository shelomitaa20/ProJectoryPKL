<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TeamController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    // ADMIN
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // Profile 
    Route::get('/admin/profile/edit', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/admin/profile/update', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::put('/admin/profile/update-password', [ProfileController::class, 'updatePassword'])->name('admin.profile.update.password');
    // User Management 
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users');
    Route::post('/admin/users', [AdminController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{id}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
    // Project 
    Route::get('/admin/projects', [ProjectController::class, 'index'])->name('admin.projects');
    Route::post('/admin/projects/store', [ProjectController::class, 'store'])->name('admin.projects.store');
    Route::get('/admin/projects/{id}/edit', [ProjectController::class, 'edit'])->name('admin.projects.edit');
    Route::put('/admin/projects/{id}', [ProjectController::class, 'update'])->name('admin.projects.update');
    Route::delete('/admin/projects/{id}', [ProjectController::class, 'destroy'])->name('admin.projects.destroy');
    Route::get('/admin/projects/{id}', [ProjectController::class, 'detail'])->name('admin.projects.detail');
    // Report 
    Route::get('/admin/reports', [ReportController::class, 'index'])->name('admin.reports');
    Route::get('/report/{report_id}/printUser/{format}', [ReportController::class, 'printUserReport'])->name('report.printUser');
    Route::get('/report/{report_id}/printProject/{format}', [ReportController::class, 'printProjectReport'])->name('report.printProject');

    // TEAM
    Route::get('/team/dashboard', [TeamController::class, 'dashboard'])->name('team.dashboard');
    // Profile 
    Route::get('/team/profile/edit', [ProfileController::class, 'edit'])->name('team.profile.edit');
    Route::put('/team/profile/update', [ProfileController::class, 'update'])->name('team.profile.update');
    Route::put('/team/profile/update-password', [ProfileController::class, 'updatePassword'])->name('team.profile.update.password');
    // Project 
    Route::get('/team/projects', [ProjectController::class, 'index'])->name('team.projects');
    Route::post('/team/projects/store', [ProjectController::class, 'store'])->name('team.projects.store');
    Route::get('/team/projects/{id}/edit', [ProjectController::class, 'edit'])->name('team.projects.edit');
    Route::put('/team/projects/{id}', [ProjectController::class, 'update'])->name('team.projects.update');
    Route::delete('/team/projects/{id}', [ProjectController::class, 'destroy'])->name('team.projects.destroy');
    Route::get('/team/projects/{id}', [ProjectController::class, 'detail'])->name('team.projects.detail');

    // Task Routes (General)
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{id}/progress', [TaskController::class, 'progress'])->name('tasks.progress');
    Route::put('/tasks/{id}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::put('/tasks/{id}/approve', [TaskController::class, 'approveCompletion'])->name('tasks.approve');
    Route::put('/tasks/{id}/reject', [TaskController::class, 'rejectCompletion'])->name('tasks.reject');
    Route::put('/tasks/{id}/attach-file', [TaskController::class, 'attachFile'])->name('tasks.attachFile');
    Route::put('/tasks/{id}/cancel', [TaskController::class, 'cancel'])->name('tasks.cancel');
});
