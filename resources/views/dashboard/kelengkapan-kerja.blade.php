@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="container">
        <div class="d-flex justify-content-end align-items-center mb-4">
            <h4 class="me-auto">Kelengkapan Kerja</h4> <!-- Tambahkan kelas 'me-auto' untuk memberi margin ke kanan pada judul -->
            <a href="" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDataBaru">+ Masukan Data Baru</a>
            <a href="" class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#modalDataExcel">+ Masukan Data Excel</a> <!-- Tambahkan kelas 'ms-2' untuk memberi margin kiri pada tombol kedua -->
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
    <div class="modal fade" id="modalDataBaru" tabindex="-1" aria-labelledby="modalDataBaruLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDataBaruLabel">Masukan Data Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="idBadge" class="form-label">ID Badge</label>
                                <input type="text" class="form-control" id="idBadge" placeholder="Masukkan ID Badge">
                            </div>
                            <div class="mb-3">
                                <label for="namaKaryawan" class="form-label">Nama Karyawan</label>
                                <input type="text" class="form-control" id="namaKaryawan" placeholder="Masukkan Nama">
                            </div>
                            <!-- Tambahkan input lainnya sesuai kebutuhan -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal untuk Masukan Data Excel -->
        <div class="modal fade" id="modalDataExcel" tabindex="-1" aria-labelledby="modalDataExcelLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDataExcelLabel">Masukan Data Excel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form action="{{ route('kelengkapan-kerja.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file_excel" class="form-label">Upload File Excel</label>
                            <input type="file" class="form-control" id="file_excel" name="file_excel" accept=".xlsx, .xls">
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-success">Upload</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggle(source) {
            checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = source.checked;
            });
        }
    </script>

@endsection