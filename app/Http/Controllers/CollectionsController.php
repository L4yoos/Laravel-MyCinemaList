<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collection;
use App\Models\Movies_Collection;
use App\Models\Tvshows_Collection;
use App\ViewModels\CollectionMoviesViewModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class CollectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Collection $user_id)
    {
        $collection = Auth::user()->id;
        $collection_id = Collection::Where('User_id', Auth::id())->value('id');

        if($collection != $user_id['user_id']) {
            abort(403);
        }

        $moviesCollection = Movies_Collection::where('collection_id', $collection_id)->select(
            'collection_id', 'movie_id', 'status', 'score', 'created_at'
        )->get();

        $tvshowsCollection = Tvshows_Collection::where('collection_id', $collection_id)->select(
            'collection_id', 'tvshow_id', 'watched_episodes', 'status', 'score', 'created_at'
        )->get();

        $userMovies = collect();
        $userTvShows = collect();

        foreach ($moviesCollection as $movieB)
        {
            $movies = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/'.$movieB->movie_id.'?append_to_response=credits,videos,images')
            ->json();

            $userMovies->add(collect($movies)->put('score', $movieB->score)->put('status', $movieB->status)->put('release_date', $movieB->created_at));
        }

        foreach ($tvshowsCollection as $tvshowB)
        {
            $tvShows = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/tv/'.$tvshowB->tvshow_id.'?append_to_response=credits,videos,images')
            ->json();

            $userTvShows->add(collect($tvShows)->put('score', $tvshowB->score)->put('status', $tvshowB->status)->put('release_date', $tvshowB->created_at)->put('watched_episodes', $tvshowB->watched_episodes));
        }

        $viewModel = new CollectionMoviesViewModel(
            $userMovies,
            $userTvShows,
        );

        return view('collection', $viewModel);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeMovie(Request $request)
    {
        $validated = $request->validate([
            'score'       => 'required',
            'status'      => 'required',
        ]);

        $Movie_From_Collection = new Movies_Collection();
        $Movie_From_Collection->collection_id = Collection::Where('User_id', Auth::id())->value('id');
        $Movie_From_Collection->movie_id = $request->id;
        $Movie_From_Collection->status = $request->input('status');
        $Movie_From_Collection->score = $request->input('score');
        $Movie_From_Collection->save();

        return redirect()->route('collections.index', Auth::id());
    }

    public function storeTvShow(Request $request)
    {
        $validated = $request->validate([
            'score'                 => 'required',
            'status'                => 'required',
            'watched_episodes'    => 'required|int',
        ]);

        $tvShow = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/tv/'.$request->id)
        ->json();

        $TvShow_From_Collection = new Tvshows_Collection();
        $TvShow_From_Collection->collection_id = Collection::Where('User_id', Auth::id())->value('id');
        $TvShow_From_Collection->tvshow_id = $request->id;

        if($request->watched_episodes > $tvShow['number_of_episodes'])
        {
            $TvShow_From_Collection->watched_episodes = $tvShow['number_of_episodes'];
            $TvShow_From_Collection->status = 'Completed';
        }
        else {
            $TvShow_From_Collection->watched_episodes = $request->watched_episodes;
            $TvShow_From_Collection->status = $request->input('status');
        }

        if($request->watched_episodes < 0)
        {
            $TvShow_From_Collection->watched_episodes = 0;
            $TvShow_From_Collection->status = 'Watching';
        }


        $TvShow_From_Collection->score = $request->input('score');
        $TvShow_From_Collection->save();

        return redirect()->route('collections.index', Auth::id());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        if($request->input('watched_episodes')) {
            $validated = $request->validate([
                'score'                 => 'required',
                'status'                => 'required',
                'watched_episodes'    => 'required|int',
            ]);

            $tvShow = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/tv/'.$request->id)
            ->json();

            if($request->watched_episodes > $tvShow['number_of_episodes'])
            {
                $request->watched_episodes = $tvShow['number_of_episodes'];
                $request->status = 'Completed';
            }
            else {
                $request->watched_episodes = $request->watched_episodes;
                $request->status = $request->input('status');
            }
    
            if($request->watched_episodes < 0)
            {
                $request->watched_episodes = 0;
                $request->status = 'Watching';
            }

            $tvshow = Tvshows_Collection::Where('tvshow_id', $id)
            ->update([
                    'score' =>$request->score,
                    'status'=>$request->status,
                    'watched_episodes'=>$request->watched_episodes,
                    ]);
        }
        else {
        $validated = $request->validate([
            'score'       => 'required',
            'status'      => 'required',
        ]);

        $movie = Movies_Collection::Where('movie_id', $id)
            ->update([
                    'score' => $request->input('score'),
                    'status'=>$request->input('status'),
                    ]);
        }

        return redirect()->route('collections.index', Auth::id());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
