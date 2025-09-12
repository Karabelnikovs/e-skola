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
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Mail;

Route::get('/', [LoginController::class, 'home'])->name('home');


$locales = config('locale.supported');
foreach ($locales as $locale) {
    Route::prefix($locale)->middleware('web')->group(function () use ($locale) {
        // default auth
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name($locale . '.login');
        Route::post('/login', [LoginController::class, 'login'])->name($locale . '.login.post');
        Route::post('/logout', [LoginController::class, 'logout'])->name($locale . '.logout');

        Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name($locale . '.register');
        Route::post('/register', [RegisterController::class, 'register'])->name($locale . '.register.post');
        Route::get('/register/{token}', [RegisterController::class, 'showInvitedRegistrationForm'])->name($locale . '.register.token');

        // password reset 
        Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name($locale . '.password.request');
        Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name($locale . '.password.email');
        Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name($locale . '.password.update');

        // email verification 
        Route::get('email/verify', [VerificationController::class, 'show'])->name($locale . '.verification.notice');
        Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name($locale . '.verification.verify');
        Route::post('email/resend', [VerificationController::class, 'resend'])->name($locale . '.verification.resend');

        Route::middleware(['AuthCheck'])->group(function () use ($locale) {
            Route::get('contacts', [CourseController::class, 'showContacts'])->name($locale . '.contacts.show');
            Route::get('terms', [CourseController::class, 'showTerms'])->name($locale . '.terms.show');
            Route::get('privacy', [CourseController::class, 'showPrivacy'])->name($locale . '.privacy.show');
            Route::get('cookies', [CourseController::class, 'showCookies'])->name($locale . '.cookies.show');

            Route::get('/', [CourseController::class, 'index'])->name($locale . '.courses.index');

            Route::get('profile', [CourseController::class, 'profile'])->name($locale . '.profile');
            Route::post('profile', [CourseController::class, 'updateProfile'])->name('profile.update');

            Route::get('module/{id}', [CourseController::class, 'module'])->name($locale . '.module.show');
            Route::post('/courses/{id}/next', [CourseController::class, 'next'])->name($locale . '.courses.module.next');
            Route::post('/courses/{id}/previous', [CourseController::class, 'previous'])->name($locale . '.courses.module.previous');

            Route::get('test/{id}/{order_status}', [TestController::class, 'show'])->name($locale . '.courses.test');
            Route::post('/test/submit/{id}', [TestController::class, 'submit'])->name($locale . '.test.submit');
            Route::get('test/{id}/result', [TestController::class, 'result'])->name($locale . '.test.result');
            Route::get('/tests', [TestController::class, 'tests'])->name($locale . '.tests');

            Route::get('attempts/{id}', [TestController::class, 'attempts'])->name($locale . '.attempts.view');
            Route::get('attempt/{id}', [TestController::class, 'attempt'])->name($locale . '.attempt.view');

            Route::get('topic/{id}/{order_status}', [TopicController::class, 'topic'])->name($locale . '.courses.topic');
            Route::get('topics', [TopicController::class, 'topics'])->name($locale . '.topics.view');

            Route::get('certificate/{id}', [CertificateController::class, 'certificate'])->name($locale . '.certificate.show');
            Route::get('certificates', [CertificateController::class, 'certificates'])->name($locale . '.certificates');
            Route::get('certificate/download/{userID}/{courseID}', [CertificateController::class, 'download'])->name('certificate.download');

            Route::get('dictionaries', [CourseController::class, 'dictionaries'])->name($locale . '.dictionaries.view');
            Route::get('/dictionary/{id}/{order_status}', [CourseController::class, 'dictionary'])->name($locale . '.courses.dictionary');
        });
    });
}

Route::middleware(['AdminCheck'])->group(function () {

    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::get('/import-users', [AdminController::class, 'importUsers'])->name('admin.import.users');
    Route::post('/import-users', [AdminController::class, 'processImport'])->name('admin.import.users.post');

    Route::get('/topics', [AdminController::class, 'topics'])->name('topics');
    Route::get('topic/{id}', [AdminController::class, 'topic'])->name('topic.view');

    Route::get('certificates', [AdminController::class, 'certificatesUsers'])->name('certificates');
    Route::get('certificates/{id}', [AdminController::class, 'certificates'])->name('user.certificates');

    //modules
    Route::get('/all-modules', [AdminController::class, 'all_modules'])->name('module.all');
    Route::get('/module/create', [AdminController::class, 'create'])->name('module.create');
    Route::post('/module/store', [AdminController::class, 'store'])->name('module.store');
    Route::get('/module/{id}', [AdminController::class, 'module'])->name('module');

    Route::get('module/delete/{id}', [AdminController::class, 'deleteModule'])->name('module.delete');
    Route::get('test/delete/{id}', [AdminController::class, 'deleteTest'])->name('test.delete');
    Route::get('dictionary/delete/{id}', [AdminController::class, 'deleteDictionary'])->name('dictionary.delete');
    Route::get('topic/delete/{id}', [AdminController::class, 'deleteTopic'])->name('topic.delete');


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


    Route::get('contacts', [AdminController::class, 'contacts'])->name('contacts');
    Route::post('/contact-section/store', [AdminController::class, 'storeContacts']);

    Route::get('privacy', [AdminController::class, 'privacy'])->name('privacy');
    Route::post('privacy/store', [AdminController::class, 'storePrivacy'])->name('privacy.store');

    Route::get('welcome_email', [AdminController::class, 'welcomeEmail'])->name('welcome_email');
    Route::post('welcome_email/store', [AdminController::class, 'storeWelcomeEmail'])->name('welcome_email.store');

    Route::get('cookies', [AdminController::class, 'cookies'])->name('cookies');
    Route::post('cookies/store', [AdminController::class, 'storeCookies'])->name('cookies.store');

    Route::get('rules', [AdminController::class, 'useTerms'])->name('rules');
    Route::post('rules/store', [AdminController::class, 'storeTerms'])->name('rules.store');

    Route::get('users-progress', [AdminController::class, 'usersProgress'])->name('users-progress');
    Route::get('progress/{id}', [AdminController::class, 'progress'])->name('user.progress');
    Route::post('/test/{test}/toggle-final', [AdminController::class, 'toggleFinal'])->name('test.toggleFinal');
    Route::post('/module/{module}/toggle-public', [AdminController::class, 'togglePublic'])->name('module.togglePublic');

});

Route::get('/error', function () {
    return view('custom-error', [
        'reason' => session('reason', 'error')
    ]);
})->name('error');

Route::get('/in-development', function () {
    return view('in_progress');
})->name('development');

Route::get('/test-email', function () {
    Mail::raw('Test email from Laravel', function ($message) {
        $message->to('nikolajlivshic@gmail.com')
            ->subject('Test Email');
    });

    return 'Email sent';
});
