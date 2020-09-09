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

Route::get('/', function () {
    return view('welcome');
});

// We can preview the e-mail uncommenting 
// the code below
// Route::get('preview-mail', function() {
//     $user = factory(\App\User::class)->create();
//     return (new \App\Notifications\ImportantStockUpdate(\App\Stock::first()))->toMail($user);
// });
