<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\EtapeController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Admin\UserController;

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

    Route::post('/projets/{projet}/commentaires', [ProjetController::class, 'storeCommentaireProjet'])
    ->name('projets.commentaires.store');

    Route::get('/projets/{projet}/commentaires/{commentaire}/edit', [ProjetController::class, 'editCommentaireProjet'])
    ->name('projets.commentaires.edit');

    Route::put('/projets/{projet}/commentaires/{commentaire}', [ProjetController::class, 'updateCommentaireProjet'])
    ->name('projets.commentaires.update');

    Route::delete('/projets/{projet}/commentaires/{commentaire}', [ProjetController::class, 'destroyCommentaireProjet'])
    ->name('projets.commentaires.destroy');
    
    Route::post('/projets/{projet}/etapes/{etape}/commentaires', [EtapeController::class, 'storeCommentaire'])
    ->name('etapes.commentaires.store');

    Route::get('/projets/{projet}/etapes/{etape}/commentaires/{commentaire}/edit', [EtapeController::class, 'editCommentaire'])
    ->name('etapes.commentaires.edit');

    Route::put('/projets/{projet}/etapes/{etape}/commentaires/{commentaire}', [EtapeController::class, 'updateCommentaire'])
    ->name('etapes.commentaires.update');

    Route::delete('/projets/{projet}/etapes/{etape}/commentaires/{commentaire}', [EtapeController::class, 'destroyCommentaire'])
    ->name('etapes.commentaires.destroy');
    
    /*
    |--------------------------------------------------------------------------
    | DOCUMENTS
    |--------------------------------------------------------------------------
    */
    Route::get('/documents/{version}/download', [DocumentController::class, 'download'])
        ->name('documents.download');

    Route::get('/projets/{projet}/etapes/{etape}/documents/create', [DocumentController::class, 'create'])
    ->name('documents.create');

    Route::post('/projets/{projet}/etapes/{etape}/documents', [DocumentController::class, 'store'])
    ->name('documents.store');

    Route::get('/documents/{document}/versions/create', [DocumentController::class, 'createVersion'])
    ->name('documents.versions.create');

    Route::post('/documents/{document}/versions', [DocumentController::class, 'storeVersion'])
    ->name('documents.versions.store');

    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])
    ->name('documents.destroy');

    Route::post('/document-versions/{version}/validation', [DocumentController::class, 'validerVersion'])
    ->name('documents.versions.validation');

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

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | UTILISATEURS
    |--------------------------------------------------------------------------
    */

    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');

    Route::get('/users/create', [UserController::class, 'create'])
        ->name('users.create');

    Route::post('/users', [UserController::class, 'store'])
        ->name('users.store');

    Route::get('/users/{user}/edit', [UserController::class, 'edit'])
        ->name('users.edit');

    Route::put('/users/{user}', [UserController::class, 'update'])
        ->name('users.update');

    Route::patch('/users/{user}/deactivate', [UserController::class, 'deactivate'])
    ->name('users.deactivate');

    Route::patch('/users/{user}/reactivate', [UserController::class, 'reactivate'])
    ->name('users.reactivate');

});