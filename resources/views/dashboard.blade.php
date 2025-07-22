@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
<section class="flex gap-2 justify-center mb-8 w-1/2 m-auto">
    <div class="border-2 flex flex-col p-4 gap-3 w-1/2">
        <h1 class="text-3xl">Permintaan <br>Menunggu Persetujuan</h1>
        <h1 class="place-self-end text-7xl">10</h1>
    </div>
    <div class="border-2 flex flex-col p-4 gap-3 w-1/2">
        <h1 class="text-3xl">Permintaan Disetujui <br>Minggu Ini</h1>
        <h1 class="place-self-end text-7xl">10</h1>
    </div>
</section>



<div class="relative overflow-x-auto shadow-md sm:rounded-lg w-3/4 m-auto">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
        <thead class="text-xsuppercase bg-[#55648c] text-white">
            <tr>
                <th scope="col" class="px-6 py-3">
                    ID
                </th>
                <th scope="col" class="px-6 py-3">
                    Permintaan
                </th>
                <th scope="col" class="px-6 py-3">
                    Nama
                </th>
                <th scope="col" class="px-6 py-3">
                    Status
                </th>
                <th scope="col" class="px-6 py-3">
                    Keterangan
                </th>
            </tr>
        </thead>
        <tbody>
            {{-- <tr class="odd:bg-white even:bg-gray-50 border-b border-gray-200">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                    Apple MacBook Pro 17"
                </th>
                <td class="px-6 py-4">
                    Silver
                </td>
                <td class="px-6 py-4">
                    Laptop
                </td>
                <td class="px-6 py-4">
                    $2999
                </td>
                <td class="px-6 py-4">
                    <a href="#" class="font-medium text-blue-600 hover:underline">Edit</a>
                </td>
            </tr>
            <tr class="odd:bg-white even:bg-gray-50 border-b border-gray-200">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                    Microsoft Surface Pro
                </th>
                <td class="px-6 py-4">
                    White
                </td>
                <td class="px-6 py-4">
                    Laptop PC
                </td>
                <td class="px-6 py-4">
                    $1999
                </td>
                <td class="px-6 py-4">
                    <a href="#" class="font-medium text-blue-600 hover:underline">Edit</a>
                </td>
            </tr> --}}
        </tbody>
    </table>
</div>

@endsection