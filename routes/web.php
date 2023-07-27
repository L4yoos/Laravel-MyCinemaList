<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MoviesController;
use App\Http\Controllers\ActorsController;
use App\Http\Controllers\TvController;
use App\Http\Controllers\CollectionsController;
use App\Http\Controllers\RandomMovieController;
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

    Route::get('/random', [RandomMovieController::class, 'index'])->name('random.index');

    Route::get('/collections/{user_id}', [CollectionsController::class, 'index'])->name('collections.index');
    Route::get('/collections/{user_id}/page/{page?}', [CollectionsController::class, 'index']);
    Route::get('/collections/{slug}', [CollectionsController::class, 'switchSortBy'])->name('collections.switchSortBy');

    Route::post('/collections/addMovie/{id}', [CollectionsController::class, 'storeMovie'])->name('collections.store');
    Route::post('/collections/addTvshow/{id}', [CollectionsController::class, 'storeTvShow'])->name('collections.TVstore');

    Route::post('/collections/addActor/{id}', [CollectionsController::class, 'storeActor'])->name('collections.Actorstore');
    Route::delete('/collections/delete/{id}', [CollectionsController::class, 'deleteActor'])->name('collections.Actordelete');

    Route::get('/collections/edit/{id}', [CollectionsController::class, 'edit'])->name('collections.edit');
    Route::put('/collections/update/{id}', [CollectionsController::class, 'update'])->name('collections.update');// Ogarnij roznice miedzy edit a update
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
