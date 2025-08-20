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

    // Materials taxonomy and lists
    Route::get('/mobile/content/categories', [\App\Http\Controllers\Api\ContentController::class, 'categories']);
    Route::get('/mobile/content/subcategories', [\App\Http\Controllers\Api\ContentController::class, 'subcategories']);
    Route::get('/mobile/content/materials', [\App\Http\Controllers\Api\ContentController::class, 'materials']);

    Route::get('/mobile/content/levels', [\App\Http\Controllers\Api\ContentController::class, 'levels']);
    Route::get('/mobile/content/subjects', [\App\Http\Controllers\Api\ContentController::class, 'subjects']);
    Route::get('/mobile/content/classes', [\App\Http\Controllers\Api\ContentController::class, 'classes']);
    
    // Mobile maintenance status (public)
    Route::get('/mobile/maintenance', [\App\Http\Controllers\Api\MaintenanceController::class, 'show']);

    // Mobile update-app ping (public)
    Route::get('/mobile/notifications/update-app', [\App\Http\Controllers\Api\MobileNotificationsController::class, 'updateApp']);
    Route::get('/mobile/content/classes/{id}/subject', [\App\Http\Controllers\Api\ContentController::class, 'classSubject']);
    Route::get('/mobile/content/notes', [\App\Http\Controllers\Api\ContentController::class, 'notes']);
    Route::get('/mobile/content/notes/{id}', [\App\Http\Controllers\Api\ContentController::class, 'note']);
    Route::get('/mobile/content/notes/{id}/preview', [\App\Http\Controllers\Api\ContentController::class, 'preview'])->name('api.notes.preview');
    Route::get('/mobile/content/notes/{id}/download', [\App\Http\Controllers\Api\ContentController::class, 'download'])->name('api.notes.download');
});

