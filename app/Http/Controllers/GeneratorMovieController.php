<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\ViewModels\MovieViewModel;

class GeneratorMovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('generator.index');
    }

    public function random()
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

        return view('generator.random', $viewModel);
    }

    public function genre(Request $request)
    {
        $page = rand(1,500);
        $randomMovie = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/movie/popular?page='.$page)
        ->json()['results'];

        $search = $request->genre;
        $results = array_filter($randomMovie, function($movie) use ($search) {
            return in_array($search, $movie['genre_ids']);
        });
        
        if(empty($results)) {
            $page = rand(1,500);
            $randomMovie = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/popular?page='.$page)
            ->json()['results'];

            $search = $request->genre;
            $results = array_filter($randomMovie, function($movie) use ($search) {
                return in_array($search, $movie['genre_ids']);
            });

            if(empty($results)) {
                alert()->info('Info', 'We are looking for a film like this, try again!');

                return redirect()->route('generator.index');
            }

            $randomMovie = Arr::random($results);
        }
        else {
            $randomMovie = Arr::random($results);
        }

        $movie = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/movie/'.$randomMovie['id'].'?append_to_response=credits,videos,images,watch/providers')
        ->json();

        $viewModel = new MovieViewModel($movie);

        return view('generator.random', $viewModel);
    }
}
