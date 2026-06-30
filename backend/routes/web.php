<?php

use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

Route::redirect('/login', '/admin/login')->name('login.redirect');

Route::get('/', function () {
    return admin_redirect('admin.dashboard');
})->name('home');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])
        ->middleware('permission:view_dashboard')
        ->name('dashboard');

    Route::middleware('permission:view_articles')->group(function () {
        Route::resource('posts', Admin\PostController::class);
        Route::post('posts/{post}/submit', [Admin\PostController::class, 'submit'])
            ->middleware('permission:submit_articles')
            ->name('posts.submit');
        Route::post('posts/{post}/unpublish', [Admin\PostController::class, 'unpublish'])
            ->middleware('permission:unpublish_articles')
            ->name('posts.unpublish');
        Route::post('posts/{post}/transition', [Admin\PostController::class, 'transition'])
            ->middleware('role:editor')
            ->name('posts.transition');
    });

    Route::middleware('permission:view_review_queue')->group(function () {
        Route::get('review', [Admin\ReviewController::class, 'index'])->name('review.index');
        Route::post('review/{post}/start', [Admin\ReviewController::class, 'startReview'])
            ->middleware('permission:review_articles')
            ->name('review.start');
        Route::post('review/{post}/changes', [Admin\ReviewController::class, 'requestChanges'])
            ->middleware('permission:review_articles')
            ->name('review.changes');
        Route::post('review/{post}/reject', [Admin\ReviewController::class, 'reject'])
            ->middleware('permission:review_articles')
            ->name('review.reject');
        Route::post('review/{post}/publish', [Admin\ReviewController::class, 'publish'])
            ->middleware('permission:publish_articles')
            ->name('review.publish');
    });

    Route::middleware('permission:manage_categories')->group(function () {
        Route::resource('categories', Admin\CategoryController::class);
    });

    Route::middleware('permission:manage_tags')->group(function () {
        Route::get('/tags', [Admin\TagController::class, 'index'])->name('tags.index');
        Route::post('/tags', [Admin\TagController::class, 'store'])->name('tags.store');
        Route::delete('/tags/{tag}', [Admin\TagController::class, 'destroy'])->name('tags.destroy');
    });

    Route::middleware('permission:manage_gallery')->group(function () {
        Route::post('gallery/upload', [Admin\GalleryController::class, 'upload'])->name('gallery.upload');
        Route::resource('gallery', Admin\GalleryController::class);
    });

    Route::middleware('permission:manage_staff')->group(function () {
        Route::resource('users', Admin\UserController::class)->except(['show']);
    });

    Route::middleware('permission:manage_layout')->group(function () {
        Route::get('layout/homepage', [Admin\LayoutController::class, 'homepage'])->name('layout.homepage');
        Route::put('layout/homepage', [Admin\LayoutController::class, 'updateHomepage'])->name('layout.homepage.update');
        Route::get('layout/hubs/{hubSlug}', [Admin\LayoutController::class, 'hub'])->name('layout.hub');
        Route::put('layout/hubs/{hubSlug}', [Admin\LayoutController::class, 'updateHub'])->name('layout.hub.update');
    });

    Route::get('permissions', [Admin\PermissionsController::class, 'index'])->name('permissions.index');
});

require __DIR__.'/auth.php';
