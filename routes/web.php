<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;

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

// MESSAGES

Route::get('/messages', [MessageController::class, 'index'])->name('messages');
Route::post('/send-message', [MessageController::class, 'send_message']);

// DEVICES

Route::get('create-device',[DeviceController::class, 'index'])->name('devices');
Route::post('store-device',[DeviceController::class, 'store'])->name('store.device');
Route::get('list-device',[DeviceController::class, 'list'])->name('device.list');
Route::get('edit-device/{device}',[DeviceController::class, 'edit'])->name('edit.device');
Route::put('update-device/{device}',[DeviceController::class, 'update'])->name('update.device');
Route::delete('delete-device/{device}',[DeviceController::class, 'delete'])->name('delete.device');
Route::get('/check-status/{id}', [DeviceController::class, 'checkStatus'])->name('check.status');


// QR CODE
Route::get('qr-code/{device}', [QrCodeController::class, 'scan'])->name('qr.code');


require __DIR__.'/auth.php';
