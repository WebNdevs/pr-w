<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\PublicVideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication routes (public)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });
});

// Public routes (no authentication required)
Route::post('/analytics/track', [AnalyticsController::class, 'store']);

// Public video routes
Route::prefix('public')->group(function () {
    Route::get('/videos/popular', [PublicVideoController::class, 'popular']);
    Route::get('/videos/{slug}', [PublicVideoController::class, 'show']);
    Route::get('/videos/{slug}/metadata', [PublicVideoController::class, 'metadata']);
    Route::get('/sitemap.xml', [PublicVideoController::class, 'sitemap']);
    
    // Brand-specific routes
    Route::get('/{brand_username}/{campaign_slug}', [PublicVideoController::class, 'campaignRoundRobin']);
    Route::get('/{brand_username}/{campaign_slug}/{video_slug}', [PublicVideoController::class, 'brandVideo']);
});

// Authentication required routes
Route::middleware('auth:sanctum')->group(function () {
    // User profile routes
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);
    
    // User management routes (admin only)
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::apiResource('users', UserController::class);
    });
    
    // Campaign routes
    Route::apiResource('campaigns', CampaignController::class);
    
    // Video routes
    Route::apiResource('videos', VideoController::class);
    Route::post('/videos/upload', [\App\Http\Controllers\Api\VideoUploadController::class, 'upload']);
    
    // Analytics routes
    Route::get('/analytics', [AnalyticsController::class, 'index']);
    Route::get('/analytics/summary', [AnalyticsController::class, 'summary']);
    Route::get('/analytics/campaigns/{campaign}/summary', [AnalyticsController::class, 'campaignSummary']);
    Route::get('/analytics/videos/{video}/summary', [AnalyticsController::class, 'videoSummary']);
});

// Get authenticated user info
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});