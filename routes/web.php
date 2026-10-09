<?php

use App\Http\Controllers\AdminTrainingController;
use App\Http\Controllers\AdminWeeklyTestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MotorcycleController;
use App\Http\Controllers\ProspectActivityController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\ProspectStatusController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WeeklyTestController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SIMPRO
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/home', function () {
    return redirect()->route('dashboard');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Prospects Management
    Route::get('/prospects/export', [ProspectController::class, 'export'])->name('prospects.export');
    Route::resource('prospects', ProspectController::class);
    Route::post('/prospects/{prospect}/activities', [ProspectActivityController::class, 'store'])->name('prospects.activities.store');

    // Training / Knowledge Sales (Supporting Feature)
    Route::get('/trainings', [TrainingController::class, 'index'])->name('trainings.index');
    Route::get('/trainings/{training}', [TrainingController::class, 'show'])->name('trainings.show');
    Route::post('/trainings/{training}/video-progress', [TrainingController::class, 'updateVideoProgress'])->name('trainings.video-progress');
    Route::post('/trainings/{training}/materials/{material}/complete', [TrainingController::class, 'completeMaterial'])->name('trainings.materials.complete');
    Route::get('/trainings/{training}/quiz', [TrainingController::class, 'quiz'])->name('trainings.quiz');
    Route::post('/trainings/{training}/quiz', [TrainingController::class, 'submitQuiz'])->name('trainings.quiz.submit');

    // Weekly Online Tests / Tes Mingguan (Supporting Feature)
    Route::get('/weekly-tests', [WeeklyTestController::class, 'index'])->name('weekly-tests.index');
    Route::get('/weekly-tests/{weeklyTest}/take', [WeeklyTestController::class, 'take'])->name('weekly-tests.take');
    Route::post('/weekly-tests/{weeklyTest}/submit', [WeeklyTestController::class, 'submit'])->name('weekly-tests.submit');
    Route::get('/weekly-tests/{weeklyTest}/result', [WeeklyTestController::class, 'result'])->name('weekly-tests.result');

    // Profile & Account Settings (Supporting Feature)
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Admin Only Routes
    Route::middleware(AdminMiddleware::class)->group(function () {
        // Master Data
        Route::get('/master/motorcycles', [MotorcycleController::class, 'index'])->name('master.motorcycles.index');
        Route::post('/master/motorcycles', [MotorcycleController::class, 'store'])->name('master.motorcycles.store');
        Route::post('/master/motorcycles/import', [MotorcycleController::class, 'import'])->name('master.motorcycles.import');
        Route::get('/master/motorcycles/template', [MotorcycleController::class, 'downloadTemplate'])->name('master.motorcycles.template');
        Route::put('/master/motorcycles/{motorcycle}', [MotorcycleController::class, 'update'])->name('master.motorcycles.update');
        Route::post('/master/motorcycles/bulk-delete', [MotorcycleController::class, 'bulkDestroy'])->name('master.motorcycles.bulk-delete');
        Route::post('/master/motorcycles/delete-all', [MotorcycleController::class, 'deleteAll'])->name('master.motorcycles.delete-all');
        Route::delete('/master/motorcycles/{motorcycle}', [MotorcycleController::class, 'destroy'])->name('master.motorcycles.destroy');
        Route::patch('/master/motorcycles/{motorcycle}/toggle', [MotorcycleController::class, 'toggleStatus'])->name('master.motorcycles.toggle');

        Route::get('/master/sources', [SourceController::class, 'index'])->name('master.sources.index');
        Route::post('/master/sources', [SourceController::class, 'store'])->name('master.sources.store');
        Route::put('/master/sources/{source}', [SourceController::class, 'update'])->name('master.sources.update');

        Route::get('/master/statuses', [ProspectStatusController::class, 'index'])->name('master.statuses.index');
        Route::post('/master/statuses', [ProspectStatusController::class, 'store'])->name('master.statuses.store');
        Route::put('/master/statuses/{status}', [ProspectStatusController::class, 'update'])->name('master.statuses.update');
        Route::delete('/master/statuses/{status}', [ProspectStatusController::class, 'destroy'])->name('master.statuses.destroy');

        // User Management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

        // Admin Training Management (Kelola Materi Pembelajaran)
        Route::get('/admin/trainings', [AdminTrainingController::class, 'index'])->name('admin.trainings.index');
        Route::get('/admin/trainings/create', [AdminTrainingController::class, 'create'])->name('admin.trainings.create');
        Route::post('/admin/trainings', [AdminTrainingController::class, 'store'])->name('admin.trainings.store');
        Route::get('/admin/trainings/{training}/edit', [AdminTrainingController::class, 'edit'])->name('admin.trainings.edit');
        Route::get('/admin/trainings/{training}/manage', [AdminTrainingController::class, 'manage'])->name('admin.trainings.manage');
        Route::put('/admin/trainings/{training}', [AdminTrainingController::class, 'update'])->name('admin.trainings.update');
        Route::patch('/admin/trainings/{training}/toggle', [AdminTrainingController::class, 'toggleStatus'])->name('admin.trainings.toggle');
        Route::delete('/admin/trainings/{training}', [AdminTrainingController::class, 'destroy'])->name('admin.trainings.destroy');
        Route::post('/admin/trainings/generate-ai-questions', [AdminTrainingController::class, 'generateAiQuestions'])->name('admin.trainings.generate-ai-questions');

        Route::post('/admin/trainings/{training}/materials', [AdminTrainingController::class, 'storeMaterial'])->name('admin.trainings.materials.store');
        Route::put('/admin/trainings/{training}/materials/{material}', [AdminTrainingController::class, 'updateMaterial'])->name('admin.trainings.materials.update');
        Route::delete('/admin/trainings/{training}/materials/{material}', [AdminTrainingController::class, 'destroyMaterial'])->name('admin.trainings.materials.destroy');

        Route::post('/admin/trainings/{training}/quizzes', [AdminTrainingController::class, 'storeQuiz'])->name('admin.trainings.quizzes.store');
        Route::delete('/admin/trainings/{training}/quizzes/{quiz}', [AdminTrainingController::class, 'destroyQuiz'])->name('admin.trainings.quizzes.destroy');

        // Admin Weekly Tests Management
        Route::get('/admin/weekly-tests', [AdminWeeklyTestController::class, 'index'])->name('admin.weekly-tests.index');
        Route::post('/admin/weekly-tests', [AdminWeeklyTestController::class, 'store'])->name('admin.weekly-tests.store');
        Route::get('/admin/weekly-tests/{weeklyTest}/manage', [AdminWeeklyTestController::class, 'manage'])->name('admin.weekly-tests.manage');
        Route::put('/admin/weekly-tests/{weeklyTest}', [AdminWeeklyTestController::class, 'update'])->name('admin.weekly-tests.update');
        Route::delete('/admin/weekly-tests/{weeklyTest}', [AdminWeeklyTestController::class, 'destroy'])->name('admin.weekly-tests.destroy');

        Route::post('/admin/weekly-tests/{weeklyTest}/questions', [AdminWeeklyTestController::class, 'storeQuestion'])->name('admin.weekly-tests.questions.store');
        Route::delete('/admin/weekly-tests/{weeklyTest}/questions/{question}', [AdminWeeklyTestController::class, 'destroyQuestion'])->name('admin.weekly-tests.questions.destroy');
    });
});
