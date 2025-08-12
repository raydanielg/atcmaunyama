<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'admin'])->name('dashboard');

// Admin-only routes for sidebar sections
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    // User Management
    Route::get('/users', [\App\Http\Controllers\Admin\UsersController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [\App\Http\Controllers\Admin\UsersController::class, 'show'])->name('users.show');
    Route::put('/users/{user}/role', [\App\Http\Controllers\Admin\UsersController::class, 'updateRole'])->name('users.update_role');
    Route::post('/users/{user}/ban', [\App\Http\Controllers\Admin\UsersController::class, 'ban'])->name('users.ban');
    Route::post('/users/{user}/unban', [\App\Http\Controllers\Admin\UsersController::class, 'unban'])->name('users.unban');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UsersController::class, 'destroy'])->name('users.destroy');

    // Learning - Notes
    Route::get('/learning/notes', [\App\Http\Controllers\Admin\NotesController::class, 'index'])->name('learning.notes.index');
    Route::get('/learning/notes/create', [\App\Http\Controllers\Admin\NotesController::class, 'create'])->name('learning.notes.create');
    Route::post('/learning/notes', [\App\Http\Controllers\Admin\NotesController::class, 'store'])->name('learning.notes.store');
    Route::get('/learning/notes/{note}', [\App\Http\Controllers\Admin\NotesController::class, 'show'])->name('learning.notes.show');
    Route::get('/learning/notes/{note}/edit', [\App\Http\Controllers\Admin\NotesController::class, 'edit'])->name('learning.notes.edit');
    Route::put('/learning/notes/{note}', [\App\Http\Controllers\Admin\NotesController::class, 'update'])->name('learning.notes.update');
    Route::delete('/learning/notes/{note}', [\App\Http\Controllers\Admin\NotesController::class, 'destroy'])->name('learning.notes.destroy');
    // JSON endpoints for dependent selects
    Route::get('/learning/notes-levels', [\App\Http\Controllers\Admin\NotesController::class, 'levels'])->name('learning.notes.levels');
    Route::get('/learning/notes-subjects', [\App\Http\Controllers\Admin\NotesController::class, 'subjects'])->name('learning.notes.subjects');
    Route::get('/learning/notes-classes', [\App\Http\Controllers\Admin\NotesController::class, 'classes'])->name('learning.notes.classes');
    Route::get('/learning/levels', [\App\Http\Controllers\Admin\LevelsController::class, 'index'])->name('learning.levels.index');
    Route::post('/learning/levels', [\App\Http\Controllers\Admin\LevelsController::class, 'store'])->name('learning.levels.store');
    Route::put('/learning/levels/{level}', [\App\Http\Controllers\Admin\LevelsController::class, 'update'])->name('learning.levels.update');
    Route::delete('/learning/levels/{level}', [\App\Http\Controllers\Admin\LevelsController::class, 'destroy'])->name('learning.levels.destroy');
    Route::get('/learning/levels/{level}/classes-json', [\App\Http\Controllers\Admin\LevelsController::class, 'classesJson'])->name('learning.levels.classes_json');
    // Assign classes to Level (one-to-many via level_id)
    Route::get('/learning/levels/{level}/assign-classes-json', [\App\Http\Controllers\Admin\LevelsController::class, 'classesAssignJson'])->name('learning.levels.assign_classes_json');
    Route::post('/learning/levels/{level}/classes', [\App\Http\Controllers\Admin\LevelsController::class, 'syncClasses'])->name('learning.levels.classes.sync');
    Route::post('/learning/levels/ai-suggest-description', [\App\Http\Controllers\Admin\LevelsController::class, 'aiSuggestDescription'])->name('learning.levels.ai_suggest');
    Route::get('/learning/levels/{level}/edit', [\App\Http\Controllers\Admin\LevelsController::class, 'edit'])->name('learning.levels.edit');
    Route::put('/learning/levels/{level}', [\App\Http\Controllers\Admin\LevelsController::class, 'update'])->name('learning.levels.update');
    Route::delete('/learning/levels/{level}', [\App\Http\Controllers\Admin\LevelsController::class, 'destroy'])->name('learning.levels.destroy');
    Route::get('/learning/subjects', [\App\Http\Controllers\Admin\SubjectsController::class, 'index'])->name('learning.subjects.index');
    Route::get('/learning/subjects/suggest', [\App\Http\Controllers\Admin\SubjectsController::class, 'suggest'])->name('learning.subjects.suggest');
    Route::post('/learning/subjects', [\App\Http\Controllers\Admin\SubjectsController::class, 'store'])->name('learning.subjects.store');
    Route::put('/learning/subjects/{subject}', [\App\Http\Controllers\Admin\SubjectsController::class, 'update'])->name('learning.subjects.update');
    Route::delete('/learning/subjects/{subject}', [\App\Http\Controllers\Admin\SubjectsController::class, 'destroy'])->name('learning.subjects.destroy');
    // Assign Classes to Subject
    Route::get('/learning/subjects/{subject}/classes-json', [\App\Http\Controllers\Admin\SubjectsController::class, 'classesJson'])->name('learning.subjects.classes_json');
    Route::post('/learning/subjects/{subject}/classes', [\App\Http\Controllers\Admin\SubjectsController::class, 'syncClasses'])->name('learning.subjects.classes.sync');
    // Learning - Classes
    Route::get('/learning/classes', [\App\Http\Controllers\Admin\ClassesController::class, 'index'])->name('learning.classes.index');
    Route::get('/learning/classes/suggest', [\App\Http\Controllers\Admin\ClassesController::class, 'suggest'])->name('learning.classes.suggest');
    Route::post('/learning/classes', [\App\Http\Controllers\Admin\ClassesController::class, 'store'])->name('learning.classes.store');
    Route::put('/learning/classes/{class}', [\App\Http\Controllers\Admin\ClassesController::class, 'update'])->name('learning.classes.update');
    Route::delete('/learning/classes/{class}', [\App\Http\Controllers\Admin\ClassesController::class, 'destroy'])->name('learning.classes.destroy');
    Route::post('/learning/classes/{class}/subjects', [\App\Http\Controllers\Admin\ClassesController::class, 'syncSubjects'])->name('learning.classes.subjects.sync');

    // Learning Materials
    Route::get('/materials', [\App\Http\Controllers\Admin\MaterialsController::class, 'index'])->name('materials.index');
    // Categories
    Route::get('/materials/categories', [\App\Http\Controllers\Admin\CategoriesController::class, 'index'])->name('materials.categories.index');
    Route::get('/materials/categories/suggest', [\App\Http\Controllers\Admin\CategoriesController::class, 'suggest'])->name('materials.categories.suggest');
    Route::post('/materials/categories', [\App\Http\Controllers\Admin\CategoriesController::class, 'store'])->name('materials.categories.store');
    Route::put('/materials/categories/{category}', [\App\Http\Controllers\Admin\CategoriesController::class, 'update'])->name('materials.categories.update');
    Route::delete('/materials/categories/{category}', [\App\Http\Controllers\Admin\CategoriesController::class, 'destroy'])->name('materials.categories.destroy');

    // Subcategories
    Route::get('/materials/subcategories', [\App\Http\Controllers\Admin\SubcategoriesController::class, 'index'])->name('materials.subcategories.index');
    Route::get('/materials/subcategories/suggest', [\App\Http\Controllers\Admin\SubcategoriesController::class, 'suggest'])->name('materials.subcategories.suggest');
    Route::post('/materials/subcategories', [\App\Http\Controllers\Admin\SubcategoriesController::class, 'store'])->name('materials.subcategories.store');
    Route::put('/materials/subcategories/{subcategory}', [\App\Http\Controllers\Admin\SubcategoriesController::class, 'update'])->name('materials.subcategories.update');
    Route::delete('/materials/subcategories/{subcategory}', [\App\Http\Controllers\Admin\SubcategoriesController::class, 'destroy'])->name('materials.subcategories.destroy');

    // AJAX endpoints
    Route::get('/materials/suggest', [\App\Http\Controllers\Admin\MaterialsController::class, 'suggest'])->name('materials.suggest');
    Route::get('/materials/subcategories/json', [\App\Http\Controllers\Admin\MaterialsController::class, 'subcategories'])->name('materials.subcategories');
    // Materials CRUD
    Route::post('/materials', [\App\Http\Controllers\Admin\MaterialsController::class, 'store'])->name('materials.store');
    Route::put('/materials/{material}', [\App\Http\Controllers\Admin\MaterialsController::class, 'update'])->name('materials.update');
    Route::delete('/materials/{material}', [\App\Http\Controllers\Admin\MaterialsController::class, 'destroy'])->name('materials.destroy');

    // Mobile App
    Route::get('/mobile/notifications', [\App\Http\Controllers\Admin\MobileNotificationsController::class, 'index'])->name('mobile.notifications.index');
    Route::post('/mobile/notifications/send', [\App\Http\Controllers\Admin\MobileNotificationsController::class, 'send'])->name('mobile.notifications.send');
    Route::post('/mobile/notifications/update-app', [\App\Http\Controllers\Admin\MobileNotificationsController::class, 'updateApp'])->name('mobile.notifications.update_app');
    Route::get('/mobile/maintenance', [\App\Http\Controllers\Admin\MaintenanceController::class, 'index'])->name('mobile.maintenance');
    Route::post('/mobile/maintenance/toggle', [\App\Http\Controllers\Admin\MaintenanceController::class, 'toggle'])->name('mobile.maintenance.toggle');
    Route::post('/mobile/maintenance/message', [\App\Http\Controllers\Admin\MaintenanceController::class, 'saveMessage'])->name('mobile.maintenance.message');
    Route::get('/mobile/settings', [\App\Http\Controllers\Admin\MobileAppSettingsController::class, 'index'])->name('mobile.settings');
    Route::post('/mobile/settings', [\App\Http\Controllers\Admin\MobileAppSettingsController::class, 'update'])->name('mobile.settings.update');
    Route::view('/mobile/api', 'admin.mobile.api')->name('mobile.api');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'update'])->name('settings.update');
    Route::get('/settings/backup', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'backup'])->name('settings.backup');
    Route::post('/settings/test-mail', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'testMail'])->name('settings.test_mail');

    // Admin Profile
    Route::get('/admin/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'index'])->name('admin.profile.index');
    Route::post('/admin/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'update'])->name('admin.profile.update');
    Route::post('/admin/profile/password', [\App\Http\Controllers\Admin\AdminProfileController::class, 'updatePassword'])->name('admin.profile.password');

    

    // Layout mode toggle (fixed header+sidebar vs normal)
    Route::post('/layout/toggle', function() {
        $fixed = session('layout_fixed', false);
        session(['layout_fixed' => !$fixed]);
        return back();
    })->name('layout.toggle');

    // Menu search (AJAX)
    Route::get('/menu/search', function(Request $request) {
        $q = trim((string)$request->query('q', ''));
        $items = [
            // Dashboard
            ['title' => 'Dashboard', 'section' => 'Main', 'url' => route('dashboard')],

            // User Management
            ['title' => 'Users', 'section' => 'User Management', 'url' => route('users.index')],

            // Learning
            ['title' => 'All Notes', 'section' => 'Learning', 'url' => route('learning.notes.index')],
            ['title' => 'Education Levels', 'section' => 'Learning', 'url' => route('learning.levels.index')],
            ['title' => 'Subjects', 'section' => 'Learning', 'url' => route('learning.subjects.index')],
            ['title' => 'Classes', 'section' => 'Learning', 'url' => route('learning.classes.index')],

            // Materials
            ['title' => 'All Materials', 'section' => 'Learning Materials', 'url' => route('materials.index')],
            ['title' => 'Material Categories', 'section' => 'Learning Materials', 'url' => route('materials.categories.index')],
            ['title' => 'Material Sub Categories', 'section' => 'Learning Materials', 'url' => route('materials.subcategories.index')],

            // Mobile App
            ['title' => 'Notifications', 'section' => 'Mobile App', 'url' => route('mobile.notifications.index')],
            ['title' => 'Maintenance Mode', 'section' => 'Mobile App', 'url' => route('mobile.maintenance')],
            ['title' => 'General Settings (Mobile)', 'section' => 'Mobile App', 'url' => route('mobile.settings')],
            ['title' => 'API Documents', 'section' => 'Mobile App', 'url' => route('mobile.api')],

            

            // Settings
            ['title' => 'General Settings', 'section' => 'Settings', 'url' => route('settings.index')],
            ['title' => 'My Profile', 'section' => 'Settings', 'url' => route('admin.profile.index')],
        ];

        if ($q === '') {
            return response()->json([]);
        }
        $needle = mb_strtolower($q);
        $results = array_values(array_filter($items, function($it) use ($needle) {
            return str_contains(mb_strtolower($it['title']), $needle)
                || str_contains(mb_strtolower($it['section']), $needle);
        }));
        // Limit to 10
        $results = array_slice($results, 0, 10);
        return response()->json($results);
    })->name('menu.search');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Authenticated Welcome route for mobile deep link & fallback
Route::middleware('auth')->get('/welcome', function () {
    return view('auth.welcome', [
        'playStoreUrl' => config('services.mobile_app.play_store_url'),
        'androidPackage' => config('services.mobile_app.android_package'),
        'deeplinkScheme' => config('services.mobile_app.deeplink_scheme', 'app'),
        'redirectSeconds' => 5,
    ]);
})->name('welcome');

// Public material download by slug
Route::get('/m/{slug}', [\App\Http\Controllers\Admin\MaterialsController::class, 'download'])->name('materials.download');
