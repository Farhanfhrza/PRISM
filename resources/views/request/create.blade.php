@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
    <form class="border-2 max-w-3/4 mx-auto p-4 rounded-2xl" action="{{ route('stationery.store') }}" method="POST">
        @csrf
        <h1 class="text-3xl">Alat Tulis</h1>
        <label class="block mt-3">
            <span class="text-lg ">Nama</span>
            <input type="text" name="name" required autocomplete="off"
                class="block w-full px-4 py-2 mt-1 transition duration-200 ease-in-out border border-gray-300 rounded-lg shadow-sm form-control focus:border-lime-500 focus:ring-lime-500 focus:outline-none focus:shadow-outline">
        </label>
        <div class="flex">
            <label class="block mt-3 w-full">
                <span class="text-lg ">Kategori</span>
                <input type="text" name="category" required autocomplete="off"
                    class="block w-full px-4 py-2 mt-1 transition duration-200 ease-in-out border border-gray-300 rounded-lg shadow-sm form-control focus:border-lime-500 focus:ring-lime-500 focus:outline-none focus:shadow-outline">
            </label>
            <label class="block mt-3 w-full">
                <span class="text-lg ">Kategori</span>
                <input type="text" name="category" required autocomplete="off"
                    class="block w-full px-4 py-2 mt-1 transition duration-200 ease-in-out border border-gray-300 rounded-lg shadow-sm form-control focus:border-lime-500 focus:ring-lime-500 focus:outline-none focus:shadow-outline">
            </label>

        </div>
        <label class="block mt-3">
            <span class="text-lg ">Jumlah</span>
            <input type="number" name="stock" required autocomplete="off"
                class="block w-full px-4 py-2 mt-1 transition duration-200 ease-in-out border border-gray-300 rounded-lg shadow-sm form-control focus:border-lime-500 focus:ring-lime-500 focus:outline-none focus:shadow-outline">
        </label>
        <label class="block mt-3">
            <span class="text-lg ">Deskripsi</span>
            <textarea id="description" name="description" rows="4" cols="50" placeholder="Enter your description here..." required
            class="block w-full border mt-1 border-gray-300 rounded-lg shadow-sm form-control"></textarea>
        </label>
        <div class="mt-6 border text-xl font-semibold text-white flex justify-end gap-3">
            <button class="bg-[#ED4545] py-1 px-6 rounded-3xl">BATAL</button>
            <button class="bg-yellow-300 py-1 px-6 rounded-3xl" type="reset">RESET</button>
            <button class="bg-lime-400 py-1 px-6 rounded-3xl" type="submit">TAMBAH</button>
        </div>
    </form>
@endsection