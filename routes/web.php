<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentPortalController;
use App\Http\Controllers\RegistrarController;

/*
|--------------------------------------------------------------------------
| Public Pages (FrontWebsite)
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
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'registerUser'])->name('register.post');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'loginUser'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Student Portal Flow
|--------------------------------------------------------------------------
*/
Route::prefix('student')->name('student.')->middleware('auth')->group(function () {
    Route::get('/portal-check', [StudentPortalController::class, 'check'])->name('portal.check');

    Route::get('/admission/create', [StudentPortalController::class, 'createAdmission'])->name('admission.create');
    Route::post('/admission/store', [StudentPortalController::class, 'storeAdmission'])->name('admission.store');

    Route::get('/requirements', [StudentPortalController::class, 'requirements'])->name('requirements');
    Route::post('/requirements/upload', [StudentPortalController::class, 'uploadRequirement'])->name('requirements.upload');

    Route::get('/enrollment', [StudentPortalController::class, 'enrollment'])->name('enrollment');
    Route::get('/dashboard', [StudentPortalController::class, 'dashboard'])->name('dashboard');

    Route::get('/enroll', fn() => view('StudentDashboard.enroll'))->name('enroll');
    Route::get('/enrollments', fn() => view('StudentDashboard.enrollments'))->name('enrollments');
    Route::get('/assessment', fn() => view('StudentDashboard.assessment'))->name('assessment');
    Route::get('/schedule', fn() => view('StudentDashboard.schedule'))->name('schedule');
});

/*
|--------------------------------------------------------------------------
| Registrar Portal Flow
|--------------------------------------------------------------------------
*/

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


/*
|--------------------------------------------------------------------------
| Admin Portal Flow
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('AdminDashboard.dashboard'))->name('dashboard');
    Route::get('/users', fn() => view('AdminDashboard.users'))->name('users');
    Route::get('/settings', fn() => view('AdminDashboard.settings'))->name('settings');
    Route::get('/reports', fn() => view('AdminDashboard.reports'))->name('reports');
});

/*
|--------------------------------------------------------------------------
| Teacher Portal Flow
|--------------------------------------------------------------------------
*/
Route::prefix('teacher')->name('teacher.')->middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('TeacherDashboard.dashboard'))->name('dashboard');
    Route::get('/classes', fn() => view('TeacherDashboard.classes'))->name('classes');
    Route::get('/grades', fn() => view('TeacherDashboard.grades'))->name('grades');
    Route::get('/reports', fn() => view('TeacherDashboard.reports'))->name('reports');
});

/*
|--------------------------------------------------------------------------
| Parent Portal Flow
|--------------------------------------------------------------------------
*/
Route::prefix('parent')->name('parent.')->middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('ParentDashboard.dashboard'))->name('dashboard');
    Route::get('/children', fn() => view('ParentDashboard.children'))->name('children');
    Route::get('/grades', fn() => view('ParentDashboard.grades'))->name('grades');
    Route::get('/billing', fn() => view('ParentDashboard.billing'))->name('billing');
});

/*
|--------------------------------------------------------------------------
| Cashier Portal Flow
|--------------------------------------------------------------------------
*/
Route::prefix('cashier')->name('cashier.')->middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('CashierDashboard.dashboard'))->name('dashboard');
    Route::get('/payments', fn() => view('CashierDashboard.payments'))->name('payments');
    Route::get('/billing', fn() => view('CashierDashboard.billing'))->name('billing');
    Route::get('/reports', fn() => view('CashierDashboard.reports'))->name('reports');
});
