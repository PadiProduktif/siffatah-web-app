@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <!-- Stats -->
    <!-- <div class="row g-3 mb-4">
        <div class="col-md">
            <div class="stat bg-light text-center p-3 rounded shadow">
                <h2 class="text-success">23</h2>
                <p>Berkas Baru</p>
            </div>
        </div>
        <div class="col-md">
            <div class="stat bg-light text-center p-3 rounded shadow">
                <h2 class="text-success">43</h2>
                <p>Pengajuan Verifikasi</p>
            </div>
        </div>
        <div class="col-md">
            <div class="stat bg-light text-center p-3 rounded shadow">
                <h2 class="text-success">150</h2>
                <p>Telah Diverifikasi</p>
            </div>
        </div>
    </div> -->

    <!-- Table -->
    <div class="table-container bg-white p-3 rounded shadow">
        <h2>MASTER DATA KARYAWAN</h2>
        <div class="table-responsive">
            <table class="table">
                <thead class="table-light">
                    <tr>
                        <th>Aksi</th>
                        <th>Nama</th>
                        <th>ID Badge</th>
                        <th>Deskripsi</th>
                        <th>Nomor Surat</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><a href="#" class="btn btn-link">Lihat Detail</a></td>
                        <td>Ronny Khoerudin</td>
                        <td>3022126</td>
                        <td>Pengobatan RS PON</td>
                        <td>123/CJ/RS/2024</td>
                        <td>09 Agustus 2024</td>
                        <td><span class="status new">Berkas Baru</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
