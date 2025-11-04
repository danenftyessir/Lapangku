<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\Auth\ValidationController;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| Web Routes - KKN-GO Platform
|--------------------------------------------------------------------------
|
| file ini berisi semua routing untuk aplikasi KKN-GO
| 
*/

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// home page
Route::get('/', [HomeController::class, 'index'])->name('home');

// about us page
Route::get('/about', [AboutController::class, 'index'])->name('about'); 

// contact page
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

// public student profile/portfolio (dapat diakses tanpa login)
Route::get('/profile/{username}', [StudentProfileController::class, 'publicView'])->name('profile.public');

// redirect portfolio ke profile untuk backward compatibility
Route::get('/portfolio/{username}', function($username) {
    return redirect()->route('profile.public', $username);
});

/*
|--------------------------------------------------------------------------
| Development Routes (hanya untuk development)
|--------------------------------------------------------------------------
*/

if (config('app.env') === 'local' || config('app.env') === 'development') {
    Route::get('/dev/login', function () {
        return view('dev.login');
    })->name('dev.login');
}

/*
|--------------------------------------------------------------------------
| Guest Routes (hanya bisa diakses jika belum login)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    
    // authentication pages
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::get('/register/student', [RegisterController::class, 'showStudentForm'])->name('register.student');
    Route::post('/register/student', [RegisterController::class, 'registerStudent'])->name('register.student.submit');
    Route::get('/register/institution', [RegisterController::class, 'showInstitutionForm'])->name('register.institution');
    Route::post('/register/institution', [RegisterController::class, 'registerInstitution'])->name('register.institution.submit');
    Route::post('/validation/student/step', [ValidationController::class, 'validateStudentStep'])->name('validation.student.step');
    
    // forgot password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    
    // reset password
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
    
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (perlu login)
|--------------------------------------------------------------------------
*/

// logout (harus authenticated)
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// email verification
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])->name('verification.resend');
});