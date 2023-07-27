<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use App\Models\Actors_Collection;
use App\Models\Movies_Collection;
use App\Models\Tvshows_Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\ViewModels\CollectionCinemaViewModel;


class CollectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Collection $user_id, $page = 1)
    {
        $collection = Auth::user()->id;
        $collection_id = Collection::Where('User_id', Auth::id())->value('id');

        $howManyMovies = Movies_Collection::Where('collection_id', $collection_id)->get();
        $howManyMovies = count($howManyMovies->Where('status', 'Completed'));

        if($request->sortBy) {
            $sortBy = $request->sortBy;
        }
        else {
            $sortBy = 'status'; //Default SortBy
        }

        if($collection != $user_id['user_id']) {
            abort(403);
        }

        $moviesCollection = Movies_Collection::where('collection_id', $collection_id)->select(
            'collection_id', 'movie_id', 'status', 'score', 'created_at'
        )->get()->sortByDesc($sortBy)->paginate(10);

        $tvshowsCollection = Tvshows_Collection::where('collection_id', $collection_id)->select(
            'collection_id', 'tvshow_id', 'watched_episodes', 'status', 'score', 'created_at'
        )->get()->sortByDesc($sortBy)->paginate(10);

        $actorsCollection = Actors_Collection::where('collection_id', $collection_id)->select(
            'collection_id', 'actor_id',
        )->get();

        $userMovies = collect();
        $userTvShows = collect();
        $userActors = collect();

        // dump($moviesCollection);

        foreach ($moviesCollection as $movieB)
        {
            $movies = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/'.$movieB->movie_id)
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

        foreach ($actorsCollection as $actorsB)
        {
            $actors = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/person/'.$actorsB->actor_id)
            ->json();

            $userActors->add(collect($actors));
        }

        $viewModel = new CollectionCinemaViewModel(
            $userMovies,
            $userTvShows,
            $userActors,
            $howManyMovies,
            $page,
        );

        return view('collection', $viewModel);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeMovie(Request $request)
    {
        $request->validate([
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
        $request->validate([
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

    public function storeActor(Request $request)
    {
        $Actor_From_Collection = new Actors_Collection();
        $Actor_From_Collection->collection_id = Collection::Where('User_id', Auth::id())->value('id');
        $Actor_From_Collection->actor_id = $request->id;
        $Actor_From_Collection->save();

        return redirect()->route('collections.index', Auth::id());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteActor(string $id)
    {
        $collection_id = Collection::Where('User_id', Auth::id())->value('id');
        $actorsCollection = Actors_Collection::where('collection_id', $collection_id)
            ->where('actor_id', $id)->firstOrFail();

        $actorsCollection->delete();
        return redirect()->route('collections.index', Auth::id());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        if($request->input('watched_episodes')) {
            $request->validate([
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

            Tvshows_Collection::Where('tvshow_id', $id)
            ->update([
                    'score' =>$request->score,
                    'status'=>$request->status,
                    'watched_episodes'=>$request->watched_episodes,
                    ]);
        }
        else {
            $request->validate([
                'score'       => 'required',
                'status'      => 'required',
            ]);

            Movies_Collection::Where('movie_id', $id)
                ->update([
                        'score' => $request->input('score'),
                        'status'=>$request->input('status'),
                        ]);
        }

        return redirect()->route('collections.index', Auth::id());
    }
}
