@extends('layouts.app')

@section('content')
    <div>
        <div class="flex md:flex-row flex-col justify-between md:items-center gap-2">
            <div>
                <h2 class="md:text-2xl font-medium ">Welcome,
                    <span class="text-teal-700 ">
                        {{ Auth::user()->name }}
                    </span>
                    <span class="text-teal-700 text-sm ">
                        ({{ Auth::user()->user_type }})
                    </span>
                </h2>
                <p class="text-gray-600 text-xs md:text-sm">You will see all your projects here</p>
            </div>

            @if (Auth::user()->user_type === 'Manager')
                <a class="w-fit py-2 px-4 ml-auto rounded-lg bg-teal-500 hover:bg-teal-600 transition text-white"
                    href="/projects/create">Create a new project</a>
            @endif
        </div>

        @if ($projects && count($projects) > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-6">
                @foreach ($projects as $project)
                    <div
                        class="border p-4 hover:shadow-md transition bg-white hover:bg-gray-50 rounded-lg border-l-8 border-l-teal-500 shadow-inner">
                        <a href="/projects/{{ $project->id }}"
                            class="text-lg font-medium text-teal-600 hover:text-teal-800 transition-colors">{{ $project->title }}</a>
                        <p class="text-sm text-gray-600/75">Created on: {{ $project->created_at }}</p>
                        <p class="mt-2">
                            {{ Str::words($project->description, 13) }}
                        </p>
                    </div>
                @endforeach
            </div>
        @else
            <x-error>
                You don't have any projects.
            </x-error>
        @endif
    </div>
@endsection
