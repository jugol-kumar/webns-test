<?php

use Illuminate\Support\Facades\Route;
//Route::get('/info', function () {
//    return phpinfo();
//});
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');


