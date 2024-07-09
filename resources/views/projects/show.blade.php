@extends('layouts.app')


@section('content')
    <a href="/projects" class="py-1 px-4 border rounded-full hover:bg-gray-200 transition "> ⬅ Go Back</a>

    <div class="mt-6 flex flex-col lg:flex-row justify-between items-start lg:items-center">
        <div>
            <h2 class="font-medium text-xl">
                {{ $project->title }}
            </h2>
            <p class="text-gray-600 text-sm md:text-base">
                {{ $project->description }}
            </p>
        </div>

        @if (Auth::user()->user_type === 'Manager')
            <div class="space-x-2 flex mt-2 md:mt-0 self-end">
                <x-button href="/projects/{{ $project->id }}/edit" variant="default">
                    Edit Project
                </x-button>
                <form action="{{ route('projects.destroy', $project->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="py-2 px-4 text-nowrap rounded-lg text-white bg-red-500 hover:bg-red-600 transition">
                        Delete Project
                    </button>
                </form>
            </div>
        @endif
    </div>

    <div class="flex flex-col md:flex-row items-start gap-4 mt-6">
        <div class="border w-full md:w-1/3 rounded-lg p-4 order-1 md:order-none">
            <h2 class="font-medium">Assigned Users ({{ count($project->users) }})</h2>
            <p class="text-gray-600 text-sm mb-2">Users who have access to this project </p>
            <hr />

            @if ($project->users && count($project->users) > 0)
                <div class="mt-4 space-y-4">
                    @foreach ($project->users as $user)
                        <div class="bg-gray-50 border rounded-md p-2 flex justify-between items-start">
                            <div>
                                <h3>
                                    {{ $user->name }}
                                </h3>
                                <small>{{ $user->email }}</small>
                            </div>
                            <div class="bg-teal-500 text-white w-auto px-2 rounded-full text-sm">
                                {{ $user->user_type }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <x-error>
                    Currently there is no user who have the access to this project.
                    <br />
                    <br />
                    Add new users by Editing the project
                </x-error>
            @endif
        </div>

        <div class="w-full border p-4 rounded-lg ">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-medium">Bugs</h2>
                    <p class="text-gray-600 text-sm mb-2">Bugs in the project created by QA's</p>
                </div>

                @if (Auth::user()->user_type === 'QA')
                    <x-button href="/bugs/create?project_id={{ $project->id }}" variant="default">
                        Report new Bug
                    </x-button>
                @endif
            </div>
            <hr />

            @if ($project->bugs && count($project->bugs) > 0)
                <div class="relative overflow-x-auto mt-4">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-800 rounded-lg overflow-hidden">
                        <thead class="text-gray-800 uppercase bg-gray-100 ">
                            <tr>
                                <th scope="col" class="px-6 py-3"> Title </th>
                                <th scope="col" class="px-6 py-3"> Type </th>
                                <th scope="col" class="px-6 py-3"> Status </th>
                                <th scope="col" class="px-6 py-3"> Deadline </th>
                                <th scope="col" class="px-6 py-3 text-nowrap"> Created by </th>
                                <th scope="col" class="px-6 py-3 text-nowrap"> Assigned to </th>
                            </tr>
                        </thead>
                        @foreach ($project->bugs as $bug)
                            <tr class="bg-white border-b">
                                <td class="px-6 py-4">
                                    <a href="/bugs/{{ $bug->id }}"
                                        class="font-medium text-teal-600 hover:text-teal-800 transition text-nowrap">
                                        {{ Str::subStr($bug->title, 0, 30) }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-nowrap"> {{ $bug->type === 'Feature' ? '💡' : '🐞' }}
                                    {{ $bug->type }}
                                </td>
                                <td class="px-6 py-4 text-nowrap">
                                    {{ $bug->status === 'Resolved' || $bug->status === 'Completed' ? '✅' : '⌛' }}
                                    {{ $bug->status }} </td>
                                <td class="px-6 py-4 text-nowrap"> {{ $bug->deadline }} </td>
                                <td class="px-6 py-4 text-nowrap"> {{ $bug->qa->name }}</td>
                                <td class="px-6 py-4 text-nowrap"> {{ $bug->developer->name }} </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @else
                <x-error>
                    Currently there is no bug reported in this project
                </x-error>
            @endif
        </div>
    </div>

@endsection
