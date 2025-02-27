<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;
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

Route::get('/messages', [MessageController::class, 'index'])->name('messages');
Route::post('/send-message', [MessageController::class, 'send_message']);

Route::get('create-device',[DeviceController::class, 'create_device'])->name('devices');
Route::post('store-device',[DeviceController::class, 'store_device']);

Route::get('qr-code/{deviceId}', [DeviceController::class, 'qrCode'])->name('qrCode');
Route::get('link-device/{deviceId}', [DeviceController::class, 'link_device']);
Route::get('generate-qr-code', [DeviceController::class, 'generate_qr_code']);


require __DIR__.'/auth.php';
