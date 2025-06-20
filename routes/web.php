<?php

use App\Http\Controllers\ClassesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('students', StudentController::class);
    Route::post('/students/{userId}/toggle-active', [StudentController::class, 'toggleActive'])->name('students.toggle-active');

    Route::resource('teachers', TeacherController::class);
    Route::post('/teachers/{teacherId}/toggle-active', [TeacherController::class, 'toggleActive'])->name('teachers.toggle-active');
    
    Route::resource('parents', ParentsController::class);
    Route::post('/parents/{parentId}/toggle-active', [ParentsController::class, 'toggleActive'])->name('parents.toggle-active');

    Route::resource('classes', ClassesController::class);
    Route::post('/classes/{class}/assign-student', [ClassesController::class, 'assignStudent'])->name('classes.assign-student');
    Route::post('/classes/{class}/bulk-assign', [ClassesController::class, 'bulkAssign'])->name('classes.bulk-assign');
    Route::delete('classes/{class}/remove-student', [ClassesController::class, 'removeStudent'])->name('classes.remove-student');

    Route::resource('subjects', SubjectController::class);
    
    Route::resource('schedules', ScheduleController::class);
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
