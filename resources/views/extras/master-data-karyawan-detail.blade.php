@extends('layouts.app')
@section('content')
<style>
    .custom-thumbnail {
        /* border: 2px solid #007bff; Border warna biru */
        border-radius: 10px;      /* Rounded corners */
        padding: 5px;            /* Spasi padding */

        width: auto;
        height: 220px;
        object-fit: cover;
        transition: transform 0.3s; /* Animasi saat hover */
    }

    .custom-thumbnail:hover {
        transform: scale(1.05); /* Membesarkan gambar saat hover */
        cursor: pointer;        /* Mengubah kursor saat hover */
    }
</style>

<div class="container-fluid mt-5">
        <!-- Tab Navigation -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Data Personal</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Data Keluarga</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Berkas</button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="myTabContent">
            <!-- DATA PERSONAL -->
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" value="{{ $dataKaryawan->id_badge }}" 
                                    {{ auth()->check() && auth()->user()->role === 'superadmin' ? '' : 'disabled' }}>
                                    <label for="floatingInput">Badge</label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" value="{{ $dataKaryawan->nama_karyawan }}" 
                                    {{ auth()->check() && auth()->user()->role === 'superadmin' ? '' : 'disabled' }}>
                                    <label for="floatingInput">Nama Karyawan</label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" value="{{ $dataKaryawan->jenis_kelamin }}" 
                                    {{ auth()->check() && auth()->user()->role === 'superadmin' ? '' : 'disabled' }}>
                                    <label for="floatingInput">Jenis Kelamin</label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-floating mb-3">
                                    <!-- <textarea class="form-control mt-3" rows="4" disabled>{{ $dataKaryawan->alamat }}</textarea> -->
                                    <input type="text" class="form-control" id="floatingInput" value="{{ $dataKaryawan->nama_karyawan }}" disabled>
                                    <label for="floatingInput">Alamat</label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" value="{{ $dataKaryawan->nama_karyawan }}" disabled>
                                    <label for="floatingInput">Agama</label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" value="{{ $dataKaryawan->nama_karyawan }}" disabled>
                                    <label for="floatingInput">Tempat lahir</label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-floating mb-3">
                                    <input
                                        type="date"
                                        class="form-control"
                                            value="{{ $dataKaryawan->tanggal_lahir }}"
                                            {{ auth()->check() && auth()->user()->role === 'superadmin' ? '' : 'disabled' }}
                                    >
                                    <label for="floatingInput">Tanggal lahir</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if(auth()->check() && auth()->user()->role === 'superadmin')
                        <div class="card-footer text-end">
                            <button class="btn btn-success">Simpan</button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- DATA KELUARGA -->
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <p class="mt-3">This is the Profile tab content.</p>
                
                <div class="accordion" id="accordionExample">
                    <!-- Accordion Item 1 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Pasangan
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <strong>This is the first item's accordion body.</strong> You can modify it with your own content or style.
                            </div>
                        </div>
                    </div>

                    <!-- Accordion Item 2 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Anak
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <strong>This is the second item's accordion body.</strong> It will remain hidden until the user clicks the toggle button.
                            </div>
                        </div>
                    </div>

                    <!-- Accordion Item 3 -->
                    <!-- <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Accordion Item #3
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <strong>This is the third item's accordion body.</strong> It is hidden by default and will only be shown when expanded.
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>

            <!-- IMAGES FILE -->
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <img src="https://picsum.photos/id/237/200/300" alt="Thumbnail 1" class="img-thumbnail custom-thumbnail">
                            <p class="text-center">Thumbnail 1</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">

                            <img src="https://picsum.photos/id/237/200/300" alt="Thumbnail 1" class="img-thumbnail custom-thumbnail">
                            <p class="text-center">Thumbnail 1</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">

                            <img src="https://picsum.photos/id/237/200/300" alt="Thumbnail 1" class="img-thumbnail custom-thumbnail">
                            <p class="text-center">Thumbnail 1</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
