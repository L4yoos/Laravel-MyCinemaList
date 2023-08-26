<?php

namespace App\ViewModels;

use App\Models\Actors_Collection;
use Spatie\ViewModels\ViewModel;
use Carbon\Carbon;

class ActorViewModel extends ViewModel
{
    public $actor;
    public $social;
    public $credits;

    public function __construct($actor, $social, $credits)
    {
        $this->actor = $actor;
        $this->social = $social;
        $this->credits = $credits;
    }

    public function actor()
    {
        return collect($this->actor)->merge([
            'UserHaveIt' => $this->checkIfUserHaveThis($this->actor['id']) ? collect($this->actor)->put('UserOwns', True) : collect($this->actor)->put('UserOwns', False),
            'profile_path' => $this->actor['profile_path'] 
                ? 'https://image.tmdb.org/t/p/w300/'.$this->actor['profile_path']
                : 'https://via.placeholder.com/300x350',
            'birthday' => Carbon::parse($this->actor['birthday'])->format('M d, Y'),
            'age' => Carbon::parse($this->actor['birthday'])->age,
        ])->only([
            'birthday', 'age', 'profile_path', 'name', 'id', 'homepage', 'place_of_birth', 'biography', 'UserHaveIt'
        ]);
    }

    public function social()
    {
        return collect($this->social)->merge([
            'twitter' => $this->social['twitter_id'] 
                ? 'https://twitter.com/'.$this->social['twitter_id'] 
                : null,
            'instagram' => $this->social['instagram_id'] 
                ? 'https://instagram.com/'.$this->social['instagram_id'] 
                : null,
            'facebook' => $this->social['facebook_id'] 
                ? 'https://facebook.com/'.$this->social['facebook_id'] 
                : null,
        ])->only([
            'facebook', 'instagram', 'twitter',
        ]);
    }

    public function knownForMovies()
    {
        $castMovies = collect($this->credits)->get('cast');

        return collect($castMovies)->SortbyDesc('popularity')->unique('id')->take(5)
            ->map(function ($movie) {
                if (isset($movie['title'])) {
                    $title = $movie['title'];
                } elseif (isset($movie['name'])) {
                    $title = $movie['name'];
                } else {
                    $title = 'Untitled';
                }

                return collect($movie)->merge([
                    'poster_path' => $movie['poster_path']
                        ? 'https://image.tmdb.org/t/p/w185'.$movie['poster_path']
                        : 'https://via.placeholder.com/185x278',
                    'title' => $title, 
                    'linkToPage' => $movie['media_type'] === 'movie' 
                        ? route('movies.show', $movie['id']) 
                        : route('tv.show', $movie['id'])
                ])->only([
                    'poster_path', 'title', 'id', 'media_type', 'linkToPage', 'character'
                ]);
            });
    }

    public function credits()
    {
        $castMovies = collect($this->credits)->get('cast');

        return collect($castMovies)->map(function ($movie) {
            if (isset($movie['release_date'])) {
                $releaseDate = $movie['release_date'];
            } elseif (isset($movie['first_air_date'])) {
                $releaseDate = $movie['first_air_date'];
            } else {
                $releaseDate = '';
            }

            if (isset($movie['title'])) {
                $title = $movie['title'];
            } elseif (isset($movie['name'])) {
                $title = $movie['name'];
            } else {
                $title = 'Untitled';
            }

            return collect($movie)->merge([
                'release_date' => $releaseDate,
                'release_year' => isset($releaseDate) 
                    ? Carbon::parse($releaseDate)->format('Y') 
                    : 'Future',
                'title' => $title,
                'character' => isset($movie['character']) 
                    ? $movie['character'] 
                    : '',
                'linkToPage' => $movie['media_type'] === 'movie' 
                    ? route('movies.show', $movie['id']) 
                    : route('tv.show', $movie['id']),
            ])->only([
                'release_date', 'release_year', 'title', 'character', 'linkToPage',
            ]);
        })->sortByDesc('release_year');
    }

    private function checkIfUserHaveThis($actor)
    {
        $actors = Actors_Collection::Where('actor_id', $actor)->first();
        if($actors) return true;
        return false;
    }

}
