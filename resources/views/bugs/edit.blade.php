@extends('layouts.app')


@section('content')
    <a href="/bugs/{{ $bug->id }}" class="py-1 px-4 border rounded-full hover:bg-gray-200 transition "> ⬅ Go
        Back</a>
    <div class="flex justify-center">
        <div class="bg-white shadow-md rounded-lg overflow-hidden w-full md:w-8/12 lg:w-6/12">
            <h2 class="py-4 px-6 bg-teal-500 text-white border-b font-medium">
                Update the Bug details
            </h2>

            <form method="POST" action="{{ route('bugs.update', $bug->id) }}" enctype="multipart/form-data" class="mx-6 my-4">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">
                        Bug Title
                        <small class="text-gray-600"> - Must be unique</small>
                    </label>
                    <input id="title" type="text" placeholder="Your Bug title here..." value="{{ $bug->title }}"
                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-teal-500 focus:ring-teal-500 @error('title') border-red-500 @enderror"
                        name="title" required autofocus>

                    @error('title')
                        <x-formError>{{ $message }}</x-formError>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" cols="30" rows="4"
                        placeholder="Write some description about the Bug..."
                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-teal-500 focus:ring-teal-500 @error('description') border-red-500 @enderror">{{ $bug->description }}</textarea>
                    @error('description')
                        <x-formError>{{ $message }}</x-formError>
                    @enderror
                </div>
                <div class="mb-4 grid grid-cols-2 gap-4">
                    <div>
                        <label for="deadline" class="block text-sm font-medium text-gray-700">Deadline</label>
                        <input id="deadline" type="date" value="{{ $bug->deadline }}"
                            class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-teal-500 focus:ring-teal-500 @error('deadline') border-red-500 @enderror"
                            name="deadline">
                        @error('deadline')
                            <x-formError>{{ $message }}</x-formError>
                        @enderror
                    </div>

                    <div>
                        <label for="assigntodev" class="block text-sm font-medium text-gray-700">Assign to - select a
                            developer</label>
                        @if ($users && count($users) > 0)
                            <select name="assigntodev" id="assigntodev"
                                class="mt-2 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-teal-500 focus:ring-teal-500  @error('assigntodev') border-red-500 @enderror">
                                <option value="">Select a developer</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @if (in_array($user->id, [$bug->developer_id])) selected @endif>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 mt-1 rounded-lg">
                                <span class="block text-nowrap">No developer found in this project</span>
                            </div>
                        @endif
                        @error('assigntodev')
                            <x-formError>{{ $message }}</x-formError>
                        @enderror
                    </div>
                </div>

                <div class="mb-4 grid grid-cols-2 gap-4">
                    <div>
                        <label for="bugtype" class="block text-sm font-medium text-gray-700">Type</label>
                        <select name="bugtype" id="bugtype" onchange="updateBugStatusOptions()"
                            class="mt-2 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-teal-500 focus:ring-teal-500  @error('bugtype') border-red-500 @enderror">
                            <option value="">Select a type</option>
                            <option value="Feature" @if (in_array('Feature', [$bug->type])) selected @endif>Feature</option>
                            <option value="Bug" @if (in_array('Bug', [$bug->type])) selected @endif>Bug</option>
                        </select>
                        @error('bugtype')
                            <x-formError>{{ $message }}</x-formError>
                        @enderror
                    </div>

                    <div>
                        <label for="bugstatus" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="bugstatus" id="bugstatus"
                            class="mt-2 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-teal-500 focus:ring-teal-500  @error('bugstatus') border-red-500 @enderror">
                            <option value="">Select a status</option>
                            <option value="New" @if (in_array('New', [$bug->status])) selected @endif>New</option>
                            <option value="Started" @if (in_array('Started', [$bug->status])) selected @endif>Started</option>
                            @if ($bug->type === 'Bug')
                                <option value="Resolved" @if (in_array('Resolved', [$bug->status])) selected @endif>Resolved
                                </option>
                            @else
                                <option value="Completed" @if (in_array('Completed', [$bug->status])) selected @endif>Completed
                                </option>
                            @endif
                        </select>
                        @error('bugstatus')
                            <x-formError>{{ $message }}</x-formError>
                        @enderror
                    </div>
                </div>


                <div class="mb-4">
                    <label for="screenshot" class="block text-sm font-medium text-gray-700">Attach Screenshot
                        <small class="text-gray-600"> - Allowed extensions are jpg, png</small>
                    </label>
                    <input id="screenshot" type="file" accept="image/png, image/jpeg"
                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-teal-500 focus:ring-teal-500"
                        name="screenshot">
                </div>

                <button type="submit"
                    class="px-4 py-2 bg-teal-500 w-full text-white rounded hover:bg-teal-600 focus:outline-none focus:bg-teal-600">
                    Update Bug
                </button>
            </form>
        </div>
    </div>

@endsection
