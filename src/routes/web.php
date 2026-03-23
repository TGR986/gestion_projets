<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\EtapeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin', function () {
    return 'Bienvenue dans la zone administrateur.';
})->middleware(['auth', 'admin'])->name('admin');

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PROJETS
    |--------------------------------------------------------------------------
    */
    Route::get('/projets', [ProjetController::class, 'index'])->name('projets.index');
    Route::get('/projets/create', [ProjetController::class, 'create'])->name('projets.create');
    Route::post('/projets', [ProjetController::class, 'store'])->name('projets.store');

    /*
    |--------------------------------------------------------------------------
    | ETAPES (AVANT {projet})
    |--------------------------------------------------------------------------
    */

    // Créer une étape / sous-étape
    Route::get('/projets/{projet}/etapes/create', [EtapeController::class, 'create'])
        ->name('projets.etapes.create');

    Route::post('/projets/{projet}/etapes', [EtapeController::class, 'store'])
        ->name('projets.etapes.store');

    // Modifier une étape
    Route::get('/projets/{projet}/etapes/{etape}/edit', [EtapeController::class, 'edit'])
        ->name('projets.etapes.edit');

    Route::put('/projets/{projet}/etapes/{etape}', [EtapeController::class, 'update'])
        ->name('projets.etapes.update');

    // Supprimer une étape
    Route::delete('/projets/{projet}/etapes/{etape}', [EtapeController::class, 'destroy'])
        ->name('projets.etapes.destroy');

    // Actions workflow
    Route::post('/projets/{projet}/etapes/{etape}/action', [EtapeController::class, 'changerStatut'])
        ->name('projets.etapes.action');

    // Voir une étape
    Route::get('/projets/{projet}/etapes/{etape}', [EtapeController::class, 'show'])
        ->name('projets.etapes.show');

    /*
    |--------------------------------------------------------------------------
    | PROJET (APRES ETAPES)
    |--------------------------------------------------------------------------
    */
    Route::get('/projets/{projet}', [ProjetController::class, 'show'])->name('projets.show');
    Route::get('/projets/{projet}/edit', [ProjetController::class, 'edit'])->name('projets.edit');
    Route::put('/projets/{projet}', [ProjetController::class, 'update'])->name('projets.update');
    Route::delete('/projets/{projet}', [ProjetController::class, 'destroy'])->name('projets.destroy');

    /*
    |--------------------------------------------------------------------------
    | PARTICIPANTS
    |--------------------------------------------------------------------------
    */
    Route::get('/projets/{projet}/participants/create', [ProjetController::class, 'createParticipant'])
        ->name('projets.participants.create');

    Route::post('/projets/{projet}/participants', [ProjetController::class, 'storeParticipant'])
        ->name('projets.participants.store');

    Route::delete('/projets/{projet}/participants/{participant}', [ProjetController::class, 'destroyParticipant'])
        ->name('projets.participants.destroy');

});