<?php

namespace App\ViewModels;

use Spatie\ViewModels\ViewModel;
use Carbon\Carbon;
use App\Models\Movies_Collection;

class MovieViewModel extends ViewModel
{
    public $movie;

    public function __construct($movie)
    {
        $this->movie = $movie;
    }

    private function checkIfUserHaveThis($movie)
    {
        $movies = Movies_Collection::Where('movie_id', $movie)->first();
        if($movies) return true;
        return false;
    }

    public function movie()
    {
        return collect($this->movie)->merge([
            'UserHaveIt' => $this->checkIfUserHaveThis($this->movie['id']) ? collect($this->movie)->put('UserOwns', True) : collect($this->movie)->put('UserOwns', False),
            'poster_path' => $this->movie['poster_path']
                ? 'https://image.tmdb.org/t/p/w500/'.$this->movie['poster_path']
                : 'https://via.placeholder.com/500x750',
            'release_date' => Carbon::parse($this->movie['release_date'])->format('M d, Y'),
            'genres' => collect($this->movie['genres'])->pluck('name')->flatten()->implode(', '),
            'crew' => collect($this->movie['credits']['crew'])->take(2),
            'cast' => collect($this->movie['credits']['cast'])->take(5)->map(function($cast) {
                return collect($cast)->merge([
                    'profile_path' => $cast['profile_path']
                        ? 'https://image.tmdb.org/t/p/w300'.$cast['profile_path']
                        : 'https://via.placeholder.com/300x450',
                ]);
            }),
            'images' => collect($this->movie['images']['backdrops'])->take(9),
        ])->only([
            'poster_path', 'id', 'genres', 'title', 'vote_average', 'overview', 'release_date', 'credits' ,
            'videos', 'images', 'crew', 'cast', 'images', 'UserHaveIt'
        ]);
    }
}
