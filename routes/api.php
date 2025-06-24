<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiContoller;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [ApiContoller::class, 'api_login'])->name('api.login')->middleware('cors');
Route::post('/register', [ApiContoller::class, 'api_register']);
Route::post('/forget_password', [ApiContoller::class, 'forget_password_api']);
Route::post('/reset_password', [ApiContoller::class, 'reset_password_api']);
Route::post('/user_social', [ApiContoller::class, 'handle_user_social_api']);
Route::post('/user_info', [ApiContoller::class, 'user_info_api']);
Route::post('/full_user_info', [ApiContoller::class, 'full_user_info_api']);
Route::post('/user_level', [ApiContoller::class, 'user_level_api']);
Route::post('/user_points_plus', [ApiContoller::class, 'user_points_plus_api']);
Route::post('/update_user_info', [ApiContoller::class, 'update_user_info_api']);
Route::post('/categories', [ApiContoller::class, 'categories_api']);
Route::post('/mywords', [ApiContoller::class, 'mywords_api']);
Route::post('/category/{id}', [ApiContoller::class, 'category_words_api']);
Route::post('/category_train', [ApiContoller::class, 'category_train_api']);
Route::post('/category_train_completed', [ApiContoller::class, 'category_train_completed_api']);
Route::post('/username', [ApiContoller::class, 'username']);
Route::post('/train', [ApiContoller::class, 'today_api']);
Route::post('/favourite', [ApiContoller::class, 'favourite_api']);
Route::post('/important', [ApiContoller::class, 'important_api']);
Route::post('/add_word', [ApiContoller::class, 'new_word_api']);
Route::post('/report_bug', [ApiContoller::class, 'report_bug_api']);
Route::post('/set_favourite', [ApiContoller::class, 'set_favourite_api']);
Route::post('/set_important', [ApiContoller::class, 'set_important_api']);
Route::post('/trash_word', [ApiContoller::class, 'trash_word_api']);
Route::post('/settings', [ApiContoller::class, 'settings_api']);
Route::post('/get_settings', [ApiContoller::class, 'get_settings_api']);
Route::post('/dictionary', [ApiContoller::class, 'dictionary_api']);
Route::post('/dictionary_count', [ApiContoller::class, 'dictionary_count_api']);
Route::post('/dictionary_search', [ApiContoller::class, 'quick_search_api']);
Route::post('/find_word', [ApiContoller::class, 'find_word_api']);
Route::post('/video_slots', [ApiContoller::class, 'videos_slots_api']);
Route::post('/video_categories', [ApiContoller::class, 'video_categories_api']);
Route::post('/all_videos_slots', [ApiContoller::class, 'all_videos_slots_api']);
Route::post('/delete_account', [ApiContoller::class, 'delete_account_api']);
Route::post('/logout', [ApiContoller::class, 'api_logout']);
