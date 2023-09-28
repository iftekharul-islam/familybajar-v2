<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaterkitController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WithdrawController;

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



Route::get('login', [AuthenticationController::class, 'login'])->name('login');
Route::post('login', [AuthenticationController::class, 'loginConfirm'])->name('loginConfirm');
Route::post('logout', [AuthenticationController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [StaterkitController::class, 'home'])->name('home');
    Route::get('home', [StaterkitController::class, 'homeNew'])->name('home');
    Route::get('profile', [UserController::class, 'profile'])->name('profile.show');

    // Users
    Route::get('users', [UserController::class, 'index'])->name('users');
    Route::get('users/show/{id}', [UserController::class, 'show'])->name('user.show');
    Route::get('user-add', [UserController::class, 'userAdd'])->name('userAdd');
    Route::post('user-add', [UserController::class, 'userAddButton'])->name('userAddButton');

    // Orders
    Route::get('orders', [OrderController::class, 'index'])->name('orders');
    Route::get('order/{id}', [OrderController::class, 'orderShow'])->name('orderShow');
    Route::get('order-add', [OrderController::class, 'orderAdd'])->name('orderAdd');
    Route::post('order-add', [OrderController::class, 'orderAddButton'])->name('orderAddButton');

    // Withdraws
    Route::get('withdraws', [WithdrawController::class, 'withdraws'])->name('withdraws');
    Route::get('withdraw-add', [WithdrawController::class, 'withdrawAdd'])->name('withdrawAdd');
    Route::post('withdraw-add', [WithdrawController::class, 'withdrawAddButton'])->name('withdrawAddButton');
    Route::get('withdraw-cancel/{id}', [WithdrawController::class, 'withdrawCancelButton'])->name('withdrawCancelButton');

    // Withdraw Requests
    Route::get('withdraw-requests', [WithdrawController::class, 'withdrawRequests'])->name('withdrawRequests');
    Route::get('withdraw-request-edit/{id}', [WithdrawController::class, 'withdrawRequestEdit'])->name('withdrawRequestEdit');
    Route::post('withdraw-request-edit', [WithdrawController::class, 'withdrawRequestEditButton'])->name('withdrawRequestEditButton');

    // Repurchase History
    Route::get('repurchase-history', [OrderController::class, 'repurchaseHistory'])->name('repurchase-history');

    // Settings
    Route::get('settings/global', [SettingsController::class, 'global'])->name('global');
    Route::post('settings/global', [SettingsController::class, 'updateGlobal'])->name('updateGlobal');

    Route::get('settings/manual', [SettingsController::class, 'manual'])->name('manual');
    Route::get('settings/manual-add', [SettingsController::class, 'manualAdd'])->name('manualAdd');
    Route::post('settings/manual-add', [SettingsController::class, 'createManual'])->name('createManual');

    Route::post('settings/manual', [SettingsController::class, 'updateManual'])->name('updateManual');

    Route::get('login-as-user/{id}', [AuthenticationController::class, 'loginAsUser'])->name('loginAsUser');
});
// Route Components
Route::get('layouts/collapsed-menu', [StaterkitController::class, 'collapsed_menu'])->name('collapsed-menu');
Route::get('layouts/full', [StaterkitController::class, 'layout_full'])->name('layout-full');
Route::get('layouts/without-menu', [StaterkitController::class, 'without_menu'])->name('without-menu');
Route::get('layouts/empty', [StaterkitController::class, 'layout_empty'])->name('layout-empty');
Route::get('layouts/blank', [StaterkitController::class, 'layout_blank'])->name('layout-blank');


// locale Route
Route::get('lang/{locale}', [LanguageController::class, 'swap']);
