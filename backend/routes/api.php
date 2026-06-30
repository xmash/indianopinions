<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BriefController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LayoutController;
use App\Http\Controllers\Api\NewsletterController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'store']);

Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{post:slug}', [ArticleController::class, 'show']);
Route::get('/layout/homepage', [LayoutController::class, 'homepage']);
Route::get('/layout/hubs/{hubSlug}', [LayoutController::class, 'hub']);
Route::get('/brief/dates', [BriefController::class, 'dates']);
Route::get('/brief/latest', [BriefController::class, 'latest']);
Route::get('/brief/{date}', [BriefController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category:slug}', [CategoryController::class, 'show']);
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe']);
