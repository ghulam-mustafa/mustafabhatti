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
Route::group(['middleware' => ['web']], function() { 
	// Authentication routes
	Route::get('auth/login', [ 'uses' => 'Auth\LoginController@showLoginForm', 'as' => 'login'] );
	Route::post('auth/login', 'Auth\LoginController@login');
	Route::get('auth/logout', ['uses' => 'Auth\LoginController@logout', 'as' => 'logout']);
	

	// Registration routes
	Route::get('auth/register', 'Auth\RegisterController@showRegistrationForm');
	Route::post('auth/register', 'Auth\RegisterController@register');

	// Password reset routes
	Route::get('password/reset/{token?}', 'Auth\ResetPasswordController@showResetForm');
	Route::post('password/email', 'Auth\ResetPasswordController@sendResetLinkEmail');
	Route::post('password/reset', 'Auth\ResetPasswordController@reset');

	// Categories
	Route::resource('categories', 'CategoryController', ['except' => ['create']]);
	Route::resource('tags', 'TagController', ['except' => ['create']]);

	// Comments
	Route::post('comments/{post_id}', ['uses' => 'CommentsController@store', 'as' => 'comments.store']);
	Route::get('comments/{id}/edit', ['uses' => 'CommentsController@edit', 'as' => 'comments.edit']);
	Route::put('comments/{id}', ['uses' => 'CommentsController@update', 'as' => 'comments.update']);	
	Route::delete('comments/{id}', ['uses' => 'CommentsController@destroy', 'as' => 'comments.destroy']);	
	Route::get('comments/{id}/delete', ['uses' => 'CommentsController@delete', 'as' => 'comments.delete']);

	Route::get('blog/{slug}', ['as'=>'blog.single', 'uses'=>'BlogController@getSingle'])->where('slug', '[\w\d\-\_]+');
	
	// Auth::routes(); if we use php artisan make:auth then it will generated. but we donw need it now as we make our own routes



	// create blog contoller in laravel $php aritisan make:controller BlogController
	// we can also pass another parameter like id as that of slug and pass that to public function getSingle in BlogController in same order. depending on our posts 
	Route::get('blog', ['uses' => 'BlogController@getIndex', 'as' => 'blog.index']);
	Route::get('contact', 'PagesController@getContact');
	Route::post('contact', 'PagesController@postContact');
	Route::get('about', ['uses' => 'PagesController@getAbout', 'as' => 'pages.about']);
	Route::get('/', 'PagesController@getIndex');
	Route::resource('posts', 'PostController');

});