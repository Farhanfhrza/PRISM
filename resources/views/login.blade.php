@extends('layouts.master')

@section('title', 'Login')

@section('content')
<div class="flex flex-col items-center justify-center font-poppins">
    @if (session()->has('success'))
        <div>{{ session('success') }}</div>
    @endif
    @if (session()->has('loginError'))
        <div>{{ session('loginError') }}</div>
    @endif
    <div class="w-full max-w-2xl px-6 py-12 bg-white rounded-md shadow-2xl">
        <div class="flex flex-col items-center">
            <span class="text-2xl font-semibold ">Selamat Datang !</span>
            <span class="text-2xl text-center font-semibold ">Silahkan masuk untuk melanjutkan ke PRISM</span>
        </div>
        <form class="mt-10" action="{{ route('login.in') }}" method="POST">
            @csrf
            <label class="block">
                <span class="text-lg ">Email</span>
                <input type="email" name="email" autofocus required value="{{ old('email') }}"
                    class="block w-full px-4 py-2 mt-1 transition duration-200 ease-in-out border border-gray-300 rounded-lg shadow-sm form-control focus:border-lime-500 focus:ring-lime-500 focus:outline-none focus:shadow-outline">
                <div class="invalid-feedback">
                </div>
                @error('name')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </label>

            <label class="block mt-3">
                <span class="text-lg ">Password</span>
                <input type="password" name="password" required
                    class="block w-full px-4 py-2 mt-1 transition duration-200 ease-in-out border border-gray-300 rounded-lg shadow-sm form-control focus:border-lime-500 focus:ring-lime-500 focus:outline-none focus:shadow-outline">
            </label>

            <button
                class="w-full px-4 py-2 mt-6 text-lg text-center text-white bg-lime-500 rounded-md font-semibold hover:bg-lime-600">
                Masuk
            </button>
        </form>
    </div>
</div>
@endsection