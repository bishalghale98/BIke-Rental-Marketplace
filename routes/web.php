<?php

use App\Http\Controllers\AccountDeletionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterCompanyController;
use App\Http\Controllers\Auth\RegisterCustomerController;
use App\Http\Controllers\Company\BikeController as CompanyBikeController;
use App\Http\Controllers\Company\BookingController as CompanyBookingController;
use App\Http\Controllers\Company\ProfileController as CompanyProfileController;
use App\Http\Controllers\Company\ReportController as CompanyReportController;
use App\Http\Controllers\Company\ReviewController as CompanyReviewController;
use App\Http\Controllers\Company\VerificationController as CompanyVerificationController;
use App\Http\Controllers\Admin\FinancialController;
use App\Http\Controllers\Admin\PayoutController as AdminPayoutController;
use App\Http\Controllers\Company\PayoutController as CompanyPayoutController;
use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Customer\PaymentController as CustomerPaymentController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\Customer\ReviewController as CustomerReviewController;
use App\Http\Controllers\Customer\VerificationController as CustomerVerificationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Public\BikeController as PublicBikeController;
use Illuminate\Support\Facades\Route;

Route::post('/theme/toggle', function () {
    $theme = request('theme', 'light');
    session(['theme' => $theme === 'dark' ? 'dark' : 'light']);
    return response()->json(['theme' => session('theme')]);
})->name('theme.toggle')->middleware('web');

Route::get('/', function () {
    $categories = \App\Models\BikeCategory::withCount('bikes')->get();
    $featured = \App\Models\Bike::available()->with(['primaryImage', 'category', 'company'])->latest()->take(8)->get();
    $companies = \App\Models\CompanyProfile::where('verification_status', 'verified')->withCount('bikes')->take(6)->get();
    $totalBikes = \App\Models\Bike::count();
    $totalCompanies = \App\Models\CompanyProfile::where('verification_status', 'verified')->count();
    $totalBookings = \App\Models\Booking::count();
    return view('public.home', compact('categories', 'featured', 'companies', 'totalBikes', 'totalCompanies', 'totalBookings'));
})->name('home');

Route::get('/bikes', [PublicBikeController::class, 'index'])->name('bikes.index');
Route::get('/bikes/{bike}', [PublicBikeController::class, 'show'])->name('bikes.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:5,1');

    Route::get('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'store'])->name('password.update');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::get('/register/customer', [RegisterCustomerController::class, 'create'])->name('register.customer');
    Route::post('/register/customer', [RegisterCustomerController::class, 'store'])->middleware('throttle:3,1');

    Route::get('/register/company', [RegisterCompanyController::class, 'create'])->name('register.company');
    Route::post('/register/company', [RegisterCompanyController::class, 'store'])->middleware('throttle:3,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    Route::get('/email/verify', [\App\Http\Controllers\Auth\EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [\App\Http\Controllers\Auth\EmailVerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/verification-notification', [\App\Http\Controllers\Auth\EmailVerificationController::class, 'send'])->name('verification.send');

    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');

    Route::post('/account/deactivate', [AccountDeletionController::class, 'deactivate'])->name('account.deactivate');

    Route::prefix('admin')->name('admin.')->middleware('role:Admin')->group(function () {
        Route::get('/financial', [FinancialController::class, 'dashboard'])->name('financial.dashboard');
    });

    Route::prefix('customer')->name('customer.')->middleware('role:Customer')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/bookings', [CustomerBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/create/{bike}', [CustomerBookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings/{bike}', [CustomerBookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/{booking}', [CustomerBookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{booking}/cancel', [CustomerBookingController::class, 'cancel'])->name('bookings.cancel');

        Route::get('/checkout/{booking}', [CustomerPaymentController::class, 'checkout'])->name('payment.checkout');
        Route::post('/pay/{booking}/{gateway}', [CustomerPaymentController::class, 'pay'])->name('payment.pay');
        Route::get('/payment/{gateway}/callback', [CustomerPaymentController::class, 'callback'])->name('payment.callback');
        Route::get('/payment/success/{booking}', [CustomerPaymentController::class, 'success'])->name('payment.success');
        Route::get('/payment/failure/{booking}', [CustomerPaymentController::class, 'failure'])->name('payment.failure');
        Route::post('/pay/remaining/{booking}/{gateway}', [CustomerPaymentController::class, 'payRemaining'])->name('payment.pay-remaining');

        Route::get('/reviews', [CustomerReviewController::class, 'index'])->name('reviews.index');
        Route::get('/reviews/create/{booking}', [CustomerReviewController::class, 'create'])->name('reviews.create');
        Route::post('/reviews/{booking}', [CustomerReviewController::class, 'store'])->name('reviews.store');

        Route::get('/extensions', [\App\Http\Controllers\Customer\ExtensionRequestController::class, 'index'])->name('extensions.index');
        Route::get('/extensions/create/{booking}', [\App\Http\Controllers\Customer\ExtensionRequestController::class, 'create'])->name('extensions.create');
        Route::post('/extensions/{booking}', [\App\Http\Controllers\Customer\ExtensionRequestController::class, 'store'])->name('extensions.store');

        Route::get('/wishlist', function () {
            return view('customer.wishlist');
        })->name('wishlist');

        Route::get('/invoices', function () {
            return view('customer.invoices');
        })->name('invoices');

        Route::get('/profile', [CustomerProfileController::class, 'edit'])->name('profile');
        Route::put('/profile', [CustomerProfileController::class, 'update'])->name('profile.update');

        Route::get('/verification', [CustomerVerificationController::class, 'show'])->name('verification');
        Route::post('/verification', [CustomerVerificationController::class, 'submit'])->name('verification.submit');
    });

    Route::prefix('company')->name('company.')->middleware('role:Company')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Company\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/bikes', [CompanyBikeController::class, 'index'])->name('bikes.index');
        Route::get('/bikes/create', [CompanyBikeController::class, 'create'])->name('bikes.create');
        Route::post('/bikes', [CompanyBikeController::class, 'store'])->name('bikes.store');
        Route::get('/bikes/{bike}/edit', [CompanyBikeController::class, 'edit'])->name('bikes.edit');
        Route::put('/bikes/{bike}', [CompanyBikeController::class, 'update'])->name('bikes.update');
        Route::delete('/bikes/{bike}', [CompanyBikeController::class, 'destroy'])->name('bikes.destroy');
        Route::post('/bikes/{bike}/toggle-availability', [CompanyBikeController::class, 'toggleAvailability'])->name('bikes.toggle-availability');

        Route::get('/bookings', [CompanyBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [CompanyBookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{booking}/status', [CompanyBookingController::class, 'updateStatus'])->name('bookings.update-status');

        Route::get('/reviews', [CompanyReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{review}/reply', [CompanyReviewController::class, 'reply'])->name('reviews.reply');

        Route::get('/calendar', [\App\Http\Controllers\Company\CalendarController::class, 'index'])->name('calendar');

        Route::get('/analytics', function () {
            return view('company.analytics');
        })->name('analytics');

        Route::get('/payouts', [CompanyPayoutController::class, 'index'])->name('payouts.index');
        Route::post('/payouts/request', [CompanyPayoutController::class, 'request'])->name('payouts.request');
        Route::get('/payouts/invoice/{payout}', [CompanyPayoutController::class, 'invoice'])->name('payouts.invoice');
        Route::get('/payouts/history', [CompanyPayoutController::class, 'history'])->name('payouts.history');

        Route::get('/bank-details', [\App\Http\Controllers\Company\BankDetailController::class, 'index'])->name('bank-details.index');
        Route::post('/bank-details', [\App\Http\Controllers\Company\BankDetailController::class, 'store'])->name('bank-details.store');
        Route::put('/bank-details/{bankDetail}', [\App\Http\Controllers\Company\BankDetailController::class, 'update'])->name('bank-details.update');
        Route::delete('/bank-details/{bankDetail}', [\App\Http\Controllers\Company\BankDetailController::class, 'destroy'])->name('bank-details.destroy');

        Route::get('/extensions', [\App\Http\Controllers\Company\ExtensionRequestController::class, 'index'])->name('extensions.index');
        Route::post('/extensions/{extensionRequest}/approve', [\App\Http\Controllers\Company\ExtensionRequestController::class, 'approve'])->name('extensions.approve');
        Route::post('/extensions/{extensionRequest}/deny', [\App\Http\Controllers\Company\ExtensionRequestController::class, 'deny'])->name('extensions.deny');

        Route::get('/reports', [CompanyReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/revenue', [CompanyReportController::class, 'revenue'])->name('reports.revenue');
        Route::get('/reports/revenue/export', [CompanyReportController::class, 'exportRevenue'])->name('reports.revenue.export');
        Route::get('/reports/bookings', [CompanyReportController::class, 'bookings'])->name('reports.bookings');
        Route::get('/reports/bookings/export', [CompanyReportController::class, 'exportBookings'])->name('reports.bookings.export');
        Route::get('/reports/bikes', [CompanyReportController::class, 'bikes'])->name('reports.bikes');
        Route::get('/reports/bikes/export', [CompanyReportController::class, 'exportBikes'])->name('reports.bikes.export');

        Route::get('/profile', [CompanyProfileController::class, 'edit'])->name('profile');
        Route::put('/profile', [CompanyProfileController::class, 'update'])->name('profile.update');

        Route::get('/verification', [CompanyVerificationController::class, 'show'])->name('verification');
        Route::post('/verification', [CompanyVerificationController::class, 'submit'])->name('verification.submit');
    });
});
