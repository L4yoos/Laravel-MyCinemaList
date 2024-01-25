<div> 
    <input
    x-ref="search"
    @keydown.window="
        if (event.keyCode === 191) {
            event.preventDefault();
            $refs.search.focus();
        }
    " 
    @focus="isOpen = true"
    @keydown="isOpen = true"
    @keydown.escape.window="isOpen = false"
    @keydown.shift.tab="isOpen = false"  
    wire:model.debounce.500ms="search" type="text" class="bg-gray-800 text-sm rounded-full w-64 px-4 pl-8 py-1 focus:outline-none focus:shadow-outline" placeholder="Search (Press '/' to focus)">

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">

        @foreach ($userMovies as $movie)
                <x-movie-card-collection :movie="$movie" />
        @endforeach
    </div>
</div>