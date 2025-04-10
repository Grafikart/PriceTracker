@extends('layout', ['header' => false])

@section('main')

    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-slate-800 rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-normal">{{ __('Login') }}</h1>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-6">
                    <label for="email" class="block text-light text-sm font-bold mb-2">{{ __('Email Address') }}</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="your@email.com"
                    >
                    @error('email')
                    <div class="mt-2 text-sm text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-light text-sm font-bold mb-2">{{ __('Password') }}</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="••••••••"
                    >
                    @error('password')
                    <div class="mt-2 text-sm text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-6">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-colors duration-200">
                        {{ __('Signin') }}
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection
