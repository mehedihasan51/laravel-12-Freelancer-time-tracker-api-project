@extends('frontend.app')

@section('title', 'Freelancer Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-6">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-center text-3xl font-bold text-gray-800 mb-6">Welcome Back, Freelancer</h2>

        @if(session('status'))
        <div class="mb-4 text-green-600 text-sm font-semibold">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input id="email" type="email" name="email" required autofocus
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:outline-none"
                    value="{{ old('email') }}">
                @error('email')
                <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password" name="password" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:outline-none">
                @error('password')
                <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-between mb-4">
                <label class="flex items-center text-sm">
                    <input type="checkbox" name="remember" class="mr-2">
                    Remember Me
                </label>

                @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:underline" href="{{ route('password.request') }}">
                    Forgot Password?
                </a>
                @endif
            </div>


        </form>

        <div>
            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition duration-200">
                Login
            </button>
        </div>

        <div class="text-center mt-4 text-sm text-gray-600">
            Don't have an account?
            <a href="" class="text-blue-600 hover:underline">Sign up</a>
        </div>
    </div>
</div>
@endsection