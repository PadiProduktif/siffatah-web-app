@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Kelengkapan Kerja</h4>
            <a href="" class="btn btn-primary">+ Masukan Data Baru</a>
        </div>

        <div class="mb-3">
            <form method="GET" action="">
                <div class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Pencarian" value="">
                    <button type="submit" class="btn btn-secondary">Cari</button>
                </div>
            </form>
        </div>
        {{-- {{ auth()->user()->fullname }} --}}
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col"><input type="checkbox" onclick="toggle(this)"></th>
                    <th scope="col">ID Badge</th>
                    <th scope="col">Nama Karyawan</th>
                    <th scope="col">Cost Center</th>
                    <th scope="col">Unit Kerja</th>
                    <th scope="col">Posisi Jabatan</th>
                    <th scope="col">Grade</th>
                    <th scope="col">Jenis Pakaian</th>
                    <th scope="col">Sepatu Kantor</th>
                    <th scope="col">Sepatu Safety</th>
                    <th scope="col">Wearpack Cover All</th>
                    <th scope="col">Jaket Shift</th>
                    <th scope="col">Seragam Olahraga</th>
                    <th scope="col">Jaket Casual</th>
                    <th scope="col">Seragam Dinas Harian</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kelengkapan as $item)
                <tr>
                    <td><input type="checkbox" name="selected[]" value=""></td>
                    <td>{{ $item->id_badge }}</td>
                    <td>{{ $item->nama_karyawan }}</td>
                    <td>{{ $item->cost_center }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <a href="" class="btn btn-sm btn-warning">Edit</a>
                        <form action="" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="16" class="text-center">Tidak ada data</td>
                </tr>
                
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center">
            <span>Menampilkan  dari Total Data</span>
            
        </div>
    </div>

    <script>
        function toggle(source) {
            checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = source.checked;
            });
        }
    </script>

@endsection