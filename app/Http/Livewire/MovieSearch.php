<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Models\Movies_Collection;
use Carbon\Carbon;

class MovieSearch extends Component
{
    public $search = '';

    public function render()
    {
        $userMovies = collect();
        if (strlen($this->search) >= 2) {
            $searchResults = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/search/movie?query='.$this->search)
            ->json()['results'];

            $movieIdsString = array_map(function ($movie) {
                return $movie['id'];
            }, $searchResults);

            // dd($searchResults);

            $moviesCollection = Movies_Collection::whereIn('movie_id', $movieIdsString)->get();

            // dump($moviesCollection);

            foreach ($moviesCollection as $movieB)
            {
                $movies = Http::withToken(config('services.tmdb.token'))
                ->get('https://api.themoviedb.org/3/movie/'.$movieB->movie_id)
                ->json();

                $userMovies->add(collect($movies)->put('score', $movieB->score)->put('status', $movieB->status)->put('release_date', $movieB->created_at));
            }
            // dump($userMovies);
            $userMovies = $userMovies->map(function($movie) {
                return collect($movie)->merge([
                    'poster_path' => $movie['poster_path']
                        ? 'https://image.tmdb.org/t/p/w500'.$movie['poster_path']
                        : 'https://via.placeholder.com/500x750',
                    'release_date' => Carbon::parse($movie['release_date'])->format('M d, Y'),
                    'genres' => collect($movie['genres'])->pluck('name')->take(3)->flatten()->implode(', '),
                ]);
            });
        }
                    
        return view('livewire.movie-search', [
            'userMovies' => $userMovies,
        ]);
    }
}
