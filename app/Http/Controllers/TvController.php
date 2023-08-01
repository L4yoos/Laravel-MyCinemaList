<?php

namespace App\Http\Controllers;

use App\ViewModels\TvViewModel;
use App\ViewModels\TvShowViewModel;
use Illuminate\Support\Facades\Http;

class TvController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $popularTv = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/tv/popular')
        ->json()['results']; 

        $topRatedTv = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/tv/top_rated')
            ->json()['results'];

        $genres = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/genre/tv/list')
            ->json()['genres'];

        $viewModel = new TvViewModel(
            $popularTv,
            $topRatedTv,
            $genres,
        );

        return view('tv.index', $viewModel);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tvshow = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/tv/'.$id.'?append_to_response=credits,videos,images,watch/providers')
        ->json();
    
        $exist = $tvshow['success'] ?? 1;

        if($exist == false) {
            abort(404);
        }
        
        $viewModel = new TvShowViewModel($tvshow);

        return view('tv.show', $viewModel);
    }
}
