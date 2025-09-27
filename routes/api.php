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

    // Public Materials Browsing API
    Route::prefix('public/materials')->group(function(){
        Route::get('/types', [\App\Http\Controllers\Api\MaterialsPublicController::class, 'types']);
        Route::get('/subtypes', [\App\Http\Controllers\Api\MaterialsPublicController::class, 'subtypes']);
        Route::get('/levels', [\App\Http\Controllers\Api\MaterialsPublicController::class, 'levels']);
        Route::get('/classes', [\App\Http\Controllers\Api\MaterialsPublicController::class, 'classes']);
        Route::get('/subjects', [\App\Http\Controllers\Api\MaterialsPublicController::class, 'subjects']);
        Route::get('/', [\App\Http\Controllers\Api\MaterialsPublicController::class, 'materials']);
    });

    // Public Blog API
    Route::prefix('public/blog')->group(function(){
        // List
        Route::get('/', [\App\Http\Controllers\Api\BlogController::class, 'index']);
        // Detail by slug
        Route::get('/{slug}', [\App\Http\Controllers\Api\BlogController::class, 'show']);
        // Image (hero) by slug
        Route::get('/{slug}/image', [\App\Http\Controllers\Api\BlogController::class, 'image']);
        // Optional detail by id for convenience
        Route::get('/id/{id}', [\App\Http\Controllers\Api\BlogController::class, 'showById']);
        // Comments (public; user_id optional)
        Route::post('/{slug}/comments', [\App\Http\Controllers\Api\BlogController::class, 'storeComment']);
        // Reactions
        Route::post('/{slug}/react', [\App\Http\Controllers\Api\BlogController::class, 'react']);
    });

    // Regions
    Route::get('/mobile/regions', [RegionController::class, 'index']);
    Route::get('/regions', [RegionController::class, 'index']);

    // Mobile notifications (public read-only)
    Route::get('/mobile/notifications', function () {
        $q = \App\Models\MobileNotification::query()
            ->orderByDesc('id')
            ->limit(50);
        // If you want to filter by status, uncomment next line e.g. only sent
        // $q->where('status', 'sent');
        $items = $q->get(['id','title','message','deep_link','scheduled_at','sent_at','status']);
        return response()->json(['data' => $items]);
    });

    // Materials taxonomy and lists (Material Level removed)
    Route::get('/mobile/content/subcategories', [\App\Http\Controllers\Api\ContentController::class, 'subcategories']);
    Route::get('/mobile/content/materials', [\App\Http\Controllers\Api\ContentController::class, 'materials']);

    Route::get('/mobile/content/levels', [\App\Http\Controllers\Api\ContentController::class, 'levels']);
    Route::get('/mobile/content/subjects', [\App\Http\Controllers\Api\ContentController::class, 'subjects']);
    Route::get('/mobile/content/classes', [\App\Http\Controllers\Api\ContentController::class, 'classes']);
    Route::get('/mobile/content/semisters', [\App\Http\Controllers\Api\ContentController::class, 'semisters']);
    
    // Mobile maintenance status (public)
    Route::get('/mobile/maintenance', [\App\Http\Controllers\Api\MaintenanceController::class, 'show']);

    // Mobile update-app ping (public)
    Route::get('/mobile/notifications/update-app', [\App\Http\Controllers\Api\MobileNotificationsController::class, 'updateApp']);
    Route::get('/mobile/content/classes/{id}/subject', [\App\Http\Controllers\Api\ContentController::class, 'classSubject']);
    // Notes require semister_id (and optional level_id, class_id, subject_id)
    Route::get('/mobile/content/notes', [\App\Http\Controllers\Api\ContentController::class, 'notes']);
    Route::get('/mobile/content/notes/{id}', [\App\Http\Controllers\Api\ContentController::class, 'note']);
    Route::get('/mobile/content/notes/{id}/preview', [\App\Http\Controllers\Api\ContentController::class, 'preview'])->name('api.notes.preview');
    Route::get('/mobile/content/notes/{id}/download', [\App\Http\Controllers\Api\ContentController::class, 'download'])->name('api.notes.download');
    
    // User Content Creation (requires authentication)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/user/notes', [\App\Http\Controllers\Api\UserContentController::class, 'storeNote']);
        Route::get('/user/notes', [\App\Http\Controllers\Api\UserContentController::class, 'myNotes']);
        
        Route::post('/user/materials', [\App\Http\Controllers\Api\UserContentController::class, 'storeMaterial']);
        Route::get('/user/materials', [\App\Http\Controllers\Api\UserContentController::class, 'myMaterials']);
        
        // Get taxonomy data for content creation
        Route::get('/user/taxonomy', [\App\Http\Controllers\Api\UserContentController::class, 'taxonomy']);
    });
});
