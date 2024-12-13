<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\AuthController;

Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');

use Illuminate\Support\Facades\Auth;

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/'); // Redirect ke halaman utama setelah logout
})->name('logout');


Route::get('/password/reset/{token}', function ($token) {
    return view('auth.password-reset', ['token' => $token]);
})->name('password.reset');

Route::get('/send-test-email', function () {
    $testEmail = 'rezkydwi55@gmail.com';
    Mail::raw('This is a test email from Laravel.', function ($message) use ($testEmail) {
        $message->to($testEmail)
                ->subject('Test Email');
    });

    return 'Test email sent!';
});

Route::get('/send-test-email', function () {
    $testEmail = 'rezkydwi55@gmail.com';
    Mail::raw('This is a test email from Laravel.', function ($message) use ($testEmail) {
        $message->to($testEmail)
                ->subject('Test Email');
    });

    return 'Test email sent!';
});