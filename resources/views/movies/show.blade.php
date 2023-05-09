@extends('layouts.main')

@section('content')
    <div class="movie-info border-b border-gray-800">
        <div class="container mx-auto px-4 py-16 flex flex-col md:flex-row">
            <img src="{{ $movie['poster_path'] }}" alt="parasite" class="w-64 md:w-96">
            <div class="md:ml-24">
                <div class="flex inline-flex items-center">
                    <h2 class="text-4xl font-semibold">{{ $movie['title'] }}</h2>
                @if ($movie['UserHaveIt']['UserOwns'] == True)
                <div></div>
                @else
                <div x-data="{ isOpen: false }"> <!-- Add-Button -->
                    <div class="">
                        <button
                            @click="isOpen = true"
                            class="flex inline-flex items-center bg-green-500 text-white rounded font-semibold px-2 py-2 ml-4 hover:bg-green-600 transition ease-in-out duration-150"
                        >
                        <svg class="svg-icon-add" viewBox="0 0 20 20">
                                        <path d="M14.613,10c0,0.23-0.188,0.419-0.419,0.419H10.42v3.774c0,0.23-0.189,0.42-0.42,0.42s-0.419-0.189-0.419-0.42v-3.774H5.806c-0.23,0-0.419-0.189-0.419-0.419s0.189-0.419,0.419-0.419h3.775V5.806c0-0.23,0.189-0.419,0.419-0.419s0.42,0.189,0.42,0.419v3.775h3.774C14.425,9.581,14.613,9.77,14.613,10 M17.969,10c0,4.401-3.567,7.969-7.969,7.969c-4.402,0-7.969-3.567-7.969-7.969c0-4.402,3.567-7.969,7.969-7.969C14.401,2.031,17.969,5.598,17.969,10 M17.13,10c0-3.932-3.198-7.13-7.13-7.13S2.87,6.068,2.87,10c0,3.933,3.198,7.13,7.13,7.13S17.13,13.933,17.13,10"></path>
                                </svg>
                            <span class="ml-2">Add</span>
                        </button>
                    </div>
                    <template x-if="isOpen">
                            <div
                                style="background-color: rgba(0, 0, 0, .5);"
                                class="fixed top-0 left-0 w-full h-full flex items-center shadow-lg overflow-y-auto"
                            >
                                <div class="container mx-auto lg:px-32 rounded-lg overflow-y-auto">
                                    <div class="bg-gray-900 rounded">
                                        <div class="flex justify-end pr-4 pt-2">
                                            <button
                                                @click="isOpen = false"
                                                @keydown.escape.window="isOpen = false"
                                                class="text-3xl leading-none hover:text-gray-300">&times;
                                            </button>
                                        </div>
                                        <div class="modal-body px-8 py-8">
                                        <form action="{{ route('collections.store', $movie['id']) }}" method="post">
                                            @csrf
                                            <div class="grid gap-4 mb-4 sm:grid-cols-2">
                                                <div>
                                                    <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                                    <select id="status" name="status" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                        <option value="">Select status</option>
                                                        <option value="Watching">Watching</option>
                                                        <option value="Completed">Completed</option>
                                                        <option value="On-Hold">On-Hold</option>
                                                        <option value="Plan to Watch">Plan to Watch</option>
                                                        <option value="Dropped">Dropped</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label for="score" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Score</label>
                                                    <select id="score" name="score" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                        <option value="--">Select Score</option>
                                                        <option value="10">(10) Masterpiece</option>
                                                        <option value="9">(9) Great</option>
                                                        <option value="8">(8) Very Good</option>
                                                        <option value="7">(7) Good</option>
                                                        <option value="6">(6) Fine</option>
                                                        <option value="5">(5) Average</option>
                                                        <option value="4">(4) Bad</option>
                                                        <option value="3">(3) Very Bad</option>
                                                        <option value="2">(2) Horrible</option>
                                                        <option value="1">(1) Appalling</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <button type="submit" class="text-white inline-flex items-center bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                                                Add new movie to your List
                                            </button>
                                        </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </template>
                </div> <!-- Add-button -->
                @endif                               
                </div>
                <div class="flex flex-wrap items-center text-gray-400 text-sm mt-1">
                    <svg class="fill-current text-orange-500 w-4" viewBox="0 0 24 24"><g data-name="Layer 2"><path d="M17.56 21a1 1 0 01-.46-.11L12 18.22l-5.1 2.67a1 1 0 01-1.45-1.06l1-5.63-4.12-4a1 1 0 01-.25-1 1 1 0 01.81-.68l5.7-.83 2.51-5.13a1 1 0 011.8 0l2.54 5.12 5.7.83a1 1 0 01.81.68 1 1 0 01-.25 1l-4.12 4 1 5.63a1 1 0 01-.4 1 1 1 0 01-.62.18z" data-name="star"/></g></svg>
                        <span class="ml-1">{{ $movie['vote_average'] }}</span>
                        <span class="mx-2">|</span>
                        <span>{{ $movie['release_date'] }}</span>
                        <span class="mx-2">|</span>
                        <span>{{ $movie['genres'] }}</span>
                </div>
                <p class="text-gray-300 mt-8">
                    {{ $movie['overview'] }}
                </p>

                <div class="mt-12">
                    <h4 class="text-white font-semibold">Featured Cast</h4>
                    <div class="flex mt-4">
                    @foreach ($movie['crew'] as $crew)
                        <div class="mr-8">
                            <div>{{ $crew['name'] }}</div>
                            <div class="text-sm text-gray-400">{{ $crew['job'] }}</div>
                        </div>
                    @endforeach
                    </div>
                </div>

                <div x-data="{ isOpen: false }">
                @if (count($movie['videos']['results']) > 0)
                    <div class="mt-12">
                        <button
                            @click="isOpen = true"
                            class="flex inline-flex items-center bg-orange-500 text-gray-900 rounded font-semibold px-5 py-4 hover:bg-orange-600 transition ease-in-out duration-150"
                        >
                            <svg class="w-6 fill-current" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M10 16.5l6-4.5-6-4.5v9zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                            </svg>
                            <span class="ml-2">Play Trailer</span>
                        </button>
                    </div>
                    <template x-if="isOpen">
                            <div
                                style="background-color: rgba(0, 0, 0, .5);"
                                class="fixed top-0 left-0 w-full h-full flex items-center shadow-lg overflow-y-auto"
                            >
                                <div class="container mx-auto lg:px-32 rounded-lg overflow-y-auto">
                                    <div class="bg-gray-900 rounded">
                                        <div class="flex justify-end pr-4 pt-2">
                                            <button
                                                @click="isOpen = false"
                                                @keydown.escape.window="isOpen = false"
                                                class="text-3xl leading-none hover:text-gray-300">&times;
                                            </button>
                                        </div>
                                        <div class="modal-body px-8 py-8">
                                            <div class="responsive-container overflow-hidden relative" style="padding-top: 56.25%">
                                                <iframe class="responsive-iframe absolute top-0 left-0 w-full h-full" src="https://www.youtube.com/embed/{{ $movie['videos']['results'][0]['key'] }}" style="border:0;" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </template>
                @endif
                </div>
            </div>
        </div>
    </div> <!-- End-Movie-info -->

    <div class="movie-cast border-b border-gray-800"> <!-- Movie-Cast -->
        <div class="container mx-auto px-4 py-16">
            <h2 class="text-4xl font-semibold">Cast</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
                @foreach ($movie['cast'] as $cast)
                    <div class="mt-8">
                    <a href="{{ route('actors.show', $cast['id']) }}">
                        <img src="{{ $cast['profile_path'] }}" alt="actor" class="hover:opacity-75 transition ease-in-out duration-150">
                    </a>
                        <div class="mt-2">
                        <a href="{{ route('actors.show', $cast['id']) }}" class="text-lg mt-2 hover:text-gray:300">{{ $cast['name'] }}</a>
                        <div class="text-sm text-gray-400">
                            {{ $cast['character'] }}
                        </div>
                    </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div> <!-- End-Movie-Cast -->

    <div class="movie-images" x-data="{ isOpen: false, image: '' }"> <!-- Movie-Images -->
        <div class="container mx-auto px-4 py-16">
            <h2 class="text-4xl font-semibold">Images</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                @foreach ($movie['images'] as $images)
                <div class="mt-8">
                    <a 
                        @click.prevent="isOpen = true, image='{{ 'https://image.tmdb.org/t/p/original/'.$images['file_path'] }}'"
                        href="#"
                    >
                        <img src="{{ 'https://image.tmdb.org/t/p/w500/'.$images['file_path'] }}" alt="poster" class="hover:opacity-75 transition ease-in-out duration-150">
                    </a>
                </div>
                @endforeach
                <div
                    style="background-color: rgba(0, 0, 0, .5);"
                    class="fixed top-0 left-0 w-full h-full flex items-center shadow-lg overflow-y-auto"
                    x-show="isOpen"
                >
                    <div class="container mx-auto lg:px-32 rounded-lg overflow-y-auto">
                        <div class="bg-gray-900 rounded">
                            <div class="flex justify-end pr-4 pt-2">
                                <button
                                    @click="isOpen = false"
                                    @keydown.escape.window="isOpen = false"
                                    class="text-3xl leading-none hover:text-gray-300">&times;
                                </button>
                            </div>
                            <div class="modal-body px-8 py-8">
                                <img :src="image" alt="poster">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End-Movie-Images -->
@endsection