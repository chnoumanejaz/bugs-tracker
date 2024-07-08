<nav class="bg-white shadow-sm">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">

            <a class="text-xl md:text-2xl tracking-wide text-teal-600 hover:text-teal-800 transition font-bold"
                href="{{ url('/') }}">
                {{ config('app.name', 'Bugs Tracking System') }}
                <small class="hidden md:block text-gray-600/75 text-xs font-normal">Keep track of your bugs</small>
            </a>



            <div class="flex items-center lg:w-auto">
                <ul class="flex list-none lg:ml-auto gap-4">
                    @guest
                        @if (Route::has('login'))
                            <li class="hover:text-teal-600 transition">
                                <a href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="hover:text-teal-600 transition">
                                <a href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="relative flex flex-col text-sm md:text-base md:flex-row md:gap-4 items-center">
                            <p class="font-medium hidden md:block">
                                {{ Auth::user()->name }}
                                <span class="text-sm font-normal">
                                    ({{ Auth::user()->user_type }})
                                </span>
                            </p>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="bg-teal-500 p-2 md:py-2 md:px-4 hover:bg-teal-600 transition rounded-md text-white">
                                    Logout
                                </button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </div>
</nav>
