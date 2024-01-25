<?php

namespace App\ViewModels;

use Spatie\ViewModels\ViewModel;
use Carbon\Carbon;
use App\Models\Tvshows_Collection;
use App\Models\User;

class TvShowViewModel extends ViewModel
{
    public $tvshow;

    public function __construct($tvshow)
    {
        $this->tvshow = $tvshow;
    }

    private function checkIfUserHaveThis($tvshow)
    {
        $user = User::find(\Auth::id());
        $collection_id = $user->collection->id;
        $tvshows = Tvshows_Collection::Where('tvshow_id', $tvshow)->Where('collection_id', $collection_id)->first();
        if($tvshows) return true;
        return false;
    }

    public function tvshow()
    {
        return collect($this->tvshow)->merge([
            'UserHaveIt' => $this->checkIfUserHaveThis($this->tvshow['id']) ? collect($this->tvshow)->put('UserOwns', True) : collect($this->tvshow)->put('UserOwns', False),
            'poster_path' => 'https://image.tmdb.org/t/p/w500/'.$this->tvshow['poster_path']
                ? 'https://image.tmdb.org/t/p/w500/'.$this->tvshow['poster_path']
                : 'https://via.placeholder.com/500x750',
            'first_air_date' => Carbon::parse($this->tvshow['first_air_date'])->format('M d, Y'),
            'genres' => collect($this->tvshow['genres'])->pluck('name')->flatten()->implode(', '),
            'crew' => !empty($this->tvshow['credits']['crew']) 
                ? collect($this->tvshow['credits']['crew'])->take(2) 
                : collect($this->tvshow['credits']['crew'])->push(['name'=>'None', 'job'=>'None']),
            'cast' => collect($this->tvshow['credits']['cast'])->take(5)->map(function($cast) {
                    return collect($cast)->merge([
                        'profile_path' => $cast['profile_path']
                            ? 'https://image.tmdb.org/t/p/w300'.$cast['profile_path']
                            : 'https://via.placeholder.com/300x450',
                    ]);
                }),
            'images' => collect($this->tvshow['images']['backdrops'])->take(9),
            'watch_providers' => $this->tvshow['watch/providers']['results'],
        ])->only([
            'poster_path', 'id', 'genres', 'name', 'vote_average', 'overview', 'first_air_date', 'credits' ,
            'videos', 'images', 'crew', 'cast', 'images', 'created_by', 'number_of_episodes', 'UserHaveIt', 'watch_providers'
        ]);
    }
}
