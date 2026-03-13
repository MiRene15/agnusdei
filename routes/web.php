<?php

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
