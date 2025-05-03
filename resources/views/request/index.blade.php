@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
    <div class="py-4 z-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form class="w-full mx-auto">
                <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only ">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="search" id="default-search"
                        class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Search..." name="search" required value="{{ request('search') }}" />
                    <button type="submit"
                        class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="max-w-7xl mx-auto mb-4 sm:px-6 lg:px-8">
        <a href="{{ route('request.create') }}"
            class="bg-lime-400 text-white text-xl font-semibold py-1 px-3 rounded-3xl">Tambah Baru</a>
    </div>

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
                        Waktu
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($requests as $request)
                    <tr class="odd:bg-white even:bg-gray-50 border-b border-gray-200">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ $request->id }}
                        </th>
                        <td class="px-6 py-4">
                            @foreach ($request->request_detail as $detail)
                                <li>
                                    {{ $detail->stationery->name ?? 'N/A' }}
                                    ({{ $detail->amount }} pcs)
                                </li>
                            @endforeach
                        </td>
                        <td class="px-6 py-4">
                            {{ $request->employee->name }}
                        </td>
                        <td class="px-6 py-4">
                            @if ($request->status == 'pending')
                                <span class="text-yellow-500">{{ $request->status }}</span>
                            @elseif ($request->status == 'denied')
                                <span class="text-red-500">{{ $request->status }}</span>
                            @else
                                <span class="text-green-500">{{ $request->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if ($request->status == 'pending')
                                <span class="text-yellow-500">{{ $request->submit }}</span>
                            @elseif ($request->status == 'denied')
                                <span class="text-red-500">{{ $request->approved }}</span>
                            @else
                                <span class="text-green-500">{{ $request->approved }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="#" class="font-medium text-blue-600 hover:underline">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
