<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');

    // users
    Route::get('/add_user', 'HomeController@addUser');
    Route::get('/update_user/{id}', 'HomeController@updateUser');
    Route::post('/save_new_user', 'HomeController@saveNewUser')->name('save_new_user');
    Route::post('/save_user_update', 'HomeController@saveUserUpdates')->name('save_user_update');
    Route::get('/delete_user/{id}', 'HomeController@deleteUser');

    // article categories
    Route::get('/categories', 'ArticleCategoryController@index');
    Route::post('/add_category', 'ArticleCategoryController@addCategory');
    Route::post('/update_category/{id}', 'ArticleCategoryController@updateCategory');
    Route::get('/delete_category/{id}', 'ArticleCategoryController@deleteCategory');
});
