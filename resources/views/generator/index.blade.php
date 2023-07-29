@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 pt-16">
        <div class="Random-Generator">
            <div class="grid grid-cols-2 items-center">
                <a href="{{ route('generator.random') }}">
                    <button class="bg-orange-500 text-gray-900 rounded font-semibold px-2 py-2 hover:bg-orange-600 transition ease-in-out duration-150">Random Movie</button>
                </a>
                <div>
                    <form action="{{ route('generator.genre') }}" method="get">
                        <label for="genre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Genres</label>
                        <select id="genre" name="genre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="--">Select Genre</option>
                            <option value="28">Action</option>
                            <option value="12">Adventure</option>
                            <option value="16">Animation</option>
                            <option value="35">Comedy</option>
                            <option value="80">Crime</option>
                            <option value="99">Documentary</option>
                            <option value="18">Drama</option>
                            <option value="10751">Family</option>
                            <option value="14">Fantasy</option>
                            <option value="36">History</option>
                            <option value="27">Horror</option>
                            <option value="10402">Music</option>
                            <option value="9648">Mystery</option>
                            <option value="10749">Romance</option>
                            <option value="878">Science Fiction</option>
                            <option value="53">Thriller</option>
                            <option value="10752">War</option>
                            <option value="37">Western</option>
                        </select>
                        <button type="submit" class="bg-orange-500 text-gray-900 rounded font-semibold px-2 py-2 hover:bg-orange-600 transition ease-in-out duration-150 mt-8">Generate</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection