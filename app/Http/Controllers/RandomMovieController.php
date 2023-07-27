<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\ViewModels\MovieViewModel;

class RandomMovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = rand(1,500);
        $randomMovie = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/movie/popular?page='.$page)
        ->json()['results'];

        $randomMovie = Arr::random($randomMovie);

        $movie = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/movie/'.$randomMovie['id'].'?append_to_response=credits,videos,images,watch/providers')
        ->json();
        
        $viewModel = new MovieViewModel($movie);

        return view('random.index', $viewModel);
    }
}
