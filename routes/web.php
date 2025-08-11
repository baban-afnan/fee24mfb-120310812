<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BVNmodController;
use App\Http\Controllers\CRMController;
use App\Http\Controllers\ManualSearchController;
use App\Http\Controllers\EnrolmentUserController;
use App\Http\Controllers\SendVninToNibssController;
use App\Http\Controllers\ManageUsersController;
use App\Http\Controllers\WalletFundingController;
use App\Http\Controllers\GeneralWalletFundingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationAdd;
use App\Http\Controllers\ValidationController;
use App\Http\Controllers\IpeController;
use App\Http\Controllers\NINmodController;








Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



// Service Routes
Route::middleware('auth')->group(function () {
Route::get('/nin-services', [ServiceController::class, 'nin'])->name('services.nin');
Route::get('/bvn-services', [ServiceController::class, 'bvn'])->name('services.bvn');
Route::get('/verification', [ServiceController::class, 'verification'])->name('services.verification');
Route::get('/vip-services', [ServiceController::class, 'vip'])->name('services.vip');
Route::get('/management-services', [ServiceController::class, 'management'])->name('services.management'); 
Route::get('/wallet-services', [ServiceController::class, 'wallet'])->name('services.wallet');   
});



// BVN Modification Routes
Route::middleware('auth')->group(function () {
Route::get('/bvnmod', [BVNmodController::class, 'index'])->name('bvnmod.index');
Route::get('/bvnmod/view/{id}', [BVNmodController::class, 'show'])->name('bvnmod.show');
Route::put('/bvnmod/view/{id}', [BVNmodController::class, 'update'])->name('bvnmod.update');
});


// BVN Modification Routes
Route::middleware('auth')->group(function () {
Route::get('/ninmod', [NINmodController::class, 'index'])->name('ninmod.index');
Route::get('/ninmod/view/{id}', [NINmodController::class, 'show'])->name('ninmod.show');
Route::put('/ninmod/view/{id}', [NINmodController::class, 'update'])->name('ninmod.update');
});



// BVN CRM Routes
Route::middleware('auth')->group(function () {
Route::get('/crmreg', [CRMController::class, 'index'])->name('crmreg.index');
Route::get('/crmreg/view/{id}', [CRMController::class, 'show'])->name('crmreg.show');
Route::put('/crmreg/view/{id}', [CRMController::class, 'update'])->name('crmreg.update');
});


// BVN Serach Using Phone Number Routes
Route::middleware('auth')->group(function () {
Route::get('/bvnsearch', [ManualSearchController::class, 'index'])->name('bvnsearch.index');
Route::get('/bvnsearch/view/{id}', [ManualSearchController::class, 'show'])->name('bvnsearch.show');
Route::put('/bvnsearch/view/{id}', [ManualSearchController::class, 'update'])->name('bvnsearch.update');
});

// BVN USER REQ Routes
Route::middleware('auth')->group(function () {
Route::get('/bvnuser', [EnrolmentUserController::class, 'index'])->name('bvnuser.index');
Route::get('/bvnuser/view/{id}', [EnrolmentUserController::class, 'show'])->name('bvnuser.show');
Route::put('/bvnuser/view/{id}', [EnrolmentUserController::class, 'update'])->name('bvnuser.update');
});


// send vnin to nibss route
Route::middleware('auth')->group(function () {
Route::get('/send-vnin', [SendVninToNibssController::class, 'index'])->name('sendvnin.index');
Route::get('/send-vnin/view/{id}', [SendVninToNibssController::class, 'show'])->name('sendvnin.show');
Route::put('/send-vnin/view/{id}', [SendVninToNibssController::class, 'update'])->name('sendvnin.update');
});


// User management routes
Route::middleware('auth')->group(function () {
Route::get('/users', [ManageUsersController::class, 'index'])->name('users.index');
Route::get('/users/view/{id}', [ManageUsersController::class, 'show'])->name('users.show');
Route::put('/users/view/{id}', [ManageUsersController::class, 'update'])->name('users.update');
});


// Manual wallet funding
Route::middleware('auth')->group(function () {
Route::get('/manual-funding', [WalletFundingController::class, 'showForm'])->name('manual.funding.form');
Route::post('/manual-funding', [WalletFundingController::class, 'process'])->name('manual.funding');
});


Route::middleware('auth')->group(function () {
Route::get('/general-funding', [GeneralWalletFundingController::class, 'showForm'])->name('general.funding.form');
Route::post('/general-funding', [GeneralWalletFundingController::class, 'process'])->name('general.funding');
Route::post('/general-funding/preview', [GeneralWalletFundingController::class, 'preview'])->name('general-funding.preview');
Route::post('/general-funding/queue', [GeneralWalletFundingController::class, 'queue'])->name('general-funding.queue');
});



// NOTIFICATIONS UPDATES
Route::middleware('auth')->group(function () {
Route::get('/notification', [NotificationController::class, 'index'])->name('notification.index');
Route::get('/notification/view/{id}', [NotificationController::class, 'show'])->name('notification.show');
Route::put('/notification/view/{id}', [NotificationController::class, 'update'])->name('notification.update');
Route::post('/notifications', [NotificationAdd::class, 'store'])->name('notification.store');
});


// BVN Serach Using Phone Number Routes
Route::middleware('auth')->group(function () {
Route::get('/validation', [ValidationController::class, 'index'])->name('validation.index');
Route::get('/validation/view/{id}', [ValidationController::class, 'show'])->name('validation.show');
Route::put('/validation/view/{id}', [ValidationController::class, 'update'])->name('validation.update');
});


// BVN Serach Using Phone Number Routes
Route::middleware('auth')->group(function () {
Route::get('/ipe', [IpeController::class, 'index'])->name('ipe.index');
Route::get('/ipe/view/{id}', [IpeController::class, 'show'])->name('ipe.show');
Route::put('/ipe/view/{id}', [IpeController::class, 'update'])->name('ipe.update');
});


require __DIR__.'/auth.php';
