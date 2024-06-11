<?php

use App\Http\Controllers\ClubClassController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClubController;
use Illuminate\Support\Facades\Route;

Route::get('/', InicioController::class)->name('inicio');

// Rutas para el ClubController
Route::prefix('clubs')->group(function () {
    Route::get('/', [ClubController::class, 'index'])->name('clubs.index');
    Route::post('/', [ClubController::class, 'store'])->name('clubs.store');
    Route::get('/{club}', [ClubController::class, 'show'])->name('clubs.show');
    Route::get('/{club}/edit', [ClubController::class, 'edit'])->middleware(['auth', 'role:club'])->name('clubs.edit');
    Route::put('/{club}', [ClubController::class, 'update'])->middleware(['auth', 'role:club'])->name('clubs.update');
    Route::delete('/{club}', [ClubController::class, 'delete'])->middleware(['auth', 'role:administrador'])->name('clubs.delete');
    Route::post('/{clubProfile}/cabecera', [ClubController::class, 'cabecera'])->middleware(['auth', 'role:club'])->name('clubs.cabecera');
    Route::post('/{clubProfile}/edit-details', [ClubController::class, 'updateDetails'])->middleware(['auth', 'role:club'])->name('clubs.updateDetails');
});

// Ruta para obtener los datos de las clases
Route::get('/api/clubs', [ClubController::class, 'datosClubs'])->name('clubs.datosClubs');

Route::group(['prefix' => 'club-profile/{clubProfile}'], function () {
    Route::post('classes', [ClubClassController::class, 'store'])->middleware(['auth', 'role:club'])->name('classes.store');
    Route::post('classes/{class}/register', [ClubClassController::class, 'createRegistration'])->middleware(['auth', 'role:user'])->name('classes.register');
    Route::put('classes/{class}', [ClubClassController::class, 'update'])->middleware(['auth', 'role:club'])->name('classes.update');
    Route::delete('classes/{class}', [ClubClassController::class, 'destroy'])->middleware(['auth', 'role:club'])->name('classes.destroy');
    Route::delete('classes/{class}/registration/{registration}', [ClubClassController::class, 'destroyRegistration'])->middleware(['auth', 'role:user'])->name('classes.destroyRegistration');
});

//Ruta de users
Route::resource('users', UserController::class)->parameters([
    'users' => 'user'
]);
