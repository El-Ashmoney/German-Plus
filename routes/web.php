<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\BugsController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TrainController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\WordsController;
use App\Http\Controllers\LevelsController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FrontendContoller;
use App\Http\Controllers\GrammarController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\UserWordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VideoSlotController;
use App\Http\Controllers\RegisterationController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SlotsCategoryController;
use App\Http\Controllers\ForgotPasswordController;

// Authentication
Route::get('/register', [RegisterationController::class, 'create']);
Route::post('/register', [RegisterationController::class, 'store'])->name('user.register');

Route::get('/login', [SessionsController::class, 'create']);
Route::post('/login', [SessionsController::class, 'store'])->name('user.login');
Route::get('/logout', [SessionsController::class, 'destroy'])->name('user.logout');

// Password Reset
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.reset');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.token');
Route::post('password/reset', [ResetPasswordController::class, 'reset']);

// Admin area
Route::prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'overview'])->name('admin.index');

    Route::post('todo/done', [TodoController::class, 'done'])->name('todo.done');
    Route::post('todo/quick_destroy', [TodoController::class, 'quick_destroy'])->name('todo.quick_destroy');
    Route::post('todo/quick_update', [TodoController::class, 'quick_update'])->name('todo.quick_update');
    Route::resource('todo', TodoController::class);

    Route::resource('user', UserController::class);
    Route::resource('article', ArticleController::class);

    Route::post('category/save_words_order', [CategoryController::class, 'save_words_order'])->name('category.save_words_order');
    Route::get('category/words/{id}', [CategoryController::class, 'words'])->name('category.words');
    Route::get('category/export/{id}', [CategoryController::class, 'export'])->name('category.export');
    Route::resource('category', CategoryController::class);

    Route::resource('comment', CommentController::class);

    Route::post('word/favourite', [WordsController::class, 'favourite'])->name('word.favourite');
    Route::post('word/important', [WordsController::class, 'important'])->name('word.important');
    Route::post('word/valid', [WordsController::class, 'valid'])->name('word.valid');
    Route::post('word/quick_store', [WordsController::class, 'quick_store'])->name('word.quick_store');
    Route::post('word/quick_search', [WordsController::class, 'quick_search'])->name('word.quick_search');
    Route::post('word/quick_category_filter', [WordsController::class, 'quick_category_filter'])->name('word.quick_category_filter');
    Route::post('word/quick_trash', [WordsController::class, 'quick_trash'])->name('word.quick_trash');

    Route::get('word/favourites', [WordsController::class, 'favourites'])->name('word.favourites');
    Route::get('word/importants', [WordsController::class, 'importants'])->name('word.importants');
    Route::get('word/valids', [WordsController::class, 'valids'])->name('word.valids');
    Route::get('word/today', [WordsController::class, 'today'])->name('word.today');
    Route::get('word/test', [WordsController::class, 'today'])->name('word.test');
    Route::post('word/memorize', [WordsController::class, 'memorize'])->name('word.memorize');
    Route::get('word/import', [WordsController::class, 'import']);
    Route::resource('word', WordsController::class);

    Route::post('train/quick_search', [TrainController::class, 'quick_search'])->name('train.quick_search');
    Route::post('train/quick_trash', [TrainController::class, 'quick_trash'])->name('train.quick_trash');
    Route::post('train/quick_category_filter', [TrainController::class, 'quick_category_filter'])->name('train.quick_category_filter');
    Route::post('train/fetch_word', [TrainController::class, 'fetch_word'])->name('train.fetch_word');
    Route::post('train/fetch_words', [TrainController::class, 'fetch_words'])->name('train.fetch_words');
    Route::get('train/create/{id}', [TrainController::class, 'create'])->name('train.create_with_id');
    Route::resource('train', TrainController::class)->except(['show']);

    Route::resource('grammar', GrammarController::class);

    Route::get('options', [OptionController::class, 'edit'])->name('option.edit');
    Route::post('options', [OptionController::class, 'update'])->name('option.update');

    Route::get('bugs', [BugsController::class, 'index'])->name('bugs.index');

    Route::get('user/word/add', [UserWordController::class, 'create'])->name('user.word.add');
    Route::post('user/word', [UserWordController::class, 'store'])->name('user.word');

    Route::get('videos', [VideoController::class, 'index'])->name('video.index');
    Route::get('video/create', [VideoController::class, 'create'])->name('video.create');
    Route::get('video/edit/{id}', [VideoController::class, 'edit'])->name('video.edit');
    Route::post('video/update/{id}', [VideoController::class, 'update'])->name('video.update');
    Route::post('video/store', [VideoController::class, 'store'])->name('video.store');
    Route::get('video/delete/{id}', [VideoController::class, 'destroy'])->name('video.delete');

    Route::get('video_slot', [VideoSlotController::class, 'index'])->name('video_slot.index');
    Route::get('video_slot/create', [VideoSlotController::class, 'create'])->name('video_slot.create');
    Route::get('video_slot/create/{id}', [VideoSlotController::class, 'create'])->name('video_slot.create_with_id');
    Route::get('video_slot/edit/{id}', [VideoSlotController::class, 'edit'])->name('video_slot.edit');
    Route::post('video_slot/update/{id}', [VideoSlotController::class, 'update'])->name('video_slot.update');
    Route::post('video_slot/store', [VideoSlotController::class, 'store'])->name('video_slot.store');
    Route::get('video_slot/delete/{id}', [VideoSlotController::class, 'destroy'])->name('video_slot.delete');

    Route::resource('slot_category', SlotsCategoryController::class);

    Route::post('level/save_order', [LevelsController::class, 'save_order'])->name('level.save_order');
    Route::resource('level', LevelsController::class);
});

// Frontend
Route::get('/', [FrontendContoller::class, 'index']);
Route::get('/privacy_policy', [FrontendContoller::class, 'privacy_policy']);
Route::get('/service_terms', [FrontendContoller::class, 'service_terms']);
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
});