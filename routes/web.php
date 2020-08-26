<?php

use Illuminate\Support\Facades\Route;

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


Route::get('/', 'BlogController@blog')->name('blog');
Route::get('/post/{slug}', 'BlogController@blog')->name('blog');
Route::get('/category/{slug}', 'BlogController@category')->name('category');
Route::resource('blog', 		'BlogController');
Auth::routes();
Route::get('/posts', 'PostController@index')->name('posts');
Route::get('/post/{slug}', 'PostController@post')->name('post');
Route::get('/tag/{slug}', 'TagController@tag')->name('tag');

Route::resource('tags', 		'TagController');
Route::resource('categories', 	'CategoryController');
Route::resource('posts', 		'PostController');
Route::resource('blog', 		'BlogController');
