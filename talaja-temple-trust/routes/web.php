<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\LiveDarshanController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AccountSettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TempleInfoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Public site
|--------------------------------------------------------------------------
*/
Route::get('/', HomeController::class)->name('home');

Route::get('/change-language/{locale}', function (string $locale, Request $request) {
    if (in_array($locale, ['en', 'gu'])) {
        session(['locale' => $locale]);
    }

    return redirect()->back();
})->name('change.language');

// OTP login (devotee)
Route::get('/otp/login', [OtpController::class, 'show'])->name('otp.login');
Route::post('/otp/send', [OtpController::class, 'send'])->name('otp.send')->middleware('throttle:5,1');
Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify')->middleware('throttle:5,1');

// About
Route::get('/about-us', [PageController::class, 'about'])->name('about');
Route::get('/history', [PageController::class, 'history'])->name('history');
Route::get('/trustees', [PageController::class, 'trustees'])->name('trustees');

// Temple info
Route::get('/temple-info', [TempleInfoController::class, 'index'])->name('temple.info');

// Gallery
Route::get('/photo-gallery', [GalleryController::class, 'photos'])->name('photo.gallery');
Route::get('/video-gallery', [GalleryController::class, 'videos'])->name('video.gallery');
Route::get('/gallery', fn () => inertia('GalleryIndex'))->name('gallery');

// News
Route::get('/news-and-updates', [NewsController::class, 'index'])->name('news.index');
Route::get('/view-news/{slug}', [NewsController::class, 'show'])->name('news.show');

// Facilities
Route::get('/community-welfare', [PageController::class, 'facilities'])->name('facilities');

// Contact / Feedback
Route::get('/contact-us', [PageController::class, 'contact'])->name('contact');
Route::post('/contact-us', [ContactController::class, 'store'])->name('contact.store');

// Info
Route::get('/faqs', [InfoController::class, 'faqs'])->name('faqs');
Route::get('/downloads', [InfoController::class, 'downloads'])->name('downloads');

// Generic CMS page
Route::get('/page/{slug}', [PageController::class, 'cms'])->name('cms.page');

// Live Darshan
Route::get('/live-darshan', LiveDarshanController::class)->name('live.darshan');

// Search
Route::get('/search', SearchController::class)->name('search');

// SEO
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

/*
|--------------------------------------------------------------------------
| Authenticated area
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Account settings (incl. Guide Mode toggle)
    Route::get('/account/settings', [AccountSettingController::class, 'edit'])->name('account.settings');
    Route::put('/account/settings', [AccountSettingController::class, 'update'])->name('account.settings.update');
});

require __DIR__.'/auth.php';
require __DIR__.'/devotee.php';
