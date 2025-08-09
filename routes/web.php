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
});



// BVN Modification Routes
Route::middleware('auth')->group(function () {
Route::get('/bvnmod', [BVNmodController::class, 'index'])->name('bvnmod.index');
Route::get('/bvnmod/view/{id}', [BVNmodController::class, 'show'])->name('bvnmod.show');
Route::put('/bvnmod/view/{id}', [BVNmodController::class, 'update'])->name('bvnmod.update');
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



require __DIR__.'/auth.php';
