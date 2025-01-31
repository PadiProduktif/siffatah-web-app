@extends('layouts.app')
@section('title', 'Pengaturan Profil')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<div class="card">
    <div class="card-header">
        <h2 class="my-4">Set Cost Center List</h2>
    </div>
    <style>
        /* Style untuk Dropdown */
    .custom-dropdown {
        width: 100%;
        position: relative;
        border: 1px solid #ccc;
        background-color: white;
        cursor: pointer;
        border-radius: 5px;
        padding: 10px;
    }

    .dropdown-header {
        padding: 10px;
        background: white;
        border-radius: 5px;
        cursor: pointer;
    }

    .dropdown-list {
        display: none;
        position: absolute;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        background: white;
        border: 1px solid #ccc;
        border-top: none;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Input Pencarian */
    .dropdown-search {
        width: 100%;
        padding: 8px;
        border: none;
        border-bottom: 1px solid #ccc;
        outline: none;
    }

    /* List Karyawan */
    .dropdown-item {
        display: flex;
        align-items: center;
        padding: 8px 10px;
        cursor: pointer;
    }

    .dropdown-item:hover {
        background-color: #f1f1f1;
    }

    .dropdown-item input {
        margin-right: 10px;
    }

    /* Tampilkan dropdown saat aktif */
    .custom-dropdown.active .dropdown-list {
        display: block;
    }

    </style>
    <div class="card-body">
        <form id="costCenterForm" action="{{ route('set_user_submit') }}" method="POST">
            @csrf
    
            <!-- Label Cost Center -->
            <label for="cost_center">Cost Center</label>
    
            <!-- Custom Dropdown -->
            <div class="custom-dropdown">
                <div class="dropdown-header" onclick="toggleDropdown()">Pilih Cost Center</div>
    
                <!-- Input Pencarian -->
                <input type="text" id="searchInput" class="dropdown-search" placeholder="Cari cost center..." onkeyup="filterCostCenter()">
    
                <!-- Daftar Cost Center -->
                <div class="dropdown-list" id="dropdownList">
                    @foreach ($costCenters as $item)
                    <label class="dropdown-item">
                        <input type="checkbox" name="cost_center[]" value="{{ $item->cost_center }}" onclick="updateSelected()">
                        {{ $item->nama_bagian }} - {{ $item->cost_center }}
                    </label>
                    @endforeach
                </div>
            </div>
    
            <!-- Hidden Input untuk Menyimpan Data Terpilih -->
            <input type="hidden" id="selectedCostCenters" name="selected_cost_center">
    
            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        </form>
    
        <h4 style="margin-top: 20px;">Daftar Cost Center Terdaftar</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Bagian</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="costCenterList">
                @foreach ($registeredCostCenters as $costCenter)
                    <tr id="row-{{ $costCenter->cost_center }}">
                        <td>{{ $costCenter->nama_bagian }}</td>
                        <td>
                            <button class="btn btn-danger btn-sm btn-hapus" data-id="{{ $costCenter->cost_center }}">
                                Hapus
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
@push('scripts')
<script>
    function toggleDropdown() {
        document.querySelector('.custom-dropdown').classList.toggle('active');
    }

    function updateSelected() {
        let selected = [];
        document.querySelectorAll('.dropdown-item input:checked').forEach((checkbox) => {
            selected.push(checkbox.value);
        });

        // Update hidden input agar data bisa dikirim ke controller
        document.getElementById('selectedCostCenters').value = JSON.stringify(selected);

        // Tampilkan data yang dipilih di header dropdown
        let header = document.querySelector('.dropdown-header');
        header.innerText = selected.length > 0 ? selected.join(", ") : "Pilih Cost Center";
    }

    function filterCostCenter() {
        let input = document.getElementById("searchInput").value.toLowerCase();
        let items = document.querySelectorAll(".dropdown-item");

        items.forEach(function(item) {
            let text = item.innerText.toLowerCase();
            if (text.includes(input)) {
                item.style.display = "flex";
            } else {
                item.style.display = "none";
            }
        });
    }

    // Tutup dropdown jika klik di luar
    document.addEventListener("click", function(event) {
        let dropdown = document.querySelector('.custom-dropdown');
        if (!dropdown.contains(event.target)) {
            dropdown.classList.remove("active");
        }
    });

    $(document).ready(function () {
        // Simpan daftar cost center
        $('#btnSimpan').click(function () {
            let selectedCostCenters = $('#costCenterSelect').val();
            if (!selectedCostCenters || selectedCostCenters.length === 0) {
                alert('Pilih setidaknya satu cost center!');
                return;
            }

            $.ajax({
                url: '/admin/save-user-list',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    selected_cost_center: JSON.stringify(selectedCostCenters)
                },
                success: function (response) {
                    alert(response.message);
                    location.reload(); // Reload halaman untuk update list
                },
                error: function () {
                    alert('Terjadi kesalahan saat menyimpan.');
                }
            });
        });

        // Hapus cost center dari daftar
        $(document).on('click', '.btn-hapus', function () {
            let costCenterId = $(this).data('id');

            $.ajax({
                url: '/admin/remove-user',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    cost_center_id: costCenterId
                },
                success: function (response) {
                    alert(response.message);
                    $('#row-' + costCenterId).remove(); // Hapus row tanpa reload
                },
                error: function () {
                    alert('Gagal menghapus cost center.');
                }
            });
        });
    });
</script>

@endpush