@extends('layouts.app')


@section('content')
    <a href="/projects/{{ $project->id }}" class="py-1 px-4 border rounded-full hover:bg-gray-200 transition ">
        ⬅ Go Back
    </a>
    <div class="flex justify-center">
        <div class="bg-white shadow-md rounded-lg overflow-hidden w-full md:w-8/12 lg:w-6/12">
            <h2 class="py-4 px-6 bg-teal-500 text-white border-b font-medium">
                Edit your project
            </h2>

            <div class="px-6 py-4">
                <form method="POST" action="{{ route('projects.update', $project->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">
                            Project Title
                            <small class="text-gray-600"> - Minimum 5 characters</small>
                        </label>
                        <input id="title" type="text" placeholder="Your project title here..."
                            class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-teal-500 focus:ring-teal-500 @error('title') border-red-500 @enderror"
                            name="title" required autofocus value="{{ $project->title }}">

                        @error('title')
                            <x-formError>{{ $message }} </x-formError>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description
                            <small class="text-gray-600"> - Minimum 15 characters</small>
                        </label>
                        <textarea name="description" id="description" cols="30" rows="7"
                            placeholder="Write some description about the project..."
                            class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-teal-500 focus:ring-teal-500 @error('description') border-red-500 @enderror">{{ $project->description }}
                        </textarea>
                        @error('description')
                            <x-formError>{{ $message }} </x-formError>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="assignto" class="block text-sm font-medium text-gray-700">Assign to</label>
                        @if ($availableUsers && count($availableUsers) > 0)
                            @php
                                $selectedUsers = [];
                                foreach ($project->users as $user) {
                                    $selectedUsers[] = $user->id;
                                }
                            @endphp

                            <select name="assignto[]" id="assignto"
                                class="mt-2 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-teal-500 focus:ring-teal-500"
                                multiple="multiple">
                                @foreach ($availableUsers as $user)
                                    <option value="{{ $user->id }}" @if (in_array($user->id, $selectedUsers)) selected @endif>
                                        {{ $user->name }} ({{ $user->user_type }})
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <x-error>
                                There is no registered developer or QA person.
                            </x-error>
                        @endif
                    </div>

                    <button type="submit"
                        class="px-4 py-2 bg-teal-500 w-full text-white rounded hover:bg-teal-600 focus:outline-none focus:bg-teal-600">
                        Update the project
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
