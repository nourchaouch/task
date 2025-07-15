<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResponsableController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\DashboardController;

// Authentication Routes
Route::middleware(['web'])->group(function () {
    // Guest routes
    Route::middleware(['guest'])->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
        Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register.submit');
    });
    
    // Logout route (requires authentication)
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout')
        ->middleware('auth');
});

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home.index');

// Member Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::get('/members/{member}', [MemberController::class, 'show'])->name('members.show');
});

// All protected routes are in the auth middleware group below

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Manager Dashboard
    Route::get('/dashboard/manager', [DashboardController::class, 'managerDashboard'])
        ->name('dashboard.manager')
        ->middleware('role:responsable');
        
    // Member Dashboard
    Route::get('/dashboard/member', [DashboardController::class, 'memberDashboard'])
        ->name('dashboard.member')
        ->middleware('role:membre');
});

// Protected routes
Route::middleware(['web', 'auth'])->group(function () {
    
    // Projects Routes
    Route::prefix('projects')->group(function () {
        Route::get('/', [ProjectsController::class, 'index'])->name('projects.index');
        Route::get('/create', [ProjectsController::class, 'create'])->name('projects.create');
        Route::post('/', [ProjectsController::class, 'store'])->name('projects.store');
        Route::get('/{project}', [ProjectsController::class, 'show'])->name('projects.show');
        Route::get('/{project}/edit', [ProjectsController::class, 'edit'])->name('projects.edit');
        Route::put('/{project}', [ProjectsController::class, 'update'])->name('projects.update');
        Route::delete('/{project}', [ProjectsController::class, 'destroy'])->name('projects.destroy');
    });

    // Tasks Routes
    Route::prefix('tasks')->group(function () {
        Route::get('/', [TasksController::class, 'index'])->name('tasks.index');
        Route::get('/create', [TasksController::class, 'create'])->name('tasks.create');
        Route::post('/', [TasksController::class, 'store'])->name('tasks.store');
        Route::get('/{task}', [TasksController::class, 'show'])->name('tasks.show');
        Route::get('/{task}/edit', [TasksController::class, 'edit'])->name('tasks.edit');
        Route::put('/{task}', [TasksController::class, 'update'])->name('tasks.update');
        Route::delete('/{task}', [TasksController::class, 'destroy'])->name('tasks.destroy');
        Route::patch('/{task}/status', [TasksController::class, 'updateStatus'])->name('tasks.updateStatus');
    });
});
