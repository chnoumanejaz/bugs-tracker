@extends('layouts.app')

@section('content')
    <div class="flex justify-center items-center flex-col p-4 border rounded-lg">
        <h1 class="text-4xl font-bold">
            404 Page not found
        </h1>
        <p class="text-gray-600 mb-8">Sorry the page you are looking for is not found.</p>

        <x-button href="/" variant="default"> Go to home page </x-button>
    </div>    
@endsection