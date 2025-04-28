<?php

use App\Http\Controllers\CertificateController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AuthCheck;
use App\Http\Middleware\AdminCheck;

Route::get('/', [LoginController::class, 'home'])->name('home');


$locales = config('locale.supported');
foreach ($locales as $locale) {
    Route::prefix($locale)->middleware('web')->group(function () use ($locale) {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name($locale . '.login');
        Route::post('/login', [LoginController::class, 'login'])->name($locale . '.login.post');
        Route::post('/logout', [LoginController::class, 'logout'])->name($locale . '.logout');

        Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name($locale . '.register');
        Route::post('/register', [RegisterController::class, 'register'])->name($locale . '.register.post');

        Route::middleware(['AuthCheck'])->group(function () use ($locale) {
            Route::get('/', [CourseController::class, 'index'])->name($locale . '.courses.index');
            Route::get('profile', [CourseController::class, 'profile'])->name($locale . '.profile');

            Route::get('module/{id}', [CourseController::class, 'module'])->name($locale . '.module.show');
            Route::get('test/{id}', [TestController::class, 'show'])->name($locale . '.test.show');
            Route::post('/test/submit/{id}', [TestController::class, 'submit'])->name($locale . '.test.submit');
            Route::get('test/{id}/result', [TestController::class, 'result'])->name($locale . '.test.result');
            Route::get('/tests', [TestController::class, 'tests'])->name($locale . '.tests');

            Route::get('attempts/{id}', [TestController::class, 'attempts'])->name($locale . '.attempts.view');
            Route::get('attempt/{id}', [TestController::class, 'attempt'])->name($locale . '.attempt.view');

            Route::get('topic/{id}', [TopicController::class, 'topic'])->name($locale . '.topic.view');

            Route::get('certificate/{id}', [CertificateController::class, 'certificate'])->name($locale . '.certificate.show');
            Route::get('certificates', [CertificateController::class, 'certificates'])->name($locale . '.certificates');

            Route::get('dictionaries', [CourseController::class, 'dictionaries'])->name($locale . '.dictionaries.view');
            Route::get('/dictionary/{id}', [CourseController::class, 'dictionary'])->name($locale . '.dictionary.view');
        });
    });
}

Route::middleware(['AdminCheck'])->group(function () {

    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

    Route::get('/topics', [AdminController::class, 'topics'])->name('topics');
    Route::get('/sequences', [AdminController::class, 'sequences'])->name('sequences');

    //modules
    Route::get('/all-modules', [AdminController::class, 'all_modules'])->name('module.all');
    Route::get('/module/create', [AdminController::class, 'create'])->name('module.create');
    Route::post('/module/store', [AdminController::class, 'store'])->name('module.store');
    Route::get('/module/{id}', [AdminController::class, 'module'])->name('module');

    Route::get('/module/edit/{id}', [AdminController::class, 'edit'])->name('module.edit');
    Route::post('/module/update/{id}', [AdminController::class, 'update'])->name('module.update');

    Route::get('test/new/{id}', [AdminController::class, 'newTest'])->name('test.new');
    Route::post('tests/store', [AdminController::class, 'storeTest'])->name('test.store');
    Route::get('test/edit/{id}/{course_id}', [AdminController::class, 'editTest'])->name('test.edit');
    Route::post('test/update/{id}', [AdminController::class, 'updateTest'])->name('test.update');

    Route::get('topic/new/{id}', [AdminController::class, 'newTopic'])->name('topic.new');
    Route::post('topics/store', [AdminController::class, 'storeTopic'])->name('topics.store');
    Route::get('topic/edit/{id}/{course_id}', [AdminController::class, 'editTopic'])->name('topic.edit');
    Route::post('topic/update/{id}', [AdminController::class, 'updateTopic'])->name('topic.update');

    Route::get('dictionary/new/{id}', [AdminController::class, 'newDictionary'])->name('dictionary.new');
    Route::post('dictionary/store', [AdminController::class, 'storeDictionary'])->name('dictionary.store');
    Route::post('dictionary/update/{id}', [AdminController::class, 'updateDictionary'])->name('dictionary.update');
    Route::post('translation/store/{dictionaryId}', [AdminController::class, 'storeTranslation'])->name('translation.store');
    Route::post('translation/update/{id}', [AdminController::class, 'updateTranslation'])->name('translation.update');
    Route::delete('translation/delete/{id}', [AdminController::class, 'deleteTranslation'])->name('translation.delete');
    Route::post('dictionary/{dictionaryId}/translations/updateOrder', [AdminController::class, 'updateTranslationOrder'])->name('translation.updateOrder');
    Route::get('dictionary/edit/{id}', [AdminController::class, 'editDictionary'])->name('dictionary.edit');


    Route::post('/admin/sort-order', [AdminController::class, 'updateSortOrder'])->name('admin.sort.order');
    Route::post('/translate', [AdminController::class, 'translateText'])->name('translate');
    Route::post('/upload-image', [AdminController::class, 'uploadImage'])->name('upload.image');

    Route::get('tests/users-history', [AdminController::class, 'usersTests'])->name('tests.users');
    Route::get('tests/history/{id}', [AdminController::class, 'testsHistory'])->name('tests.history');
    Route::get('test/attempts/{id}/{userID}', [AdminController::class, 'attempts'])->name('test.attempts');
    Route::get('test/attempt/{id}', [AdminController::class, 'attempt'])->name('test.attempt');
});

Route::get('/error', function () {
    return view('custom-error', [
        'reason' => session('reason', 'error')
    ]);
})->name('error');

Route::get('/in-development', function () {
    return view('in_progress');
})->name('development');


