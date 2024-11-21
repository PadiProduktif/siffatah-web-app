@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

    <!-- Stats -->
    <div class="row g-3 mb-4">
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
    </div>
    
@endsection
