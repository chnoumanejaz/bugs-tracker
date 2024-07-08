@extends('layouts.app')

@section('content')
    <div class="flex justify-center gap-8 items-center">
        <img src="/storage/images/login.jpg" alt="Login image not found" class="hidden md:block h-[35rem]">
        <div class="md:w-1/2">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="py-4 px-6 bg-teal-500 text-white border-b text-lg font-medium">
                    Login to your account
                </div>

                <form method="POST" action="{{ route('login') }}" class="mx-6 my-4">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email Address
                        </label>
                        <input id="email" type="email" placeholder="Your email address here..."
                            class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-teal-500 focus:ring-teal-500 @error('email') border-red-500 @enderror"
                            name="email" required autofocus>
                        @error('email')
                            <x-formError> {{ $message }} </x-formError>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
                        <input id="password" type="password" placeholder="Your password here..."
                            class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-teal-500 focus:ring-teal-500 @error('password') border-red-500 @enderror"
                            name="password" required>

                        @error('password')
                            <x-formError> {{ $message }} </x-formError>
                        @enderror
                    </div>

                    <p class="text-gray-600 mb-4 text-sm">Don&apos;t have an account?
                        <a href="/register" class="text-teal-500 hover:text-teal-600 font-medium">create a new
                            account</a>
                    </p>
                    <button type="submit"
                        class="px-4 py-2 bg-teal-500 w-full text-white rounded hover:bg-teal-600 focus:outline-none focus:bg-teal-600">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
