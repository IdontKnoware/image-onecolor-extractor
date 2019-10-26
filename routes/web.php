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

# Entrance app route
Route::get( '/', 'ImageColorExtractorController@index' );

# Submits form to server via AJAX. See 'index.blade.php' for detail
Route::post( '/action', 'ImageColorExtractorController@extractImgColor' )->name( 'extractimgcolor.action' )
                                                                         ->middleware( 'checkimgcolordir' );

# Fallback
Route::fallback( function() {
    return redirect( '/' );
});
