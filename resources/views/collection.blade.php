@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 pt-16">
    <p class="text-xl">Viewing <b>Your</b> Cinema List</p>
        <div class="flex flex-col md:flex-row uppercase tracking-wider text-lg font-semibold mt-4">
            <form action="{{ route('collections.index', Auth::id()) }}" method="get">
            <button type="submit" name="sortBy" value="title">Title</button>
            <button type="submit" name="sortBy" value="status">Status</button>
            <button type="submit" name="sortBy" value="score">Score</button>
            <button type="submit" name="sortBy" value="genres">Genres</button> 
        </form>
        </div>
        <div class="MyCinemalist-movies"> <!-- MyMovieList-movies -->
            <h2 class="uppercase tracking-wider text-orange-500 text-lg font-semibold text-center">ALL MOVIES</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
                @foreach ($userMovies as $movie)
                    <x-movie-card-collection :movie="$movie" />
                @endforeach
            </div>
        </div> <!-- End-MyMovieList-movies -->

        <div class="MyCinemaList-tvshows py-24"> <!-- MyMovieList-tvshows -->
        <h2 class="uppercase tracking-wider text-orange-500 text-lg font-semibold text-center">ALL TVSHOWS</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
            @foreach ($userTvShows as $tvshow)
                    <x-tv-card-collection :tvshow="$tvshow" />
            @endforeach
            </div>
        </div> <!-- End-MyMovieList-tvshows -->

        
        <div class="MyCinemaList-Actors py-24"> <!-- MyCinemaList-Actors -->
            <h2 class="uppercase tracking-wider text-orange-500 text-lg font-semibold text-center">FAVORITE ACTORS</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
                    @foreach ($userActors as $actor)
                        <x-actor-card-collection :actor="$actor" />
                    @endforeach
                </div>
        </div> <!-- End-MyCinemaList-Actors -->
</div>
@endsection