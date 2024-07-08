@extends('layouts.app')

@section('content')
    <div class="flex justify-center gap-8 items-center">
        <div class="md:w-1/2">
            <div class="bg-white shadow-md rounded-lg overflow-hidden ">
                <div class="py-4 px-6 bg-teal-500 text-white border-b text-lg font-medium">
                    Create a new account
                </div>

                <form method="POST" action="{{ route('register') }}" class="mx-6 my-4">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input id="name" type="text" placeholder="Your name here..."
                            class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-teal-500 focus:ring-teal-500 @error('name') border-red-500 @enderror"
                            name="name" required autofocus>

                        @error('name')
                            <x-formError> {{ $message }} </x-formError>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address
                            <small class="text-gray-600"> - Must be unique</small>
                        </label>
                        <input id="email" type="email" placeholder="Your email here..."
                            class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-teal-500 focus:ring-teal-500 @error('email') border-red-500 @enderror"
                            name="email" required>
                        @error('email')
                            <x-formError> {{ $message }} </x-formError>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="user_type" class="block text-sm font-medium text-gray-700">Who are you?</label>
                        <select name="user_type" id="user_type"
                            class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-teal-500 focus:ring-teal-500 @error('user_type') border-red-500 @enderror">
                            <option value="">Select your role</option>
                            <option value="Manager">Manager</option>
                            <option value="Developer">Developer</option>
                            <option value="QA">QA</option>
                        </select>

                        @error('user_type')
                            <x-formError> {{ $message }} </x-formError>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password
                            <small class="text-gray-600"> - Minimum 8 characters required</small>
                        </label>

                        <input id="password" type="password" placeholder="Your password here..."
                            class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-teal-500 focus:ring-teal-500 @error('password') border-red-500 @enderror"
                            name="password" required>

                        @error('password')
                            <x-formError> {{ $message }} </x-formError>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password-confirm" class="block text-sm font-medium text-gray-700">Confirm
                            Password</label>
                        <input id="password-confirm" type="password" placeholder="Re-enter your password..."
                            class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-teal-500 focus:ring-teal-500"
                            name="password_confirmation" required>
                    </div>

                    <p class="text-gray-600 mb-4 text-sm">Already have an account?
                        <a href="/login" class="text-teal-500 hover:text-teal-600 font-medium">Login </a>
                    </p>
                    <button type="submit"
                        class="px-4 py-2 bg-teal-500 w-full text-white rounded hover:bg-teal-600 focus:outline-none focus:bg-teal-600">
                        Create account
                    </button>

                </form>
            </div>
        </div>
        <img src="/storage/images/register.jpg" alt="Login image not found" class="hidden md:block h-[35rem]">
    </div>
@endsection
