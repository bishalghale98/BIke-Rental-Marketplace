<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterCompanyController;
use App\Http\Controllers\Auth\RegisterCustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('public.home');
})->name('home');

Route::get('/bikes', function () {
    return view('public.bikes.index');
})->name('bikes.index');

Route::get('/bikes/{id}', function ($id) {
    return view('public.bikes.show');
})->name('bikes.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::get('/register/customer', [RegisterCustomerController::class, 'create'])->name('register.customer');
    Route::post('/register/customer', [RegisterCustomerController::class, 'store']);

    Route::get('/register/company', [RegisterCompanyController::class, 'create'])->name('register.company');
    Route::post('/register/company', [RegisterCompanyController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    Route::prefix('customer')->name('customer.')->middleware('role:Customer')->group(function () {
        Route::get('/dashboard', function () {
            return view('customer.dashboard');
        })->name('dashboard');

        Route::get('/bookings', function () {
            return view('customer.bookings');
        })->name('bookings');

        Route::get('/reviews', function () {
            return view('customer.reviews');
        })->name('reviews');

        Route::get('/wishlist', function () {
            return view('customer.wishlist');
        })->name('wishlist');

        Route::get('/invoices', function () {
            return view('customer.invoices');
        })->name('invoices');

        Route::get('/profile', function () {
            return view('customer.profile');
        })->name('profile');

        Route::get('/verification', function () {
            return view('customer.verification');
        })->name('verification');
    });

    Route::prefix('company')->name('company.')->middleware('role:Company')->group(function () {
        Route::get('/dashboard', function () {
            return view('company.dashboard');
        })->name('dashboard');

        Route::get('/bikes', function () {
            return view('company.bikes.index');
        })->name('bikes.index');

        Route::get('/bookings', function () {
            return view('company.bookings');
        })->name('bookings');

        Route::get('/calendar', function () {
            return view('company.calendar');
        })->name('calendar');

        Route::get('/reviews', function () {
            return view('company.reviews');
        })->name('reviews');

        Route::get('/analytics', function () {
            return view('company.analytics');
        })->name('analytics');

        Route::get('/reports', function () {
            return view('company.reports');
        })->name('reports');

        Route::get('/profile', function () {
            return view('company.profile');
        })->name('profile');
    });
});
