<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use App\Models\Actors_Collection;
use App\Models\Movies_Collection;
use App\Models\Tvshows_Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
// use Illuminate\Support\Facades\Validator;
use App\ViewModels\CollectionCinemaViewModel;

class CollectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Collection $id, $page = 1)
    {
        $collection = Auth::user()->id;
        
        if($collection != $id['user_id']) {
            abort(403);
        }

        $collection_id = Auth::user()->collection->id;
        
        $howManyMovies = count(Movies_Collection::Where('collection_id', $collection_id)->Where('status', 'Completed')->get());

        if($request->sortBy) {
            $sortBy = $request->sortBy;
        }
        else {
            $sortBy = 'status'; //Default SortBy
        }

        $userMovies = Movies_Collection::where('collection_id', $collection_id)->select(
            'collection_id', 'movie_id', 'name', 'img', 'genres', 'status', 'score', 'created_at'
        )->get()->sortByDesc($sortBy)->paginate(5);

        $userTvShows = Tvshows_Collection::where('collection_id', $collection_id)->select(
            'collection_id', 'tvshow_id', 'name', 'img', 'genres', 'number_of_episodes', 'watched_episodes', 'status', 'score', 'created_at'
        )->get()->sortByDesc($sortBy)->paginate(5);

        $userActors = Actors_Collection::where('collection_id', $collection_id)->select(
            'collection_id', 'actor_id', 'img'
        )->get();

        // $userMovies = collect();
        // $userTvShows = collect();
        // $userActors = collect();

        // foreach ($moviesCollection as $movieB)
        // {
        //     $movies = Http::withToken(config('services.tmdb.token'))
        //     ->get('https://api.themoviedb.org/3/movie/'.$movieB->movie_id)
        //     ->json();

        //     $userMovies->add(collect($movies)->put('score', $movieB->score)->put('status', $movieB->status)->put('release_date', $movieB->created_at));
        // }

        // foreach ($tvshowsCollection as $tvshowB)
        // {
        //     $tvShows = Http::withToken(config('services.tmdb.token'))
        //     ->get('https://api.themoviedb.org/3/tv/'.$tvshowB->tvshow_id.'?append_to_response=credits,videos,images')
        //     ->json();

        //     $userTvShows->add(collect($tvShows)->put('score', $tvshowB->score)->put('status', $tvshowB->status)->put('release_date', $tvshowB->created_at)->put('watched_episodes', $tvshowB->watched_episodes));
        // }

        // foreach ($actorsCollection as $actorsB)
        // {
        //     $actors = Http::withToken(config('services.tmdb.token'))
        //     ->get('https://api.themoviedb.org/3/person/'.$actorsB->actor_id)
        //     ->json();

        //     $userActors->add(collect($actors));
        // }

        $viewModel = new CollectionCinemaViewModel(
            $userMovies,
            $userTvShows,
            $userActors,
            $howManyMovies,
            $page,
            $sortBy,
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

        $movie = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/movie/'.$request->id)
        ->json();

        $genres = array_map(function ($item) {
            return $item['name'];
        }, $movie['genres']);

        $genres = implode(', ', $genres);

        $collection_id = Auth::user()->collection->id;
        
        $movieCollection = [
            'collection_id'     => $collection_id,
            'movie_id'          => $request->id,
            'name'              => $movie['original_title'],
            'img'               => $movie['poster_path'],
            'genres'            => $genres,
            'status'            => $request->status,
            'score'             => $request->score,
        ];

        Movies_Collection::create($movieCollection);

        toast('Movie has been Added!','success');

        return redirect()->route('collections.index', $collection_id);
    }

    public function storeTvShow(Request $request)
    {
        $request->validate([
            'score'                 => 'required',
            'status'                => 'required',
            // 'watched_episodes'      => 'required|int|min:0',
        ]);

        $collection_id = Auth::user()->collection->id;
        $tvShow = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/tv/'.$request->id)
        ->json();

        $genres = array_map(function ($item) {
            return $item['name'];
        }, $tvShow['genres']);

        $genres = implode(', ', $genres);

        $tvShowCollection = [
            'collection_id'     => $collection_id,
            'tvshow_id'         => $request->id,
            'name'              => $tvShow['name'],
            'img'               => $tvShow['poster_path'],
            'genres'            => $genres,
            'number_of_episodes'  => $tvShow['number_of_episodes'],
            'watched_episodes'  => $request->watched_episodes,
            'status'            => $request->status,
            'score'             => $request->score,
        ];

        if($request->status == "Completed") {
            $tvShowCollection = [
                'collection_id'     => $collection_id,
                'tvshow_id'         => $request->id,
                'name'              => $tvShow['name'],
                'img'               => $tvShow['poster_path'],
                'genres'            => $genres,
                'number_of_episodes'  => $tvShow['number_of_episodes'],
                'watched_episodes'  => $tvShow['number_of_episodes'],
                'status'            => 'Completed',
                'score'             => $request->score,
            ];
        }

        if($request->watched_episodes > $tvShow['number_of_episodes'])
        {
            $tvShowCollection = [
                'collection_id'     => $collection_id,
                'tvshow_id'         => $request->id,
                'name'              => $tvShow['name'],
                'img'               => $tvShow['poster_path'],
                'genres'            => $genres,
                'number_of_episodes'  => $tvShow['number_of_episodes'],
                'watched_episodes'  => $tvShow['number_of_episodes'],
                'status'            => 'Completed',
                'score'             => $request->score,
            ];
        }
        
        Tvshows_Collection::create($tvShowCollection);

        toast('Tv Show has been Added!','success');

        return redirect()->route('collections.index', $collection_id);
    }

    public function storeActor(Request $request)
    {
        $actor = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/person/'.$request->id)
        ->json();

        $collection_id = Auth::user()->collection->id;
        $actorCollection = [
            'collection_id' => $collection_id,
            'actor_id'      => $request->id,
            'img'           => $actor['profile_path'],
        ];

        Actors_Collection::create($actorCollection);

        toast('Actor has been Added!','success');

        return redirect()->route('collections.index', $collection_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteActor(int $id)
    {
        $collection_id = Auth::user()->collection->id;
        $actorsCollection = Actors_Collection::where('collection_id', $collection_id)
            ->where('actor_id', $id)->firstOrFail()
            ->delete();

        toast('Actor has been Deleted!','success'); // mie dziala idk czemu

        return redirect()->route('collections.index', $collection_id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $collection_id = Auth::user()->collection->id;
        if($request->input('watched_episodes')) 
        {
            $request->validate([
                'score'                 => 'required',
                'status'                => 'required',
                'watched_episodes'      => 'required|int|min:0',
            ]);

            // $validator = Validator::make($request->all(), [
            //         'score'                 => 'required',
            //         'status'                => 'required',
            //         'watched_episodes'      => 'required|int|min:0'
            // ]);

            // if($validator->fails())
            // {
            //     return back()->with('error', $validator->messages()->all()[0])->withInput();
            // }

            $tvShow = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/tv/'.$request->id)
            ->json();

            if($request->watched_episodes > $tvShow['number_of_episodes'])
            {
                $request->watched_episodes  = $tvShow['number_of_episodes'];
                $request->status            = 'Completed';
            }
            else {
                $request->watched_episodes  = $request->watched_episodes;
                $request->status            = $request->status;
            }

            Tvshows_Collection::Where('collection_id', $collection_id)
                ->Where('tvshow_id', $id)
                ->update([
                    'score'             =>  $request->score,
                    'status'            =>  $request->status,
                    'watched_episodes'  =>  $request->watched_episodes,
                    ]);
        }
        else 
        {
            $request->validate([
                'score'       => 'required',
                'status'      => 'required',
            ]);

            Movies_Collection::Where('collection_id', $collection_id)
                ->Where('movie_id', $id)
                ->update([
                        'score'     =>  $request->score,
                        'status'    =>  $request->status,
                        ]);
        }

        toast('Update Complete!','success');
        
        return redirect()->route('collections.index', $collection_id);
    }
}
