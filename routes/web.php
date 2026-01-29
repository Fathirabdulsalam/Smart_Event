<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Admin\EventsController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\AuthorsController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\User\UserEventController;
use App\Http\Controllers\Admin\DiscountsController;
use App\Http\Controllers\User\UserTicketController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\MasterPageController;
use App\Http\Controllers\Admin\MasterTypeController;
use App\Http\Controllers\Admin\MasterZoneController;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\Admin\MasterSlideController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\Admin\ConfigurationController;
use App\Http\Controllers\Admin\RegistrationsController;
use App\Http\Controllers\Admin\AdvertisementsController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\MasterLocationController;
use App\Http\Controllers\User\UserTransactionController;
use App\Http\Controllers\Admin\MasterEventKindController;
use App\Http\Controllers\Admin\MasterSocialMediaController;
use App\Http\Controllers\Admin\MasterTicketCategoryController;
use App\Http\Controllers\MidtransCallbackController;

Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handle']);


// Landing Page Routes
Route::get('/landingPage', [LandingPageController::class, 'index'])->name('landingPage');
Route::get('/events', [LandingPageController::class, 'allEvents'])->name('allEvents');
Route::get('/event/{id}', [LandingPageController::class, 'show'])->name('event.detail');
Route::get('/articles', [LandingPageController::class, 'allArticles'])->name('articles.all');
Route::get('/articles/{slug}', [LandingPageController::class, 'showArticle'])->name('articles.show');
Route::get('/faq', [LandingPageController::class, 'faq'])->name('faq');
Route::get('/terms', [LandingPageController::class, 'terms'])->name('terms');
Route::view('/about', 'users.about.index')->name('about');
Route::get('/creator/{id}', [LandingPageController::class, 'showCreator'])->name('creator.show');
Route::get('/page/{slug}', [LandingPageController::class, 'showPage'])->name('page.detail');

// Authentication Routes (Guest)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('showLogin');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('showRegister');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    
    Route::get('/admin/login', [AuthController::class, 'showLoginAdmin'])->name('showLoginAdmin');
    Route::post('/admin/login', [AuthController::class, 'loginAdmin'])->name('loginAdmin');

    // Forgot Password
    Route::get('forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');

    // Reset Password
    Route::get('reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [AuthController::class, 'reset'])->name('password.update');
});

// PayLabs Routes (Public Access)
// Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::post('/pay', [PaymentController::class, 'pay'])->name('payment.checkout');

// Authenticated User Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

    // Profile
    Route::get('/user/settings', [UserProfileController::class, 'edit'])->name('user.profile.edit');
    Route::put('/user/settings', [UserProfileController::class, 'update'])->name('user.profile.update');
    
    // Events Management
    Route::get('/user/events', [UserEventController::class, 'index'])->name('user.events.index');
    Route::post('/user/event/store', [UserEventController::class, 'store'])->name('user.event.store');
    Route::put('/user/event/{id}', [UserEventController::class, 'update'])->name('user.event.update');
    Route::delete('/user/event/{id}', [UserEventController::class, 'destroy'])->name('user.event.destroy');
    
    // Articles Management
    Route::get('/user/articles', [\App\Http\Controllers\User\ArticleController::class, 'index'])->name('user.articles.index');
    Route::post('/user/article/store', [\App\Http\Controllers\User\ArticleController::class, 'store'])->name('user.article.store');
    Route::put('/user/article/{id}', [\App\Http\Controllers\User\ArticleController::class, 'update'])->name('user.article.update');
    Route::delete('/user/article/{id}', [\App\Http\Controllers\User\ArticleController::class, 'destroy'])->name('user.article.destroy');

    // Tickets & Transactions
    Route::get('/user/tickets', [UserTicketController::class, 'index'])->name('user.tickets.index');
    Route::get('/user/transactions', [UserTransactionController::class, 'index'])->name('user.transactions.index');

    // PAYLABS PAYMENT ROUTES
    // Route::post('/payment/checkout/{event}', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/payment/return', [PaymentController::class, 'return'])->name('payment.return');
    Route::get('/payment/history', [UserTransactionController::class, 'index'])->name('payment.history');
    Route::get('/ticket/{transaction}', [PaymentController::class, 'showTicket'])->name('ticket.show');

    // Logout
    Route::post('/logout', [AuthController::class, 'logoutUser'])->name('logoutUser');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin/dashboard')->group(function () {
    Route::post('/logout/admin', [AuthController::class, 'logoutAdmin'])->name('logoutAdmin');
    Route::get('/', [DashboardAdminController::class, 'index'])->name('dashboardAdmin');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Events
    Route::get('/events', [EventsController::class, 'index'])->name('events.index');
    Route::post('/events', [EventsController::class, 'store'])->name('events.store');
    Route::put('/events/{id}', [EventsController::class, 'update'])->name('events.update');
    Route::delete('/events/{id}', [EventsController::class, 'destroy'])->name('events.destroy');

    // Registrations
    Route::get('/registrations', [RegistrationsController::class, 'index'])->name('registrations.index');
    Route::post('/registrations', [RegistrationsController::class, 'store'])->name('registrations.store');
    Route::post('/registrations/{registration}', [RegistrationsController::class, 'update'])->name('registrations.update');
    Route::delete('/registrations/{registration}', [RegistrationsController::class, 'destroy'])->name('registrations.destroy');

    // Authors
    Route::get('/authors', [AuthorsController::class, 'index'])->name('authors.index');
    Route::post('/authors', [AuthorsController::class, 'store'])->name('authors.store');
    Route::put('/authors/{id}/update', [AuthorsController::class, 'update'])->name('authors.update');
    Route::delete('/authors/{id}', [AuthorsController::class, 'destroy'])->name('authors.destroy');

    // Payments
    Route::get('/payments', [PaymentsController::class, 'index'])->name('payments.index');

    // Advertisements
    Route::get('/advertisement', [AdvertisementsController::class, 'index'])->name('advertisements.index');
    Route::post('/advertisement', [AdvertisementsController::class, 'store'])->name('advertisements.store');
    Route::put('/advertisement/{id}', [AdvertisementsController::class, 'update'])->name('advertisements.update');
    Route::delete('/advertisement/{id}', [AdvertisementsController::class, 'destroy'])->name('advertisements.destroy');

    // Discounts
    Route::get('/discounts', [DiscountsController::class, 'index'])->name('discounts.index');
    Route::post('/discounts', [DiscountsController::class, 'store'])->name('discounts.store');
    Route::put('/discounts/{id}', [DiscountsController::class, 'update'])->name('discounts.update');
    Route::delete('/discounts/{id}', [DiscountsController::class, 'destroy'])->name('discounts.destroy');

    // Categories
    Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoriesController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoriesController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}/update', [CategoriesController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoriesController::class, 'destroy'])->name('categories.destroy');

    // Configuration
    Route::get('/configuration', [ConfigurationController::class, 'index'])->name('configuration.index');
    Route::put('/configuration', [ConfigurationController::class, 'update'])->name('configuration.update');

    // Master Data
    Route::resource('slides', MasterSlideController::class);
    Route::resource('zones', MasterZoneController::class);
    Route::resource('locations', MasterLocationController::class);

    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('types', MasterTypeController::class)->except(['create', 'edit', 'show']);
        Route::resource('event-kinds', MasterEventKindController::class)->except(['create', 'edit', 'show']);
        Route::resource('zones', MasterZoneController::class)->except(['create', 'edit', 'show']);
        Route::resource('locations', MasterLocationController::class)->except(['create', 'edit', 'show']);
        Route::resource('ticket-categories', MasterTicketCategoryController::class)->except(['create', 'edit', 'show']);
        Route::resource('slides', MasterSlideController::class)->except(['create', 'edit', 'show']);
    });

    // FAQs
    Route::get('/faqs', [\App\Http\Controllers\Admin\FaqController::class, 'index'])->name('faqs.index');
    Route::post('/faqs', [\App\Http\Controllers\Admin\FaqController::class, 'store'])->name('faqs.store');
    Route::put('/faqs/{faq}', [\App\Http\Controllers\Admin\FaqController::class, 'update'])->name('faqs.update');
    Route::delete('/faqs/{faq}', [\App\Http\Controllers\Admin\FaqController::class, 'destroy'])->name('faqs.destroy');

    // Social Media
    Route::get('/social-medias', [MasterSocialMediaController::class, 'index'])->name('social-medias.index');
    Route::post('/social-medias', [MasterSocialMediaController::class, 'store'])->name('social-medias.store');
    Route::put('/social-medias/{id}', [MasterSocialMediaController::class, 'update'])->name('social-medias.update');
    Route::delete('/social-medias/{id}', [MasterSocialMediaController::class, 'destroy'])->name('social-medias.destroy');

    // Pages
    Route::get('/master/pages', [MasterPageController::class, 'index'])->name('master.pages.index');
    Route::post('/master/pages', [MasterPageController::class, 'store'])->name('master.pages.store');
    Route::put('/master/pages/{id}', [MasterPageController::class, 'update'])->name('master.pages.update');
    Route::delete('/master/pages/{id}', [MasterPageController::class, 'destroy'])->name('master.pages.destroy');
});