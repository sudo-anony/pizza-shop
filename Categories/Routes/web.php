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

Route::prefix('categories')->group(function() {
    Route::get('/', 'CategoriesController@index');
});


Route::group([
    'middleware' =>[ 'web','impersonate'],
    'namespace' => 'Modules\Categories\Http\Controllers'
], function () {
    Route::prefix('systemcategories')->group(function() { 
            Route::post('set/{item}', 'Main@set')->name('systemcategories.set');
    });
});