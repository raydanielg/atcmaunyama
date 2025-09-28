<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\UserController;
use App\Http\Controllers\Api\Auth\GoogleController;
use App\Http\Controllers\Api\RegionController;

// Public mobile app settings endpoint
Route::middleware('api')->get('/mobile/settings', [\App\Http\Controllers\Api\MobileSettingsController::class, 'show']);

// Public content endpoints (mobile)
Route::middleware('api')->group(function () {
    // Auth
    Route::post('/auth/register', [RegisterController::class, 'store']);
    Route::post('/auth/google', [GoogleController::class, 'exchange']);
    Route::middleware('auth:sanctum')->get('/auth/me', [UserController::class, 'me']);

    // Materials taxonomy and lists (Material Level removed)
    Route::get('/mobile/content/subcategories', [\App\Http\Controllers\Api\ContentController::class, 'subcategories']);
    Route::get('/mobile/content/materials', [\App\Http\Controllers\Api\ContentController::class, 'materials']);

    Route::get('/mobile/content/levels', [\App\Http\Controllers\Api\ContentController::class, 'levels']);
    Route::get('/mobile/content/subjects', [\App\Http\Controllers\Api\ContentController::class, 'subjects']);
    Route::get('/mobile/content/subjects-for-class-semister', [\App\Http\Controllers\Api\ContentController::class, 'subjectsForClassSemister']);
    Route::get('/mobile/content/classes', [\App\Http\Controllers\Api\ContentController::class, 'classes']);
    Route::get('/mobile/content/semisters', [\App\Http\Controllers\Api\ContentController::class, 'semisters']);
    Route::get('/mobile/content/next-options', [\App\Http\Controllers\Api\ContentController::class, 'nextOptions']);
    
    // Mobile maintenance status (public)
    Route::get('/mobile/maintenance', [\App\Http\Controllers\Api\MaintenanceController::class, 'show']);
    // Mobile update-app ping (public)
    Route::get('/mobile/notifications/update-app', [\App\Http\Controllers\Api\MobileNotificationsController::class, 'updateApp']);
    // Mobile simple heartbeat
    Route::get('/mobile/notification', [\App\Http\Controllers\Api\MobileNotificationsController::class, 'ping']);
    // Notifications list (active only) and details
    Route::get('/mobile/notifications', [\App\Http\Controllers\Api\MobileNotificationsController::class, 'listActive']);
    Route::get('/mobile/notifications/{id}', [\App\Http\Controllers\Api\MobileNotificationsController::class, 'showActive']);
    // Tracking stats
    Route::post('/mobile/notifications/{id}/view', [\App\Http\Controllers\Api\MobileNotificationsController::class, 'trackView']);
    Route::post('/mobile/notifications/{id}/click', [\App\Http\Controllers\Api\MobileNotificationsController::class, 'trackClick']);
    // Click-through redirect (increments click and redirects to action_url)
    Route::get('/mobile/notifications/{id}/go', [\App\Http\Controllers\Api\MobileNotificationsController::class, 'go']);
    // Notes require semister_id (and optional level_id, class_id, subject_id)
    Route::get('/mobile/content/notes', [\App\Http\Controllers\Api\ContentController::class, 'notes']);
    Route::get('/mobile/content/notes/{id}', [\App\Http\Controllers\Api\ContentController::class, 'note']);
    // Public inline preview (named) so previewUrl works in API responses
    Route::get('/mobile/content/notes/{id}/preview', [\App\Http\Controllers\Api\ContentController::class, 'preview'])->name('api.notes.preview');
    Route::get('/mobile/content/notes/{id}/download', [\App\Http\Controllers\Api\ContentController::class, 'download'])->name('api.notes.download');

    // Public Blog API
    Route::prefix('public/blog')->group(function(){
        // List
        Route::get('/', [\App\Http\Controllers\Api\BlogController::class, 'index']);
        // Optional detail by id for convenience
        Route::get('/id/{id}', [\App\Http\Controllers\Api\BlogController::class, 'showById']);
        // Image (hero) by slug (must be before the catch-all slug route)
        Route::get('/{slug}/image', [\App\Http\Controllers\Api\BlogController::class, 'image']);
        // Detail by slug (keep last to avoid swallowing more specific routes)
        Route::get('/{slug}', [\App\Http\Controllers\Api\BlogController::class, 'show']);
        // Comments (public; user_id optional)
        Route::post('/{slug}/comments', [\App\Http\Controllers\Api\BlogController::class, 'storeComment']);
        // Reactions
        Route::post('/{slug}/react', [\App\Http\Controllers\Api\BlogController::class, 'react']);
    });
    
    // User Content Creation (requires authentication)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/user/notes', [\App\Http\Controllers\Api\UserContentController::class, 'storeNote']);
        Route::post('/user/materials', [\App\Http\Controllers\Api\UserContentController::class, 'storeMaterial']);
        Route::get('/user/materials', [\App\Http\Controllers\Api\UserContentController::class, 'myMaterials']);
        
        // Get taxonomy data for content creation
        Route::get('/user/taxonomy', [\App\Http\Controllers\Api\UserContentController::class, 'taxonomy']);
    });
});
