@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 pt-16">
    <p class="text-xl">Viewing <b>Your</b> Movies List</p>
        <div class="mymovielist-movies"> <!-- MyMovieList-movies -->
            <h2 class="uppercase tracking-wider text-orange-500 text-lg font-semibold text-center">ALL MOVIES</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
                @foreach ($userMovies as $movie)
                    <x-movie-card-collection :movie="$movie" />
                @endforeach
            </div>
        </div> <!-- End-MyMovieList-movies -->

        <div class="top-rated-tv py-24"> <!-- MyMovieList-tvshows -->
        <h2 class="uppercase tracking-wider text-orange-500 text-lg font-semibold text-center">ALL TVSHOWS</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
            @foreach ($userTvShows as $tvshow)
                    <x-tv-card-collection :tvshow="$tvshow" />
            @endforeach
            </div>
        </div> <!-- End-MyMovieList-tvshows -->
</div>
@endsection