@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
    <form id="request-form" class="border-2 max-w-3/4 mx-auto p-4 rounded-2xl" action="{{ route('request.store') }}"
        method="POST">
        @csrf
        <input type="hidden" name="items" id="items-data">
        <h1 class="text-3xl">Permintaan Alat Tulis</h1>

        <label class="block mt-3">
            <span class="text-lg ">Nama</span>
            <input type="hidden" name="employee_id" id="employee-id">
            <input type="text" name="name_display" id="name" required autocomplete="off"
                class="block w-full px-4 py-2 mt-1 transition duration-200 ease-in-out border border-gray-300 rounded-lg shadow-sm form-control focus:border-lime-500 focus:ring-lime-500 focus:outline-none focus:shadow-outline" placeholder="Cari dan Pilih dari dropdown">
            <div id="dropdown-name" style="display: none; border: 1px solid #ccc; max-height: 200px; overflow-y: auto;">
            </div>
        </label>

        <div class="flex gap-5">
            <label class="block mt-3 w-full">
                <span class="text-lg ">Alat</span>
                <input type="text" id="stationery" required autocomplete="off"
                    class="block w-full px-4 py-2 mt-1 transition duration-200 ease-in-out border border-gray-300 rounded-lg shadow-sm form-control focus:border-lime-500 focus:ring-lime-500 focus:outline-none focus:shadow-outline" placeholder="Cari dan Pilih dari dropdown">
                <div id="dropdown-stationery"
                    style="display: none; border: 1px solid #ccc; max-height: 200px; overflow-y: auto;">
                </div>
            </label>
            <label class="block mt-3 w-full">
                <span class="text-lg ">Jumlah</span>
                <input type="number" id="amount" required autocomplete="off"
                    class="block w-full px-4 py-2 mt-1 transition duration-200 ease-in-out border border-gray-300 rounded-lg shadow-sm form-control focus:border-lime-500 focus:ring-lime-500 focus:outline-none focus:shadow-outline" placeholder="Masukkan Jumlah">
            </label>
            <div class="flex items-end">
                <button class="bg-lime-400 text-white text-4xl p-3 rounded-2xl" type="button" id="add-button">
                    <img src="{{ asset('images/plus.svg') }}" alt="" class="w-18">
                </button>
            </div>
        </div>

        <label class="block mt-3">
            <span class="text-lg ">Daftar Permintaan</span>
            <div class="block w-full px-4 py-2 mt-1 min-h-20 transition duration-200 ease-in-out border border-gray-300 rounded-lg shadow-sm"
                id="selectedStationery">

                <ul id="list-items" class="list-disc ml-5">
                    <!-- Dynamic list will be appended here -->
                </ul>
            </div>
        </label>

        <label class="block mt-3">
            <span class="text-lg ">Keterangan</span>
            <textarea id="description" name="description" rows="4" cols="50" placeholder=""
                required class="block w-full border mt-1 border-gray-300 rounded-lg shadow-sm form-control"></textarea>
        </label>

        <div class="mt-6 border text-xl font-semibold text-white flex justify-end gap-3">
            <button class="bg-[#ED4545] py-1 px-6 rounded-3xl">BATAL</button>
            <button class="bg-yellow-300 py-1 px-6 rounded-3xl" type="reset">RESET</button>
            <button class="bg-lime-400 py-1 px-6 rounded-3xl" type="submit" id="submit-button">TAMBAH</button>
        </div>

    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#name').on('input', function() {
                let query = $(this).val();

                if (query.length > 1) {
                    $.ajax({
                        url: '{{ route('getName') }}',
                        method: 'GET',
                        data: {
                            query: query
                        },
                        success: function(data) {
                            $('#dropdown-name').empty();
                            availableEmployees = {}; 

                            if (data.length > 0) {
                                data.forEach(function(item) {
                                    $('#dropdown-name').append(
                                        `<div  class="choose-name p-2 hover:bg-lime-50" data-value="${item.name}" data-id="${item.id}">${item.name}</div>`
                                    ); // Ganti column_name sesuai kebutuhan  
                                });
                                $('#dropdown-name').show();
                            } else {
                                $('#dropdown-name').hide();
                            }
                        }
                    });
                } else {
                    $('#dropdown-name').hide();
                }
            });

            // Handle item selection from dropdown  
            $(document).on('click', '.choose-name', function() {
                let selectedValue = $(this).data('value');
                let selectedId = $(this).data('id');
                $('#name').val(selectedValue); // Update input value  
                $('#employee-id').val(selectedId); // Menyimpan ID di hidden input  
                $('#dropdown-name').hide(); // Hide the dropdown  
            });

            // Close dropdown-name if clicked outside  
            $(document).click(function(e) {
                if (!$(e.target).closest('#name').length) {
                    $('#dropdown-name').hide();
                }
            });

            let availableStock = {};
            $('#stationery').on('input', function() {
                let query = $(this).val();

                if (query.length > 1) {
                    $.ajax({
                        url: '{{ route('getStationery') }}',
                        method: 'GET',
                        data: {
                            query: query
                        },
                        success: function(data) {
                            $('#dropdown-stationery').empty();
                            availableStock = {};

                            if (data.length > 0) {
                                data.forEach(function(item) {
                                    $('#dropdown-stationery').append(
                                        `<div  class="choose-stationery p-2 hover:bg-lime-50" data-value="${item.name}" data-stock="${item.stock}">${item.name}</div>`
                                    ); // Ganti column_name sesuai kebutuhan  
                                    availableStock[item.name] = item.stock;
                                });
                                $('#dropdown-stationery').show();
                            } else {
                                $('#dropdown-stationery').hide();
                            }
                        }
                    });
                } else {
                    $('#dropdown-stationery').hide();
                }
            });

            // Handle item selection from dropdown  
            $(document).on('click', '.choose-stationery', function() {
                let selectedValue = $(this).data('value');
                let selectedStock = parseInt($(this).data('stock'));
                $('#stationery').val(selectedValue)
                    .data('stock', selectedStock);
                $('#dropdown-stationery').hide(); // Hide the dropdown  
            });

            // Close dropdown-stationery if clicked outside  
            $(document).click(function(e) {
                if (!$(e.target).closest('#stationery').length) {
                    $('#dropdown-stationery').hide();
                }
            });

            $('#add-button').click(function() {
                var stationery = $('#stationery').val().trim();
                var amount = parseInt($('#amount').val().trim());

                if (stationery === '' || isNaN(amount)) {
                    alert('Please fill in both fields.');
                    return;
                }

                // Validate the stationery exists in available stock  
                if (!availableStock.hasOwnProperty(stationery)) {
                    alert('Silakan pilih alat tulis dari daftar yang tersedia.');
                    return;
                }

                var stock = availableStock[stationery];
                if (amount > stock) {
                    alert(`Jumlah melebihi stok yang tersedia (${stock}).`);
                    return;
                }

                if (amount <= 0) {
                    alert('Jumlah harus lebih besar dari 0.');
                    return;
                }

                // Cari apakah item sudah ada di daftar  
                let existingItem = null;
                $('#list-items li').each(function() {
                    if ($(this).find('span').text().startsWith(stationery + ' - Jumlah: ')) {
                        existingItem = $(this);
                        return false; // break each  
                    }
                });

                if (existingItem) {
                    // Update jumlah yang ada  
                    existingItem.find('span').text(stationery + ' - Jumlah: ' + amount);
                } else {
                    // Tambahkan item baru  
                    var listItem = $('<li class="flex justify-between items-center my-2"></li>');
                    listItem.append($('<span></span>').text(stationery + ' - Jumlah: ' + amount));

                    // Buat tombol hapus  
                    var deleteButton = $('<button class="text-red-500 ml-2">Hapus</button>');
                    listItem.append(deleteButton);
                    $('#list-items').append(listItem);
                }

                // Kosongkan input  
                $('#stationery').val('').removeData('stock'); 
                $('#amount').val('');
                selectedStationery = null;
            });

            // Hindari pengikatan ganda dengan delegated event handling  
            $('#list-items').on('click', 'button', function(e) {
                e.preventDefault(); // Menghindari aksi default jika ada  
                $(this).closest('li').remove(); // Menghapus item daftar terdekat  
            });

            $('#submit-button').click(function(e) {
                e.preventDefault();

                // Validasi form  
                if ($('#name').val().trim() === '') {
                    alert('Nama harus diisi');
                    return;
                }

                if ($('#list-items li').length === 0) {
                    alert('Daftar permintaan tidak boleh kosong');
                    return;
                }

                // Siapkan data items  
                let items = [];
                $('#list-items li').each(function() {
                    let text = $(this).find('span').text();
                    let [stationery, amount] = text.split(' - Jumlah: ');
                    items.push({
                        stationery: stationery.trim(),
                        amount: parseInt(amount)
                    });
                });

                // Simpan ke hidden input  
                $('#items-data').val(JSON.stringify(items));

                // Submit form  
                $('form').submit();
            });

        });
    </script>
@endsection
