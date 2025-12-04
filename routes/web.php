<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\TutoriaController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\OnlineClassController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');

    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');

    Route::post('/grades/{grade}/send-email', [GradeController::class, 'sendEmail'])
        ->name('grades.sendEmail');

    Route::get('/agenda', [OnlineClassController::class, 'index'])
    ->name('agenda.index');

    Route::post('/online-classes', [OnlineClassController::class, 'store'])
        ->name('online-classes.store');

    Route::post('/online-classes/{onlineClass}/close', [OnlineClassController::class, 'close'])
        ->name('online-classes.close');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');

    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/courses', [MateriaController::class, 'courses'])
        ->name('courses.index');

    Route::get('/materias', [MateriaController::class, 'index'])->name('materias.index');
    Route::get('/materias/create', [MateriaController::class, 'create'])->name('materias.create');
    Route::get('/materias/{id}/edit', [MateriaController::class, 'edit'])->name('materias.edit');

    Route::post('/materias', [MateriaController::class, 'store'])->name('materias.store');
    Route::put('/materias/{id}', [MateriaController::class, 'update'])->name('materias.update');
    Route::delete('/materias/{id}', [MateriaController::class, 'destroy'])->name('materias.destroy');

    Route::post('/materias/{materia}/enroll', [MateriaController::class, 'enroll'])
        ->name('materias.enroll');

    Route::get('/tutorias', [TutoriaController::class, 'index'])
    ->name('tutorias.index');

});

require __DIR__.'/auth.php';
