@extends('layouts.app')
@section('title', 'Pengaturan Profil')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<div class="card">
    <div class="card-header">
        <h2 class="my-4">Set User List</h2>
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
        
            <form id="karyawanForm" action="{{ route('set_user_submit') }}" method="POST">
                @csrf
            
                <!-- Label Nama Karyawan -->
                <label for="nama_karyawan">Nama Karyawan</label>
            
                <!-- Custom Dropdown -->
                <div class="custom-dropdown">
                    <div class="dropdown-header" onclick="toggleDropdown()">Pilih Nama Karyawan</div>
                    
                    <!-- Input Pencarian -->
                    <input type="text" id="searchInput" class="dropdown-search" placeholder="Cari karyawan..." onkeyup="filterKaryawan()">
                    
                    <!-- Daftar Karyawan -->
                    <div class="dropdown-list" id="dropdownList">
                        @foreach ($karyawan as $item)
                        <label class="dropdown-item">
                            <input type="checkbox" name="nama_karyawan[]" value="{{ $item->id_badge }}" onclick="updateSelected()">
                            {{ $item->nama_karyawan }} - {{ $item->id_badge }}
                        </label>
                        @endforeach
                    </div>
                </div>
            
                <!-- Hidden Input untuk Menyimpan Data Terpilih -->
                <input type="hidden" id="selectedKaryawan" name="selected_karyawan">
            
                <button type="submit" class="btn btn-primary mt-3">Simpan</button>
            </form>
            
        </div>
    </div>
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
        document.getElementById('selectedKaryawan').value = JSON.stringify(selected);

        // Tampilkan data yang dipilih di header dropdown
        let header = document.querySelector('.dropdown-header');
        header.innerText = selected.length > 0 ? selected.join(", ") : "Pilih Nama Karyawan";
    }

    function filterKaryawan() {
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
</script>

@endpush