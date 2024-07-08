@extends('layouts.app')


@section('content')

    <a href="/projects/{{ $bug->project_id }}" class="py-1 px-4 border rounded-full hover:bg-gray-200 transition ">
        ⬅ Go Back
    </a>


    <div class="w-full border p-4 mt-6 rounded-lg ">
        <div class="flex justify-between items-center mb-2">
            <div class="flex items-center gap-3">
                <div class="md:text-4xl text-3xl" title="{{ $bug->type }}">
                    {{ $bug->type === 'Feature' ? '💡' : '🐞' }}
                </div>
                <div>
                    <h3 class="md:text-xl font-medium">{{ $bug->title }}</h3>
                    <p class="text-gray-600 text-xs md:text-base">in project ({{ $bug->project->title }})</p>
                </div>
            </div>


            <p class="md:text-lg text-nowrap text-sm">
                {{ $bug->status === 'Resolved' || $bug->status === 'Completed' ? '✅' : '⌛' }}
                {{ $bug->status }}</p>


            {{-- For the qa to edit or delete the bug --}}
            @if (Auth::user()->user_type === 'QA' && Auth::user()->id === $bug->qa->id)
                <div class="space-x-2 flex">
                    <x-button href='/bugs/{{ $bug->id }}/edit' variant="default">
                        Edit
                    </x-button>
                    <form action="{{ route('bugs.destroy', $bug->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="py-2 px-4 rounded-lg text-white bg-red-500 hover:bg-red-600 transition">
                            Delete
                        </button>
                    </form>
                </div>
            @endif

            {{-- For the developer who has to solve this bug (can update the status) --}}
            @if (Auth::user()->user_type === 'Developer' && Auth::user()->id === $bug->developer->id)
                <div class="space-x-2 flex items-center gap-4">
                    <h2 class="text-">Change Status: </h2>
                    <form action="{{ route('bugs.update', $bug->id) }}" method="POST" class="flex flex-col gap-2">
                        @csrf
                        @method('PUT')
                        <select name="bugStatusUpdate" id="bugStatusUpdate" class="p-1 rounded-lg">
                            <option value="New" @if (in_array('New', [$bug->status])) selected @endif>New</option>
                            <option value="Started" @if (in_array('Started', [$bug->status])) selected @endif>Started</option>
                            @if ($bug->type === 'Feature')
                                <option value="Completed" @if (in_array('Completed', [$bug->status])) selected @endif>Completed
                                </option>
                            @else
                                <option value="Resolved" @if (in_array('Resolved', [$bug->status])) selected @endif>Resolved</option>
                            @endif
                        </select>
                        <button type="submit" class="py-1 px-2 rounded-lg bg-teal-500 text-white">
                            Update
                        </button>
                    </form>
                </div>
            @endif
        </div>
        <hr />

        <div class="mt-4">
            @if ($bug->description)
                <div class="flex flex-col md:flex-row">
                    <h4 class="min-w-48 font-medium">Description:</h4>
                    <p class="text-gray-700">{{ $bug->description }}</p>
                </div>
            @endif
            <div class="flex mt-3 flex-col md:flex-row">
                <h4 class="w-48 font-medium">Deadline:</h4>
                <p class="text-gray-700">{{ $bug->deadline }}</p>
            </div>
            <div class="flex mt-3 flex-col md:flex-row">
                <h4 class="w-48 font-medium">Created at:</h4>
                <p class="text-gray-700">{{ $bug->created_at }}</p>
            </div>
            <div class="flex mt-3 flex-col md:flex-row">
                <h4 class="w-48 font-medium">Screenshot:</h4>
                @if ($bug->screenshot)
                    <img src="/storage/images/{{ $bug->screenshot }}" alt="{{ $bug->title }}"
                        class="md:w-1/2 mt-1 mb-4 rounded-lg shadow-sm">
                @else
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-1 rounded-lg mb-4">
                        <span class="block sm:inline"> No screenshot provided by the QA
                            ({{ $bug->qa->name }})</span>
                    </div>
                @endif
            </div>
        </div>

        <hr />
        <div class="flex gap-8 flex-col md:flex-row">
            <div class="md:mt-4 md:w-1/2 bg-gray-50 border p-4 rounded-lg">
                <div class="flex items-end justify-between">
                    <h3 class="font-medium">Bug Assigned to:</h3>
                    <span class="bg-teal-500 text-white w-auto px-2 rounded-full text-sm">
                        Developer
                    </span>
                </div>
                <div class="mt-2">
                    <div class="flex">
                        <h4 class="w-32 md:w-48 font-medium">Name:</h4>
                        <p class="text-gray-700">{{ $bug->developer->name }}</p>
                    </div>
                    <div class="flex mt-3">
                        <h4 class="w-32 md:w-48 font-medium">Email:</h4>
                        <p class="text-gray-700">{{ $bug->developer->email }}</p>
                    </div>
                </div>
            </div>
            <div class="md:mt-4 md:w-1/2 bg-gray-50 border p-4 rounded-lg">
                <div class="flex items-end justify-between">
                    <h3 class="font-medium">Bug Reported by:</h3>
                    <span class="bg-teal-500 text-white w-auto px-2 rounded-full text-sm">
                        QA
                    </span>
                </div>
                <div class="mt-2">
                    <div class="flex">
                        <h4 class="w-32 md:w-48 font-medium">Name:</h4>
                        <p class="text-gray-700">{{ $bug->qa->name }}</p>
                    </div>
                    <div class="flex mt-3">
                        <h4 class="w-32 md:w-48 font-medium">Email:</h4>
                        <p class="text-gray-700">{{ $bug->qa->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
