<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MoviesController;
use App\Http\Controllers\ActorsController;
use App\Http\Controllers\TvController;
use App\Http\Controllers\CollectionsController;
use App\Http\Controllers\GeneratorMovieController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::middleware('auth')->group(function () {
    Route::get('/', [MoviesController::class, 'index'])->name('movies.index');
    Route::get('/movies/{id}', [MoviesController::class, 'show'])->name('movies.show');

    Route::get('/tv', [TvController::class, 'index'])->name('tv.index');
    Route::get('/tv/{id}', [TvController::class, 'show'])->name('tv.show');

    Route::get('/actors', [ActorsController::class, 'index'])->name('actors.index');
    Route::get('/actors/page/{page?}', [ActorsController::class, 'index']);
    Route::get('/actors/{id}', [ActorsController::class, 'show'])->name('actors.show');

    Route::get('/generator', [GeneratorMovieController::class, 'index'])->name('generator.index');
    Route::get('/generator/random', [GeneratorMovieController::class, 'random'])->name('generator.random');
    Route::get('/generator/genre', [GeneratorMovieController::class, 'genre'])->name('generator.genre');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::group(['prefix' => 'collections'], function() {
        Route::get('{id}', [CollectionsController::class, 'index'])->name('collections.index');
    
        Route::post('addMovie/{id}', [CollectionsController::class, 'storeMovie'])->name('collections.store');
        Route::post('addTvshow/{id}', [CollectionsController::class, 'storeTvShow'])->name('collections.TVstore');
        Route::post('addActor/{id}', [CollectionsController::class, 'storeActor'])->name('collections.Actorstore');
        
        Route::delete('delete/{id}', [CollectionsController::class, 'deleteActor'])->name('collections.Actordelete');
    
        Route::get('edit/{id}', [CollectionsController::class, 'edit'])->name('collections.edit');
        Route::put('update/{id}', [CollectionsController::class, 'update'])->name('collections.update');
    });
});

require __DIR__.'/auth.php';
