@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 pt-16">
    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
        <div class="popular-movies"> <!-- Popular-Movies -->
            <h2 class="uppercase tracking-wider text-orange-500 text-lg font-semibold text-center">Popular Movies</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
                @foreach ($popularMovies as $movie)
                    <x-movie-card :movie="$movie" />
                @endforeach
            </div>
        </div> <!-- End-Popular-Movies -->

        <div class="now-playing-movies py-24"> <!-- Now-Popular-Movies -->
        <h2 class="uppercase tracking-wider text-orange-500 text-lg font-semibold text-center">Now Playing</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
                @foreach ($nowPlayingMovies as $movie)
                <x-movie-card :movie="$movie" />
                @endforeach
            </div>
        </div>
    </div> <!-- End-Now-Playing-Movies -->
@endsection