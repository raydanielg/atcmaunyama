<?php

use Illuminate\Support\Facades\Route;

// Public mobile app settings endpoint
Route::middleware('api')->get('/mobile/settings', [\App\Http\Controllers\Api\MobileSettingsController::class, 'show']);

// Public content endpoints (mobile)
Route::middleware('api')->group(function () {
    Route::get('/mobile/content/levels', [\App\Http\Controllers\Api\ContentController::class, 'levels']);
    Route::get('/mobile/content/subjects', [\App\Http\Controllers\Api\ContentController::class, 'subjects']);
    Route::get('/mobile/content/classes', [\App\Http\Controllers\Api\ContentController::class, 'classes']);
    Route::get('/mobile/content/notes', [\App\Http\Controllers\Api\ContentController::class, 'notes']);
    Route::get('/mobile/content/notes/{id}', [\App\Http\Controllers\Api\ContentController::class, 'note']);
    Route::get('/mobile/content/notes/{id}/download', [\App\Http\Controllers\Api\ContentController::class, 'download'])->name('api.notes.download');
});
