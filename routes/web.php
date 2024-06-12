<?php

use App\Http\Controllers\ClubClassController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\TournamentController;
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

//Rutas para pistas
Route::prefix('courts')->group(function () {
    Route::post('/{clubId}/store', [CourtController::class, 'store'])->middleware(['auth', 'role:club'])->name('courts.store');
    Route::post('/{club}/courts/{court}/rent', [CourtController::class, 'createRent'])->middleware(['auth', 'role:user'])->name('courts.rent.create');
    Route::put('/{club}/courts/{court}', [CourtController::class, 'update'])->middleware(['auth', 'role:club'])->name('courts.update');
    Route::delete('/{club}/courts/{court}', [CourtController::class, 'destroy'])->middleware(['auth', 'role:club'])->name('courts.destroy');
    Route::put('/{club}/rents/{courtRental}', [CourtController::class, 'updateRent'])->middleware(['auth', 'role:user'])->name('rents.update');
    Route::delete('/{club}/rents/{courtRental}', [CourtController::class, 'deleteRent'])->middleware(['auth', 'role:user'])->name('rents.delete');
});

//Rutas para clases
Route::group(['prefix' => 'club-profile/{clubProfile}'], function () {
    Route::post('classes', [ClubClassController::class, 'store'])->middleware(['auth', 'role:club'])->name('classes.store');
    Route::post('classes/{class}/register', [ClubClassController::class, 'createRegistration'])->middleware(['auth', 'role:user'])->name('classes.register');
    Route::put('classes/{class}', [ClubClassController::class, 'update'])->middleware(['auth', 'role:club'])->name('classes.update');
    Route::delete('classes/{class}', [ClubClassController::class, 'destroy'])->middleware(['auth', 'role:club'])->name('classes.destroy');
    Route::delete('classes/{class}/registration/{registration}', [ClubClassController::class, 'destroyRegistration'])->middleware(['auth', 'role:user'])->name('classes.destroyRegistration');
});

//Rutas para torneos
Route::prefix('tournaments')->group(function () {
    Route::post('/{clubId}/store', [TournamentController::class, 'store'])->middleware(['auth', 'role:club'])->name('tournaments.store');
    Route::put('/{club}/tournament/{tournament}', [TournamentController::class, 'update'])->middleware(['auth', 'role:club'])->name('tournaments.update');
    Route::delete('/{club}/tournament/{tournament}', [TournamentController::class, 'destroy'])->middleware(['auth', 'role:club'])->name('tournaments.destroy');
    Route::post('/{club}/tournament/{tournament}/register', [TournamentController::class, 'registrar'])->middleware(['auth', 'role:user'])->name('tournaments.register');
    Route::get('/{tournament}/generate-matches', [TournamentController::class, 'matchGenerator'])->middleware(['auth', 'role:club'])->name('tournaments.generate.matches');
    Route::delete('/{tournament}/registration/{tournamentRegistration}', [TournamentController::class, 'destroyRegister'])->middleware(['auth', 'role:user'])->name('tournaments.destroy.register');
});

//Ruta de users
Route::resource('users', UserController::class)->parameters([
    'users' => 'user'
]);
