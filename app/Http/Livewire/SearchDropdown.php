<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class SearchDropdown extends Component
{
    public $search = '';

    public function render()
    {
        $searchResults = [];
        if (strlen($this->search) >= 2) {
            $searchResults = Http::get('https://api.themoviedb.org/3/search/movie?query='.$this->search.'&api_key=8ed40e121ef00a7cda48c64fa354d419')
            ->json()['results']; 
        }



        // dump($searchResults);

        return view('livewire.search-dropdown', [
            'searchResults' => collect($searchResults)->take(6),
        ]);
    }
}
