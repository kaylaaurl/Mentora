<?php

use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TutoringSessionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tutors', [TutoringSessionController::class, 'index'])->name('tutors.index');
Route::get('/subjects/{subject}', [TutoringSessionController::class, 'bySubject'])->name('tutors.subject');
Route::get('/articles', function () { return view('articles.index'); })->name('articles.index');
Route::get('/tutors/{tutor}', function ($tutor) { return view('tutors.show', ['tutor' => $tutor]); })->name('tutors.show');
Route::get('/subjects/{subject}', function ($subject) { return view('subjects.show', ['subject' => $subject]); })->name('subjects.show');

// Authentication Routes
require __DIR__ . '/auth.php';

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Tutor Routes
Route::middleware(['auth', \App\Http\Middleware\EnsureUserRole::class . ':tutor'])->group(function () {
    Route::get('/tutor/profile', [ProfileController::class, 'tutorProfile'])->name('tutor.profile');
    Route::patch('/tutor/profile', [ProfileController::class, 'updateTutorProfile'])->name('tutor.profile.update');
    Route::get('/tutor/sessions', [TutoringSessionController::class, 'tutorSessions'])->name('tutor.sessions');
    Route::patch('/sessions/{session}/status', [TutoringSessionController::class, 'updateStatus'])->name('sessions.update-status');
});

// Tutee Routes
Route::middleware(['auth', \App\Http\Middleware\EnsureUserRole::class . ':tutee'])->group(function () {
    Route::get('/tutee/sessions', [TutoringSessionController::class, 'tuteeSessions'])->name('tutee.sessions');
    Route::get('/tutors/{tutor}/book', [TutoringSessionController::class, 'create'])->name('sessions.create');
    Route::post('/tutors/{tutor}/book', [TutoringSessionController::class, 'store'])->name('sessions.store');
});

// Session Management Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/sessions/{session}', [TutoringSessionController::class, 'show'])->name('sessions.show');
    Route::post('/sessions/{session}/review', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Admin Routes
Route::middleware(['auth', \App\Http\Middleware\EnsureUserRole::class . ':admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['show', 'create', 'store']);
    Route::resource('subjects', App\Http\Controllers\Admin\SubjectController::class);
    Route::resource('sessions', App\Http\Controllers\Admin\TutoringSessionController::class);
});
