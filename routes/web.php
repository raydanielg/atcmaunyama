<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Installer (disabled by default via INSTALLER_ENABLED=false)
if (env('INSTALLER_ENABLED', false)) {
    Route::get('/install', [\App\Http\Controllers\InstallController::class, 'index'])->name('install.index');
    Route::post('/install', [\App\Http\Controllers\InstallController::class, 'store'])->name('install.store');
}

Route::get('/', function () {
    // Redirect to login page for unauthenticated users
    return redirect()->route('login');
})->middleware('guest');

// Public Blog pages
Route::get('/blog', [\App\Http\Controllers\BlogPublicController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [\App\Http\Controllers\BlogPublicController::class, 'show'])->name('blog.show');

// Redirect authenticated users to appropriate dashboard
Route::middleware('auth')->get('/home', function () {
    $user = auth()->user();

    // Check if user is admin and redirect accordingly
    if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
        return redirect()->route('dashboard');
    }

    // Redirect regular users to user dashboard
    return redirect()->route('user.dashboard');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'admin'])->name('dashboard');

// User Dashboard (non-admin)
Route::middleware(['auth'])->get('/user/dashboard', function () {
    return view('user.dashboard');
})->name('user.dashboard');

// User Classes (non-admin, inside user panel)
Route::middleware(['auth'])->get('/user/classes', function () {
    $siteSettings = \App\Models\AdminSetting::first();
    $classes = \App\Models\SchoolClass::with(['subject','subjects'])
        ->orderBy('name')
        ->get();
    return view('user.classes', compact('siteSettings','classes'));
})->name('user.classes.index');

// User Class details (subjects + notes/materials assigned)
Route::middleware(['auth'])->get('/user/classes/{class}', function (\App\Models\SchoolClass $class) {
    $siteSettings = \App\Models\AdminSetting::first();
    $class->load(['subject','subjects']);
    $subjects = collect([$class->subject])->filter()->merge($class->subjects ?? collect());
    $selectedSubjectId = request()->integer('subject_id');
    $selectedNoteId = request()->integer('note_id');

    $notes = \App\Models\Note::query()
        ->where('class_id', $class->id)
        ->when($selectedSubjectId, function ($q) use ($selectedSubjectId) {
            $q->where('subject_id', $selectedSubjectId);
        })
        ->latest('created_at')
        ->get();

    return view('user.class-show', [
        'siteSettings' => $siteSettings,
        'class' => $class,
        'subjects' => $subjects,
        'selectedSubjectId' => $selectedSubjectId,
        'notes' => $notes,
        'selectedNoteId' => $selectedNoteId,
    ]);
})->name('user.classes.show');

// Public Classes page
Route::get('/classes', function () {
    $siteSettings = \App\Models\AdminSetting::first();
    $classes = \App\Models\SchoolClass::with(['subject','subjects'])
        ->orderBy('name')
        ->get();
    // Pass visitors counter for consistent footer display
    if (!cache()->has('visitors_count')) {
        cache()->forever('visitors_count', 0);
    }
    $visitorsCount = (int) cache()->get('visitors_count', 0);
    return view('classes', compact('siteSettings', 'classes', 'visitorsCount'));
})->name('classes.index');

// Public FAQ page
Route::get('/faq', function () {
    $siteSettings = \App\Models\AdminSetting::first();
    if (!cache()->has('visitors_count')) {
        cache()->forever('visitors_count', 0);
    }
    $visitorsCount = (int) cache()->get('visitors_count', 0);
    return view('faq', compact('siteSettings', 'visitorsCount'));
})->name('faq.index');

// Public simple email subscription (DB-backed)
Route::post('/subscribe', function (\Illuminate\Http\Request $request) {
    $data = $request->validate([
        'email' => ['required','email','max:255','unique:subscribers,email'],
    ]);

    \App\Models\Subscriber::create([
        'email' => $data['email'],
        'ip' => $request->ip(),
        'user_agent' => (string) $request->header('User-Agent'),
    ]);

    return back()->with('subscribed', true);
})->name('subscribe');

// Social login (web) - Google/GitHub/Facebook (PUBLIC)
Route::get('/oauth/{provider}/redirect', [\App\Http\Controllers\Auth\SocialLoginController::class, 'redirect'])
    ->whereIn('provider', ['google','github','facebook'])
    ->name('oauth.redirect');
Route::get('/oauth/{provider}/callback', [\App\Http\Controllers\Auth\SocialLoginController::class, 'callback'])
    ->whereIn('provider', ['google','github','facebook'])
    ->name('oauth.callback');

// Admin-only routes for sidebar sections
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    // Admin Home (dashboard landing)
    Route::get('/admin', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/users', [\App\Http\Controllers\Admin\UsersController::class, 'index'])->name('users.index');
    Route::post('/users', [\App\Http\Controllers\Admin\UsersController::class, 'store'])->name('users.store');
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
    Route::get('/learning/notes-semisters', [\App\Http\Controllers\Admin\NotesController::class, 'semisters'])->name('learning.notes.semisters');
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
    // Categories (Material Level) routes removed (read-only module deprecated)

    // Subcategories
    Route::get('/materials/subcategories', [\App\Http\Controllers\Admin\SubcategoriesController::class, 'index'])->name('materials.subcategories.index');
    Route::get('/materials/subcategories/suggest', [\App\Http\Controllers\Admin\SubcategoriesController::class, 'suggest'])->name('materials.subcategories.suggest');
    Route::post('/materials/subcategories', [\App\Http\Controllers\Admin\SubcategoriesController::class, 'store'])->name('materials.subcategories.store');
    Route::put('/materials/subcategories/{subcategory}', [\App\Http\Controllers\Admin\SubcategoriesController::class, 'update'])->name('materials.subcategories.update');
    Route::delete('/materials/subcategories/{subcategory}', [\App\Http\Controllers\Admin\SubcategoriesController::class, 'destroy'])->name('materials.subcategories.destroy');

    // Material Sub Type (Sub Subcategories)
    Route::get('/materials/subsubcategories', [\App\Http\Controllers\Admin\SubSubcategoriesController::class, 'index'])->name('materials.subsubcategories.index');
    Route::post('/materials/subsubcategories', [\App\Http\Controllers\Admin\SubSubcategoriesController::class, 'store'])->name('materials.subsubcategories.store');
    Route::put('/materials/subsubcategories/{subsubcategory}', [\App\Http\Controllers\Admin\SubSubcategoriesController::class, 'update'])->name('materials.subsubcategories.update');
    Route::delete('/materials/subsubcategories/{subsubcategory}', [\App\Http\Controllers\Admin\SubSubcategoriesController::class, 'destroy'])->name('materials.subsubcategories.destroy');
    // Bulk actions by name
    Route::put('/materials/subsubcategories/by-name/{name}', [\App\Http\Controllers\Admin\SubSubcategoriesController::class, 'updateByName'])->name('materials.subsubcategories.update_by_name');
    Route::delete('/materials/subsubcategories/by-name/{name}', [\App\Http\Controllers\Admin\SubSubcategoriesController::class, 'destroyByName'])->name('materials.subsubcategories.destroy_by_name');
    // Reset assigned materials for a specific sub subcategory
    Route::post('/materials/subsubcategories/{subsubcategory}/reset-materials', [\App\Http\Controllers\Admin\SubSubcategoriesController::class, 'resetMaterials'])->name('materials.subsubcategories.reset_materials');

    // AJAX endpoints
    Route::get('/materials/suggest', [\App\Http\Controllers\Admin\MaterialsController::class, 'suggest'])->name('materials.suggest');
    Route::get('/materials/subcategories/json', [\App\Http\Controllers\Admin\MaterialsController::class, 'subcategories'])->name('materials.subcategories');
    Route::get('/materials/subsubcategories/by-type', [\App\Http\Controllers\Admin\MaterialsController::class, 'subsubcategoriesByType'])->name('materials.subsubcategories.by_type');
    // Materials CRUD
    Route::post('/materials', [\App\Http\Controllers\Admin\MaterialsController::class, 'store'])->name('materials.store');
    Route::put('/materials/{material}', [\App\Http\Controllers\Admin\MaterialsController::class, 'update'])->name('materials.update');
    Route::delete('/materials/{material}', [\App\Http\Controllers\Admin\MaterialsController::class, 'destroy'])->name('materials.destroy');

    // Mobile App
    Route::get('/mobile/notifications', [\App\Http\Controllers\Admin\MobileNotificationsController::class, 'index'])->name('mobile.notifications.index');
    Route::post('/mobile/notifications/send', [\App\Http\Controllers\Admin\MobileNotificationsController::class, 'send'])->name('mobile.notifications.send');
    // Accept both POST (preferred) and GET (fallback) to avoid 405 from external callers
    Route::post('/mobile/notifications/update-app', [\App\Http\Controllers\Admin\MobileNotificationsController::class, 'updateApp'])->name('mobile.notifications.update_app');
    Route::get('/mobile/notifications/update-app', [\App\Http\Controllers\Admin\MobileNotificationsController::class, 'updateApp']);
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

    // Admin Profile (restored)
    Route::get('/admin/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'index'])->name('admin.profile.index');
    Route::post('/admin/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'update'])->name('admin.profile.update');
    Route::post('/admin/profile/password', [\App\Http\Controllers\Admin\AdminProfileController::class, 'updatePassword'])->name('admin.profile.password');

    // Semesters
    Route::get('/semesters', [\App\Http\Controllers\Admin\SemisterController::class, 'index'])->name('admin.semisters.index');
    Route::get('/semesters/create', [\App\Http\Controllers\Admin\SemisterController::class, 'create'])->name('admin.semisters.create');
    Route::post('/semesters', [\App\Http\Controllers\Admin\SemisterController::class, 'store'])->name('admin.semisters.store');
    Route::get('/semesters/{semister}', [\App\Http\Controllers\Admin\SemisterController::class, 'show'])->name('admin.semisters.show');
    Route::get('/semesters/{semister}/edit', [\App\Http\Controllers\Admin\SemisterController::class, 'edit'])->name('admin.semisters.edit');
    Route::put('/semesters/{semister}', [\App\Http\Controllers\Admin\SemisterController::class, 'update'])->name('admin.semisters.update');
    Route::delete('/semesters/{semister}', [\App\Http\Controllers\Admin\SemisterController::class, 'destroy'])->name('admin.semisters.destroy');
    Route::patch('/semesters/{semister}/toggle-status', [\App\Http\Controllers\Admin\SemisterController::class, 'toggleStatus'])->name('admin.semisters.toggle_status');

    // CMS - Blog
    Route::prefix('cms/blog')->name('cms.blog.')->group(function(){
        Route::get('/posts', [\App\Http\Controllers\Admin\BlogPostsController::class, 'index'])->name('posts.index');
        Route::get('/posts/create', [\App\Http\Controllers\Admin\BlogPostsController::class, 'create'])->name('posts.create');
        Route::post('/posts', [\App\Http\Controllers\Admin\BlogPostsController::class, 'store'])->name('posts.store');
        Route::get('/posts/{post}/edit', [\App\Http\Controllers\Admin\BlogPostsController::class, 'edit'])->name('posts.edit');
        Route::put('/posts/{post}', [\App\Http\Controllers\Admin\BlogPostsController::class, 'update'])->name('posts.update');
        Route::delete('/posts/{post}', [\App\Http\Controllers\Admin\BlogPostsController::class, 'destroy'])->name('posts.destroy');

        // Comments management
        Route::get('/comments', [\App\Http\Controllers\Admin\BlogCommentsController::class, 'index'])->name('comments.index');
        Route::get('/comments/{comment}', [\App\Http\Controllers\Admin\BlogCommentsController::class, 'show'])->name('comments.show');
        Route::post('/comments/{comment}/reply', [\App\Http\Controllers\Admin\BlogCommentsController::class, 'reply'])->name('comments.reply');
        Route::delete('/comments/{comment}', [\App\Http\Controllers\Admin\BlogCommentsController::class, 'destroy'])->name('comments.destroy');
    });

    // Admin - Notifications
    Route::prefix('admin/notifications')->name('admin.notifications.')->group(function(){
        Route::get('/', [\App\Http\Controllers\Admin\NotificationsController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\NotificationsController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\NotificationsController::class, 'store'])->name('store');
        Route::get('/{notification}', [\App\Http\Controllers\Admin\NotificationsController::class, 'show'])->name('show');
        Route::get('/{notification}/edit', [\App\Http\Controllers\Admin\NotificationsController::class, 'edit'])->name('edit');
        Route::put('/{notification}', [\App\Http\Controllers\Admin\NotificationsController::class, 'update'])->name('update');
        Route::delete('/{notification}', [\App\Http\Controllers\Admin\NotificationsController::class, 'destroy'])->name('destroy');
        Route::post('/{notification}/publish', [\App\Http\Controllers\Admin\NotificationsController::class, 'publish'])->name('publish');
        Route::post('/{notification}/unpublish', [\App\Http\Controllers\Admin\NotificationsController::class, 'unpublish'])->name('unpublish');
        Route::post('/{notification}/resend', [\App\Http\Controllers\Admin\NotificationsController::class, 'resend'])->name('resend');
    });

    

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
            ['title' => 'Courses', 'section' => 'Learning', 'url' => route('learning.levels.index')],
            ['title' => 'Subjects', 'section' => 'Learning', 'url' => route('learning.subjects.index')],
            ['title' => 'Classes', 'section' => 'Learning', 'url' => route('learning.classes.index')],

            // Learning Materials
            ['title' => 'All Materials', 'section' => 'Learning Materials', 'url' => route('materials.index')],
            ['title' => 'Material Type', 'section' => 'Learning Materials', 'url' => route('materials.subcategories.index')],
            ['title' => 'Material Sub Type', 'section' => 'Learning Materials', 'url' => route('materials.subsubcategories.index')],

            // Semesters
            ['title' => 'Semesters', 'section' => 'Academic', 'url' => route('admin.semisters.index')],

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
// Public inline preview by material id
Route::get('/materials/{material}/preview', [\App\Http\Controllers\Admin\MaterialsController::class, 'preview'])->name('materials.preview');
// Public inline preview for notes
Route::get('/notes/{note}/preview', [\App\Http\Controllers\Admin\NotesController::class, 'preview'])->name('notes.preview');
