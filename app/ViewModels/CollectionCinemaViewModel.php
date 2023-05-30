<?php

namespace App\ViewModels;

use Spatie\ViewModels\ViewModel;
use Carbon\Carbon;

class CollectionCinemaViewModel extends ViewModel
{
    public $userMovies;
    public $userTvShows;
    public $userActors;

    public function __construct($userMovies, $userTvShows, $userActors)
    {
        $this->userMovies = $userMovies;
        $this->userTvShows = $userTvShows;
        $this->userActors = $userActors;
    }

    public function userMovies()
    {
        return $this->formatMovies($this->userMovies);
    }

    public function userTvShows()
    {
        return $this->formatTv($this->userTvShows);
    }

    public function userActors()
    {
        return $this->formatActors($this->userActors);
    }


    private function formatActors($userActors)
    {
        return collect($userActors)->map(function($actor) {
            return collect($actor)->merge([
                'profile_path' => $actor['profile_path'] 
                    ? 'https://image.tmdb.org/t/p/w300/'.$actor['profile_path']
                    : 'https://via.placeholder.com/300x350',
            ]);
        });
    }

    private function formatMovies($userMovies)
    {
        return collect($userMovies)->map(function($movie) {
            return collect($movie)->merge([
                'poster_path' => $movie['poster_path']
                    ? 'https://image.tmdb.org/t/p/w500/'.$movie['poster_path']
                    : 'https://via.placeholder.com/500x750',
                'release_date' => Carbon::parse($movie['release_date'])->format('M d, Y'),
                'genres' => collect($movie['genres'])->pluck('name')->take(3)->flatten()->implode(', '),
            ])->only([
                'poster_path', 'id', 'title', 'release_date', 'genres', 'score', 'status'
            ]);
        })->sortByDesc('status');
    }
    
    private function formatTv($userTvShows)
    {
        return collect($userTvShows)->map(function($tvshow) {
            return collect($tvshow)->merge([
                'poster_path' => $tvshow['poster_path']
                    ? 'https://image.tmdb.org/t/p/w500/'.$tvshow['poster_path']
                    : 'https://via.placeholder.com/500x750',
                'release_date' => Carbon::parse($tvshow['release_date'])->format('M d, Y'),
                'genres' => collect($tvshow['genres'])->pluck('name')->take(3)->flatten()->implode(', '),
            ])->only([
                'poster_path', 'id', 'name', 'release_date', 'genres', 'score', 'status', 'number_of_episodes', 'watched_episodes'
            ]);
        })->sortByDesc('status');
    }
}

