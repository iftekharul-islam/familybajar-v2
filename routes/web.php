<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaterkitController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;

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

Route::get('/', [AuthController::class, 'login'])->name('login');

//Auth Route Start
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'checkLogin'])->name('checkLogin');

Route::get('registration', [AuthController::class, 'registration'])->name('registration');
Route::post('registration', [AuthController::class, 'checkRegistration'])->name('checkRegistration');

Route::get('forget-password', [AuthController::class, 'forgetPassword'])->name('forgetPassword');
Route::post('forget-password', [AuthController::class, 'checkForgetPassword'])->name('checkForgetPassword');
Route::get('reset-password', [AuthController::class, 'resetPassword'])->name('resetPassword');
Route::post('reset-password', [AuthController::class, 'checkResetPassword'])->name('checkResetPassword');
//Auth Route End

//Profile Route Start
Route::get('profile', [ProfileController::class, 'profile'])->name('profile');


Route::get('home', [StaterkitController::class, 'home'])->name('home');
// Route Components
Route::get('layouts/collapsed-menu', [StaterkitController::class, 'collapsed_menu'])->name('collapsed-menu');
Route::get('layouts/full', [StaterkitController::class, 'layout_full'])->name('layout-full');
Route::get('layouts/without-menu', [StaterkitController::class, 'without_menu'])->name('without-menu');
Route::get('layouts/empty', [StaterkitController::class, 'layout_empty'])->name('layout-empty');
Route::get('layouts/blank', [StaterkitController::class, 'layout_blank'])->name('layout-blank');


// locale Route
Route::get('lang/{locale}', [LanguageController::class, 'swap']);
