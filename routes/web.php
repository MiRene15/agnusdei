<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\RegistrarController;
use App\Http\Controllers\StudentPortalController;
use App\Http\Controllers\TeacherController;

/*
|--------------------------------------------------------------------------
| Public Pages
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
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
/*

|--------------------------------------------------------------------------
| Student Portal
|--------------------------------------------------------------------------
*/
Route::prefix('student')->name('student.')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('/portal-check', [StudentPortalController::class, 'check'])->name('portal.check');
    Route::get('/admission/create', [StudentPortalController::class, 'createAdmission'])->name('admission.create');
    Route::post('/admission/store', [StudentPortalController::class, 'storeAdmission'])->name('admission.store');
    Route::get('/requirements', [StudentPortalController::class, 'requirements'])->name('requirements');
    Route::post('/requirements/upload', [StudentPortalController::class, 'uploadRequirement'])->name('requirements.upload');
    Route::get('/dashboard', [StudentPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/subjects', [StudentPortalController::class, 'subjects'])->name('subjects');
    Route::get('/grades', [StudentPortalController::class, 'grades'])->name('grades');
    Route::get('/schedule', [StudentPortalController::class, 'scheduleView'])->name('schedule');
    Route::get('/assessment', [StudentPortalController::class, 'assessment'])->name('assessment');
});

/*
|--------------------------------------------------------------------------
| Registrar Portal
|--------------------------------------------------------------------------
*/
Route::prefix('registrar')->name('registrar.')->middleware(['auth', 'role:registrar'])->group(function () {
    Route::get('/dashboard', [RegistrarController::class, 'dashboard'])->name('dashboard');
    Route::get('/enrollments', [RegistrarController::class, 'enrollments'])->name('enrollments');
    Route::get('/enrollments/{id}', [RegistrarController::class, 'showEnrollment'])->name('enrollments.show');
    Route::post('/enrollments/{id}/verify', [RegistrarController::class, 'verifyEnrollment'])->name('enrollments.verify');
    Route::post('/enrollments/{id}/approve', [RegistrarController::class, 'approveEnrollment'])->name('enrollments.approve');
    Route::post('/enrollments/{id}/incomplete', [RegistrarController::class, 'markIncomplete'])->name('enrollments.incomplete');
    Route::post('/enrollments/batch-approve', [RegistrarController::class, 'batchApprove'])->name('enrollments.batchApprove');
    Route::post('/enrollments/batch-incomplete', [RegistrarController::class, 'batchIncomplete'])->name('enrollments.batchIncomplete');
    Route::get('/students', [RegistrarController::class, 'students'])->name('students');
    Route::get('/students/{id}', [RegistrarController::class, 'showStudent'])->name('students.show');
    Route::get('/sectioning', [RegistrarController::class, 'sectioning'])->name('section');
    Route::post('/sectioning/update/{id}', [RegistrarController::class, 'updateSection'])->name('section.update');
Route::post('/sectioning/auto-assign/{id}', [RegistrarController::class, 'autoAssignSection'])->name('section.autoAssign');});

/*
|--------------------------------------------------------------------------
| Admin Portal
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings/update', [AdminController::class, 'updateProfile'])->name('settings.update');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/announcements', [AdminController::class, 'announcements'])->name('announcements');
    Route::post('/announcements/store', [AdminController::class, 'storeAnnouncement'])->name('announcements.store');
    Route::delete('/announcements/{id}', [AdminController::class, 'deleteAnnouncement'])->name('announcements.delete');
    Route::get('/reference-codes', [AdminController::class, 'referenceCodes'])->name('reference-codes');
    Route::post('/reference-codes/store', [AdminController::class, 'storeReferenceCode'])->name('reference-codes.store');
    Route::post('/reference-codes/{id}/deactivate', [AdminController::class, 'deactivateReferenceCode'])->name('reference-codes.deactivate');
});

/*
|--------------------------------------------------------------------------
| Teacher Portal
|--------------------------------------------------------------------------
*/
Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
    Route::get('/classes', [TeacherController::class, 'classes'])->name('classes');
    Route::get('/schedule', [TeacherController::class, 'schedule'])->name('schedule');
    Route::get('/grades', [TeacherController::class, 'grades'])->name('grades');
    Route::post('/grades/save', [TeacherController::class, 'saveGrades'])->name('grades.save');
    Route::get('/reports', [TeacherController::class, 'reports'])->name('reports');
});

/*
|--------------------------------------------------------------------------
| Parent Portal
|--------------------------------------------------------------------------
*/
Route::prefix('parent')->name('parent.')->middleware(['auth', 'role:parent'])->group(function () {
    Route::get('/dashboard', [ParentController::class, 'dashboard'])->name('dashboard');
    Route::get('/children', [ParentController::class, 'children'])->name('children');
    Route::get('/grades', [ParentController::class, 'grades'])->name('grades');
    Route::get('/billing', [ParentController::class, 'billing'])->name('billing');
});

/*
|--------------------------------------------------------------------------
| Cashier Portal
|--------------------------------------------------------------------------
*/
Route::prefix('cashier')->name('cashier.')->middleware(['auth', 'role:cashier'])->group(function () {
    Route::get('/dashboard', [CashierController::class, 'dashboard'])->name('dashboard');
    Route::get('/billing', [CashierController::class, 'billing'])->name('billing');
    Route::get('/payments', [CashierController::class, 'payments'])->name('payments');
    Route::get('/payments/create/{tuitionFeeId}', [CashierController::class, 'createPayment'])->name('payments.create');
    Route::post('/payments/store/{tuitionFeeId}', [CashierController::class, 'storePayment'])->name('payments.store');
    Route::get('/reports', [CashierController::class, 'reports'])->name('reports');
});