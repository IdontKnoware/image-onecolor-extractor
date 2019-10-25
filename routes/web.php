<?php

use Illuminate\Http\Request;
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

# Entrance app
Route::get('/', 'ImageColorExtractorController@index');

# Operations
//Route::resource( 'images', 'ImageColorExtractorController' );

# Submit form to server via AJAX
Route::post('/action', 'ImageColorExtractorController@extractImgColor')->name('extractimgcolor.action');

# Fallback
Route::fallback( function() {
    return redirect('/');
});
