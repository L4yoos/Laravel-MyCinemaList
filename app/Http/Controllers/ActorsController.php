<?php

namespace App\Http\Controllers;

use App\ViewModels\ActorsViewModel;
use App\ViewModels\ActorViewModel;
use Illuminate\Support\Facades\Http;

class ActorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($page = 1)
    {
        abort_if($page > 500, 204);

        $popularActors = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/person/popular?page='.$page)
        ->json()['results']; 

        $viewModel = new ActorsViewModel($popularActors, $page);
        
        return view('actors.index', $viewModel);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $actor = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/person/'.$id)
        ->json();

        $exist = $actor['success'] ?? 1;

        if($exist == false) {
            abort(404);
        }
        // $actor = Http::withToken(config('services.tmdb.token'))
        // ->get('https://api.themoviedb.org/3/person/'.$id.'?append_to_response=external_ids,combined_credits')
        // ->json();

        // $viewModel = new ActorViewModel($actor);

        $social = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/person/'.$id.'/external_ids')
        ->json();

        $credits = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/person/'.$id.'/combined_credits')
        ->json();

        $viewModel = new ActorViewModel($actor, $social, $credits);

        return view('actors.show', $viewModel);
    }
}
