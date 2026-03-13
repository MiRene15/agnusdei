<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentPortalController;
use App\Http\Controllers\RegistrarController;

/*
|--------------------------------------------------------------------------
| Front Website (Public Pages)
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('FrontWebsite.home'))->name('home');

Route::get('/philosophy', fn() => view('FrontWebsite.philosophy'))->name('philosophy');

Route::get('/background', fn() => view('FrontWebsite.background'))->name('background');

Route::get('/contact', fn() => view('FrontWebsite.contact'))->name('contact');

Route::get('/program-offerings', fn() => view('FrontWebsite.program-offerings'))->name('program-offerings');

Route::get('/requirements', fn() => view('FrontWebsite.requirements'))->name('requirements');

Route::get('/discounts', fn() => view('FrontWebsite.discounts'))->name('discounts');


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class,'login'])->name('login');
Route::post('/login', [AuthController::class,'loginPost'])->name('login.post');

Route::get('/register', [AuthController::class,'register'])->name('register');
Route::post('/register', [AuthController::class,'registerPost'])->name('register.post');

Route::post('/logout', [AuthController::class,'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Student Portal
|--------------------------------------------------------------------------
*/

Route::prefix('student')->name('student.')->middleware('auth')->group(function () {

    /*
    | Admission Flow
    */

    Route::get('/portal-check', [StudentPortalController::class,'check'])->name('portal.check');

    Route::get('/admission/create', [StudentPortalController::class,'createAdmission'])->name('admission.create');
    Route::post('/admission/store', [StudentPortalController::class,'storeAdmission'])->name('admission.store');

    /*
    | Requirements
    */

    Route::get('/requirements', [StudentPortalController::class,'requirements'])->name('requirements');

    Route::post('/requirements/upload', [StudentPortalController::class,'uploadRequirement'])->name('requirements.upload');

    /*
    | Student Dashboard
    */

    Route::get('/dashboard', [StudentPortalController::class,'dashboard'])->name('dashboard');

    /*
    | Enrollment Page
    */

    Route::get('/enroll', fn() => view('StudentDashboard.enroll'))->name('enroll');

    Route::get('/enrollments', [StudentPortalController::class,'enrollment'])->name('enrollments');

    /*
    | Subjects
    */

    Route::get('/subjects', [StudentPortalController::class,'subjects'])->name('subjects');

    /*
    | Grades
    */

    Route::get('/grades', [StudentPortalController::class,'grades'])->name('grades');

    /*
    | Schedule
    */

    Route::get('/schedule', [StudentPortalController::class,'scheduleView'])->name('schedule');

    /*
    | Assessment
    */

    Route::get('/assessment', [StudentPortalController::class,'assessment'])->name('assessment');

});


/*
|--------------------------------------------------------------------------
| Registrar Panel
|--------------------------------------------------------------------------
*/

Route::prefix('registrar')->name('registrar.')->middleware('auth')->group(function () {

    Route::get('/dashboard', [RegistrarController::class,'dashboard'])->name('dashboard');

    /*
    | Enrollment Applications
    */

    Route::get('/enrollments', [RegistrarController::class,'enrollments'])->name('enrollments');

    Route::get('/enrollments/{id}', [RegistrarController::class,'showEnrollment'])->name('enrollments.show');

    Route::post('/enrollments/{id}/approve', [RegistrarController::class,'approveEnrollment'])->name('enrollments.approve');

    Route::post('/enrollments/{id}/incomplete', [RegistrarController::class,'markIncomplete'])->name('enrollments.incomplete');

    /*
    | Student Records
    */

    Route::get('/students', [RegistrarController::class,'students'])->name('students');

    /*
    | Sectioning
    */

    Route::get('/sectioning', [RegistrarController::class,'sectioning'])->name('section');

    Route::post('/sectioning/update/{id}', [RegistrarController::class,'updateSection'])->name('section.update');

});


/*
|--------------------------------------------------------------------------
| Admin Dashboard
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    Route::get('/dashboard', fn() => view('AdminDashboard.dashboard'))->name('dashboard');

});


/*
|--------------------------------------------------------------------------
| Teacher Dashboard
|--------------------------------------------------------------------------
*/

Route::prefix('teacher')->name('teacher.')->middleware('auth')->group(function () {

    Route::get('/dashboard', fn() => view('TeacherDashboard.dashboard'))->name('dashboard');

});


/*
|--------------------------------------------------------------------------
| Parent Dashboard
|--------------------------------------------------------------------------
*/

Route::prefix('parent')->name('parent.')->middleware('auth')->group(function () {

    Route::get('/dashboard', fn() => view('ParentDashboard.dashboard'))->name('dashboard');

});


/*
|--------------------------------------------------------------------------
| Cashier Dashboard
|--------------------------------------------------------------------------
*/

Route::prefix('cashier')->name('cashier.')->middleware('auth')->group(function () {

    Route::get('/dashboard', fn() => view('CashierDashboard.dashboard'))->name('dashboard');

});