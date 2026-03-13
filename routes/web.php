<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistrarController;
use App\Http\Controllers\StudentPortalController;
use Illuminate\Support\Facades\Route;

/*Home page */
Route::get('/', function () {
    return view('home');
})->name('home');

/*Dropdown About Us */
Route::get('/philosophy', function () {
    return view('philosophy');
});

Route::get('/background', function () {
    return view('background');
});

Route::get('/goals', function () {
    return view('goals');
});

Route::get('/vision-mission', function () {
    return view('vision-mission');
});

Route::get('/contact', function () {
    return view('contact');
});

/*Dropdown Admissions */
Route::get('/program-offerings', function () {
    return view('program-offerings');
});

Route::get('/requirements', function () {
    return view('requirements');
});

Route::get('/discounts', function () {
    return view('discounts');
});


/* Registration */
Route::get('/register', function () {
    return view('register');
});




Route::prefix('student')->name('student.')->middleware('auth')->group(function () {
    Route::get('/portal-check', [StudentPortalController::class, 'check'])->name('portal.check');

    Route::get('/admission/create', [StudentPortalController::class, 'createAdmission'])->name('admission.create');
    Route::post('/admission/store', [StudentPortalController::class, 'storeAdmission'])->name('admission.store');

    Route::get('/requirements', [StudentPortalController::class, 'requirements'])->name('requirements');
    Route::post('/requirements/upload', [StudentPortalController::class, 'uploadRequirement'])->name('requirements.upload');

    Route::get('/dashboard', [StudentPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/enrollment', [StudentPortalController::class, 'enrollment'])->name('enrollment');
    Route::get('/enrollments', [StudentPortalController::class, 'enrollment'])->name('enrollments');
    Route::get('/subjects', [StudentPortalController::class, 'subjects'])->name('subjects');
    Route::get('/grades', [StudentPortalController::class, 'grades'])->name('grades');
    Route::get('/schedule', [StudentPortalController::class, 'scheduleView'])->name('schedule');
    Route::get('/assessment', [StudentPortalController::class, 'assessment'])->name('assessment');

    Route::get('/enroll', fn() => view('StudentDashboard.enroll'))->name('enroll');
});

Route::prefix('registrar')->name('registrar.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [RegistrarController::class, 'dashboard'])->name('dashboard');
    Route::get('/enrollments', [RegistrarController::class, 'enrollments'])->name('enrollments');
    Route::get('/enrollments/{id}', [RegistrarController::class, 'showEnrollment'])->name('enrollments.show');
    Route::post('/enrollments/{id}/approve', [RegistrarController::class, 'approveEnrollment'])->name('enrollments.approve');
    Route::post('/enrollments/{id}/incomplete', [RegistrarController::class, 'markIncomplete'])->name('enrollments.incomplete');
    Route::get('/students', [RegistrarController::class, 'students'])->name('students');
    Route::get('/sectioning', [RegistrarController::class, 'sectioning'])->name('section');
    Route::post('/sectioning/update/{id}', [RegistrarController::class, 'updateSection'])->name('section.update');
});