@extends('layouts.master')

@section('title', 'Register')

@section('content')
    <div class="flex items-center justify-center p-6">
        <div class="w-full max-w-2xl px-6 py-12 bg-white rounded-md shadow-2xl">
            <form class="space-y-3" action="{{ route('register.store') }}" method="POST">
                @csrf
                <p class="text-center text-xl sm:text-2xl font-semibold">Buat Akun</p>

                <div class="">
                    <label class="block">
                        <span class="text-lg">Username</span>
                        <input type="text" name="username" value="{{ old('username') }}" required placeholder="Username"
                            class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-lg shadow-sm form-control focus:border-[#0062DD] focus:ring-[#0062DD] focus:outline-none focus:shadow-outline">
                        @error('username')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </label>
                </div>

                <div class="">
                    <label class="block">
                        <span class="text-lg">Role</span>
                        <select name="role" id="role" name="role" required class="block rounded-lg">
                            <option value="Staff Gudang">Staff Gudang</option>
                            <option value="Ketua Divisi">Ketua Divisi</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </label>
                </div>

                <div class="">
                    <label class="block">
                        <span class="text-lg">Divisi</span>
                        <select name="divisi" id="divisi" name="divisi" required class="block rounded-lg">
                            @foreach ($divisi as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('divisi')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </label>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <label class="block">
                        <span class="text-lg">Password</span>
                        <input type="password" name="password" required placeholder="Password"
                            class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-lg shadow-sm form-control focus:border-[#0062DD] focus:ring-[#0062DD] focus:outline-none focus:shadow-outline">
                        @error('password')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </label>
                </div>

                <button type="submit"
                    class="w-full px-4 py-2 mt-3 text-lg text-center text-white bg-[#0062DD] rounded-md font-semibold hover:bg-[#3f88e1]">
                    Buat Akun
                </button>

        </div>
    </div>
@endsection
