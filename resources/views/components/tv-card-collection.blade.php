<div class="mt-8">
    <a href="{{ route('tv.show', $tvshow['id']) }}">
        <img src="{{ $tvshow['poster_path'] }}" alt="poster" class="hover:opacity-75 transition ease-in-out duration-150">
    </a>
    <div class="mt-2">
        @if($tvshow['status'] == 'Completed')
        <a href="{{ route('tv.show', $tvshow['id']) }}" class="text-green-600 text-lg mt-2">{{ $tvshow['name'] }} (Completed)</a>
        @elseif ($tvshow['status'] == 'Watching')
        <a href="{{ route('tv.show', $tvshow['id']) }}" class="text-purple-600 text-lg mt-2">{{ $tvshow['name'] }} (Watching)</a>
        @elseif ($tvshow['status'] == 'On-Hold')
        <a href="{{ route('tv.show', $tvshow['id']) }}" class="text-gray-600 text-lg mt-2">{{ $tvshow['name'] }} (On-Hold)</a>
        @elseif ($tvshow['status'] == 'Plan to Watch')
        <a href="{{ route('tv.show', $tvshow['id']) }}" class="text-yellow-600 text-lg mt-2">{{ $tvshow['name'] }} (Plan To Watch)</a>
        @elseif ($tvshow['status'] == 'Dropped')
        <a href="{{ route('tv.show', $tvshow['id']) }}" class="text-red-600 text-lg mt-2">{{ $tvshow['name'] }} (Dropped)</a>
        @else
        <a href="{{ route('tv.show', $tvshow['id']) }}" class="text-lg mt-2">{{ $tvshow['name'] }}</a>
        @endif
        <div class="flex items-center text-gray-400 text-sm mt-1">
            <svg class="fill-current text-orange-500 w-4" viewBox="0 0 24 24"><g data-name="Layer 2"><path d="M17.56 21a1 1 0 01-.46-.11L12 18.22l-5.1 2.67a1 1 0 01-1.45-1.06l1-5.63-4.12-4a1 1 0 01-.25-1 1 1 0 01.81-.68l5.7-.83 2.51-5.13a1 1 0 011.8 0l2.54 5.12 5.7.83a1 1 0 01.81.68 1 1 0 01-.25 1l-4.12 4 1 5.63a1 1 0 01-.4 1 1 1 0 01-.62.18z" data-name="star"/></g></svg>
            @if ($tvshow['score'])
                    <span class="ml-1">{{ $tvshow['score'] }}</span>
                    <span class="mx-2">|</span>
                    <span class="ml-1">{{ $tvshow['status'] }}</span>
                    <span class="mx-2">|</span>
                    <span class="ml-1">{{ $tvshow['watched_episodes'] }}/{{ $tvshow['number_of_episodes']}}</span>
            @endif
            <span class="mx-2">|</span>
            <span>{{ $tvshow['release_date'] }}</span>
        </div>
        <div class="text-gray-400 text-sm">{{ $tvshow['genres'] }}</div>
        
        <div x-data="{ isOpen: false }"> <!-- Add-Button -->
        <div class="">
                <button
                    @click="isOpen = true"
                    class="mt-2 text-white rounded font-semibold transition ease-in-out duration-150"
                >
                <svg class="svg-icon" viewBox="0 0 20 20">
					<path d="M6.176,7.241V6.78c0-0.221-0.181-0.402-0.402-0.402c-0.221,0-0.403,0.181-0.403,0.402v0.461C4.79,7.416,4.365,7.955,4.365,8.591c0,0.636,0.424,1.175,1.006,1.35v3.278c0,0.222,0.182,0.402,0.403,0.402c0.222,0,0.402-0.181,0.402-0.402V9.941c0.582-0.175,1.006-0.714,1.006-1.35C7.183,7.955,6.758,7.416,6.176,7.241 M5.774,9.195c-0.332,0-0.604-0.272-0.604-0.604c0-0.332,0.272-0.604,0.604-0.604c0.332,0,0.604,0.272,0.604,0.604C6.377,8.923,6.105,9.195,5.774,9.195 M10.402,10.058V6.78c0-0.221-0.181-0.402-0.402-0.402c-0.222,0-0.402,0.181-0.402,0.402v3.278c-0.582,0.175-1.006,0.714-1.006,1.35c0,0.637,0.424,1.175,1.006,1.351v0.461c0,0.222,0.181,0.402,0.402,0.402c0.221,0,0.402-0.181,0.402-0.402v-0.461c0.582-0.176,1.006-0.714,1.006-1.351C11.408,10.772,10.984,10.233,10.402,10.058M10,12.013c-0.333,0-0.604-0.272-0.604-0.604S9.667,10.805,10,10.805c0.332,0,0.604,0.271,0.604,0.604S10.332,12.013,10,12.013M14.629,8.448V6.78c0-0.221-0.182-0.402-0.403-0.402c-0.221,0-0.402,0.181-0.402,0.402v1.668c-0.581,0.175-1.006,0.714-1.006,1.35c0,0.636,0.425,1.176,1.006,1.351v2.07c0,0.222,0.182,0.402,0.402,0.402c0.222,0,0.403-0.181,0.403-0.402v-2.07c0.581-0.175,1.006-0.715,1.006-1.351C15.635,9.163,15.21,8.624,14.629,8.448 M14.226,10.402c-0.331,0-0.604-0.272-0.604-0.604c0-0.332,0.272-0.604,0.604-0.604c0.332,0,0.604,0.272,0.604,0.604C14.83,10.13,14.558,10.402,14.226,10.402 M17.647,3.962H2.353c-0.221,0-0.402,0.181-0.402,0.402v11.27c0,0.222,0.181,0.402,0.402,0.402h15.295c0.222,0,0.402-0.181,0.402-0.402V4.365C18.05,4.144,17.869,3.962,17.647,3.962 M17.245,15.232H2.755V4.768h14.49V15.232z"></path>
				</svg>
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
                                        <form action="{{ route('collections.update', $tvshow['id']) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="grid gap-4 mb-4 sm:grid-cols-2">
                                                <div>
                                                    <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                                    <select id="status" name="status" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                        <option value="{{ $tvshow['status'] }}">Select status (Default {{ $tvshow['status'] }})</option>
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
                                                        <option value="{{ $tvshow['score'] }}">Select Score (Default {{ $tvshow['score'] }})</option>
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
                                                <div class="flex">
                                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                                    Number of Episodes {{ $tvshow['number_of_episodes'] }}
                                                </span>
                                                <input type="number" name="watched_episodes" class="rounded-none rounded-r-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="How many have you already watched?">
                                                </div>
                                            </div>
                                            <button type="submit" class="text-white inline-flex items-center bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                                                Edit your properties!
                                            </button>
                                        </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </template>
                </div> <!-- Add-button -->
    </div>
 </div>