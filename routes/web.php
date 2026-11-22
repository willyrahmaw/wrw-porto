<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

// Main Portfolio Landing Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Projects Archive & Case Study Detail (Route Model Binding via slug)
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');

// Contact Form Submission with Strict Rate Limiting (max 3 requests per minute per IP)
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:3,1')
    ->name('contact.store');

// Public Customer Testimonial Form & Submission (with Rate Limiting)
Route::get('/testimoni', [\App\Http\Controllers\PublicTestimonialController::class, 'create'])->name('testimonials.create');
Route::post('/testimoni', [\App\Http\Controllers\PublicTestimonialController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('testimonials.store');

// SEO Sitemap XML
Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('sitemap');

// Login redirect alias
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// =========================================================================
// STUDIO COMMAND CENTER (ADMIN PANEL)
// =========================================================================
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest / Login & Emergency Recovery Routes
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.submit');
    Route::post('/emergency-unban', [AuthController::class, 'emergencyUnban'])->middleware('throttle:5,1')->name('emergency-unban');

    // Authenticated Admin Routes
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Profile & Settings
        Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
        Route::patch('/profile/toggle-availability', [DashboardController::class, 'toggleAvailability'])->name('profile.toggle-availability');

        // Projects CRUD (Dedicated Pages with Numeric ID Binding)
        Route::get('/projects/create', [DashboardController::class, 'createProject'])->name('projects.create');
        Route::post('/projects', [DashboardController::class, 'saveProject'])->name('projects.store');
        Route::get('/projects/{project:id}', fn (\App\Models\Project $project) => redirect()->route('admin.projects.edit', $project))->whereNumber('project');
        Route::get('/projects/{project:id}/edit', [DashboardController::class, 'editProject'])->whereNumber('project')->name('projects.edit');
        Route::put('/projects/{project:id}', [DashboardController::class, 'saveProject'])->whereNumber('project')->name('projects.update');
        Route::delete('/projects/{project:id}', [DashboardController::class, 'deleteProject'])->whereNumber('project')->name('projects.destroy');

        // Services CRUD
        Route::post('/services', [DashboardController::class, 'saveService'])->name('services.store');
        Route::put('/services/{service}', [DashboardController::class, 'saveService'])->whereNumber('service')->name('services.update');
        Route::delete('/services/{service}', [DashboardController::class, 'deleteService'])->whereNumber('service')->name('services.destroy');

        // Clients & Testimonials CRUD
        Route::post('/clients', [DashboardController::class, 'storeClient'])->name('clients.store');
        Route::delete('/clients/{client}', [DashboardController::class, 'deleteClient'])->whereNumber('client')->name('clients.destroy');
        Route::post('/testimonials', [DashboardController::class, 'saveTestimonial'])->name('testimonials.store');
        Route::put('/testimonials/{testimonial}', [DashboardController::class, 'saveTestimonial'])->whereNumber('testimonial')->name('testimonials.update');
        Route::patch('/testimonials/{testimonial}/toggle-approval', [DashboardController::class, 'toggleTestimonialApproval'])->whereNumber('testimonial')->name('testimonials.toggle-approval');
        Route::delete('/testimonials/{testimonial}', [DashboardController::class, 'deleteTestimonial'])->whereNumber('testimonial')->name('testimonials.destroy');

        // Inbox Messages
        Route::patch('/messages/{message}/toggle', [DashboardController::class, 'toggleMessageRead'])->whereNumber('message')->name('messages.toggle');
        Route::post('/messages/{message}/ban-ip', [DashboardController::class, 'banAndDestroyMessage'])->whereNumber('message')->name('messages.ban-ip');
        Route::delete('/messages/purge', [DashboardController::class, 'purgeAllMessages'])->name('messages.purge');
        Route::delete('/messages/{message}', [DashboardController::class, 'deleteMessage'])->whereNumber('message')->name('messages.destroy');

        // Admin Account Security
        Route::put('/security', [DashboardController::class, 'updatePassword'])->name('security.update');

        // SEO & Meta Configuration
        Route::put('/seo', [DashboardController::class, 'updateSeo'])->name('seo.update');

        // IP Blacklist & Firewall Management (Manual Ban & Unban/Delete)
        Route::post('/banned-ips', [DashboardController::class, 'manualBanIp'])->name('banned-ips.store');
        Route::delete('/banned-ips/{ipBan}', [DashboardController::class, 'unbanIp'])->whereNumber('ipBan')->name('banned-ips.destroy');
    });
});
