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
