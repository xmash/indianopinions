<?php

use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

Route::redirect('/login', '/admin/login')->name('login.redirect');

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
})->name('home');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('posts', Admin\PostController::class);
    Route::post('gallery/upload', [Admin\GalleryController::class, 'upload'])->name('gallery.upload');
    Route::resource('gallery', Admin\GalleryController::class);
    Route::resource('categories', Admin\CategoryController::class);

    Route::get('/tags', [Admin\TagController::class, 'index'])->name('tags.index');
    Route::post('/tags', [Admin\TagController::class, 'store'])->name('tags.store');
    Route::delete('/tags/{tag}', [Admin\TagController::class, 'destroy'])->name('tags.destroy');
});

require __DIR__.'/auth.php';
