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
                        <div class="col-lg-1">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput" value="{{ $dataKaryawan->id_badge }}" 
                                {{ auth()->check() && auth()->user()->role === 'superadmin' ? '' : 'disabled' }}>
                                <label for="floatingInput">Badge</label>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput" value="{{ $dataKaryawan->gelar_depan }}" 
                                {{ auth()->check() && auth()->user()->role === 'superadmin' ? '' : 'disabled' }}>
                                <label for="floatingInput">Gelar depan</label>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput" value="{{ $dataKaryawan->nama_karyawan }}" 
                                {{ auth()->check() && auth()->user()->role === 'superadmin' ? '' : 'disabled' }}>
                                <label for="floatingInput">Nama Karyawan</label>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput" value="{{ $dataKaryawan->gelar_belakang }}" 
                                {{ auth()->check() && auth()->user()->role === 'superadmin' ? '' : 'disabled' }}>
                                <label for="floatingInput">Gelar belakang</label>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput" value="{{ $dataKaryawan->jenis_kelamin }}" 
                                {{ auth()->check() && auth()->user()->role === 'superadmin' ? '' : 'disabled' }}>
                                <label for="floatingInput">Jenis Kelamin</label>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="agama" name="agama" required>
                                    <option value="Islam" {{ $dataKaryawan->agama == 'Islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="Kristen" {{ $dataKaryawan->agama == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                    <option value="Katolik" {{ $dataKaryawan->agama == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                    <option value="Hindu" {{ $dataKaryawan->agama == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Buddha" {{ $dataKaryawan->agama == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                    <option value="Konghucu" {{ $dataKaryawan->agama == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                </select>
                                <label for="agama" class="form-label">Agama</label>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput" value="{{ $dataKaryawan->tempat_lahir }}"  
                                {{ auth()->check() && auth()->user()->role === 'superadmin' ? '' : 'disabled' }}>
                                <label for="floatingInput">Tempat lahir</label>
                            </div>
                        </div>
                        <div class="col-lg-3">
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
                        
                        <div class="col-lg-12">
                            <div class="form-floating mb-3">
                                <!-- <textarea class="form-control mt-3" rows="4" disabled>{{ $dataKaryawan->alamat }}</textarea> -->
                                <input type="text" class="form-control" id="floatingInput" value="{{ $dataKaryawan->alamat }}"   
                                {{ auth()->check() && auth()->user()->role === 'superadmin' ? '' : 'disabled' }}>
                                <label for="floatingInput">Alamat</label>
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
            <div class="accordion" id="accordionExample">
                
                <button type="button" class="btn btn-success w-100 mt-2 mb-4" data-bs-toggle="modal" data-bs-target="#addKaryawanModal">
                    <i class="bi bi-plus-lg"></i> Tambah Anggota Baru
                </button>

                <div class="accordion-item mb-2">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Pasangan
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="row">
                                {{-- Menampilkan Pasangan --}}
                                @foreach($dataKaryawan['keluarga']['pasangan'] as $pasangan)
                                    <div class="col-lg-4 col-md-6 mb-4">
                                        <div class="card shadow-sm">
                                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                                {{ $pasangan['hubungan_keluarga'] }}
                                                <button type="button" class="btn-close btn-close-white" aria-label="Close" onclick="handleDelete('{{ $pasangan['id_non_karyawan'] }}')"></button>
                                            </div>
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $pasangan['nama'] }}</h5>
                                                <p class="card-text">
                                                    <strong>NIK:</strong> {{ $pasangan['nik'] }}<br>
                                                    <strong>Tempat, tanggal lahir:</strong> {{ $pasangan['tempat_lahir'] }}, {{ $pasangan['tanggal_lahir'] }}<br>
                                                    <strong>Alamat:</strong> {{ $pasangan['alamat'] }}<br>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Anak
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            {{-- Menampilkan Anak --}}
                            <div class="row">

                                @foreach($dataKaryawan['keluarga']['anak'] as $key => $anak)
                                    <div class="col-lg-4 col-md-6 mb-4">
                                        <div class="card shadow-sm">
                                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                                {{  $anak['hubungan_keluarga'] }} ke-{{ $key+1 }}
                                                <button type="button" class="btn-close btn-close-white" aria-label="Close" onclick="handleDelete('{{ $anak['id_non_karyawan'] }}')"></button>
                                            </div>
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $anak['nama'] }}</h5>
                                                <p class="card-text">
                                                    <strong>NIK:</strong> {{ $anak['nik'] }}<br>
                                                    <strong>Tempat, tanggal lahir:</strong> {{ $anak['tempat_lahir'] }}, {{ $anak['tanggal_lahir'] }}<br>
                                                    <strong>Alamat:</strong> {{ $anak['alamat'] }}<br>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- IMAGES FILE -->
        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm mt-2">
                        <div class="card-body">
                            <div class="row">
                                @foreach($dataKaryawan['files'] as $value)
                                    <div class="col-md-4">
                                        <div class="card">
                                            <img src="{{ $value['url'] }}" alt="Thumbnail 1" class="img-thumbnail custom-thumbnail">
                                            <p class="text-center">{{ $value['name'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <form id="upload-form" enctype="multipart/form-data">
                                        
                                        <div id="berkas-master-data-dropzone" class="dropzone">
                                            <div class="dz-message">
                                                Drag & Drop your files here or click to upload
                                            </div>
                                        </div>
                                        {{-- <div 
                                            id="drop-zone" 
                                            class="border border-primary rounded d-flex flex-column justify-content-center align-items-center p-5 text-center"
                                            style="min-height: 200px; cursor: pointer;"
                                        >
                                            <p class="mb-2">Drag & Drop your image here</p>
                                            <p class="text-muted">or click to browse</p>
                                            <input type="file" id="file-input" name="image" class="d-none" accept="image/*">
                                            <img id="preview" src="#" alt="Preview" class="img-fluid d-none mt-3" style="max-height: 150px;">
                                        </div> --}}
                                        <button type="submit" class="btn btn-primary mt-3 w-100">Upload</button>
                                    </form>

                                    <div id="message" class="mt-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>


<!-- MODAL ADD -->
<div class="modal fade" id="addKaryawanModal"  aria-labelledby="addKaryawanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addKaryawanModalLabel">Tambah Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/admin/master_data_non_karyawan/tambah" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4 d-none">
                            <label for="nama" class="form-label">badge parent</label>
                            <input type="text" class="form-control" id="nama" name="badge_parent" value="{{ $dataKaryawan['id_badge'] }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="tempat-lahir" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" id="tempat-lahir" name="tempat_lahir" required>
                        </div>
                        <div class="col-md-4">
                            <label for="tanggal-lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal-lahir" name="tanggal_lahir" required>
                        </div>

                        
                        <div class="col-md-4">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="text" class="form-control" id="nik" name="nik" required>
                        </div>
                        <div class="col-md-4">
                            <label for="pekerjaan" class="form-label">Pekerjaan</label>
                            <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" required>
                        </div>

                        <div class="col-md-4">
                            <label for="hubungan" class="form-label">Hubungan Keluarga</label>
                            <select class="form-select" id="hubungan" name="hubungan_keluarga" required>
                                <option value="suami">Suami</option>
                                <option value="istri">Istri</option>
                                <option value="anak">anak</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="pendidikan" class="form-label">Jenjang Pendidikan</label>
                            <select class="form-select" id="pendidikan" name="pendidikan" required>
                                <option value="SD">SD</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA">SMA</option>
                                <option value="D3">D3</option>
                                <option value="S1">S1</option>
                                <option value="S2">S2</option>
                                <option value="S3">S3</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="agama" class="form-label">Agama</label>
                            <select class="form-select" id="agama" name="agama" required>
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Konghucu">Konghucu</option>
                            </select>
                        </div>


                        <div class="col-12">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                        </div>
                        
                        <div class="col-md-12">
                            <div id="attachmentDropzone" class="dropzone">
                                <div class="dz-message">Drag & Drop your files here or click to upload</div>
                            </div>
                        </div>          
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Hidden form for deletion -->
<form id="deleteForm" action="" method="GET" style="display: none;">
    @csrf
</form>
@endsection

<script>

    function handleDelete(id) {
        console.log("ID to delete:", id); // Debugging line to check the ID
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak akan dapat mengembalikan data ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Set the action attribute of the form to the delete URL
                const form = document.getElementById('deleteForm');
                form.action = `/admin/master_data_non_karyawan/delete/${id}`;
                form.submit();
            }
        });
    }
    $(document).ready(function () {
        
        // Handle form submission
        $('#upload-form').on('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            $.ajax({
                url: '/upload',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    messageDiv.html(
                        `<div class="alert alert-success">File uploaded successfully! 
                            <a href="/storage/${response.path}" target="_blank">View File</a>
                        </div>`
                    );
                },
                error: function (xhr) {
                    const error = xhr.responseJSON.errors?.image?.[0] || 'File upload failed!';
                    messageDiv.html(`<div class="alert alert-danger">${error}</div>`);
                },
            });
        });

        let removedFiles = []; // Array untuk menyimpan file yang dihapus
        let uploadedFiles = []; // Menyimpan nama file yang sudah diupload ke server

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        if (document.querySelector('#attachmentDropzone')) {
            const attachmentDropzone = new Dropzone("#attachmentDropzone", {
                url: "/pengajuan-klaim-pengobatan/upload-temp", // Endpoint sementara untuk upload
                paramName: "file",
                headers: {
                    'X-CSRF-TOKEN': token
                },
                maxFiles: 5,
                maxFilesize: 5, // 5MB
                acceptedFiles: "image/*,.pdf,.doc,.docx,.xls,.xlsx",
                addRemoveLinks: true,
                dictRemoveFile: "Hapus File",
                dictDefaultMessage: "Drag & Drop your files here or click to upload",

                init: function () {
                    // Saat file berhasil diunggah
                    this.on("success", function (file, response) {
                        console.log("File upload response:", response);

                        if (response && response.fileName) {
                            file.uploadedFileName = response.fileName; // Simpan nama file di objek Dropzone file
                            uploadedFiles.push(response.fileName); // Tambahkan nama file ke array uploadedFiles

                            console.log("Uploaded file added:", response.fileName);
                            console.log("Uploaded Files Array:", uploadedFiles);

                            // Perbarui input hidden dengan file yang diunggah
                            document.getElementById("uploadedFilesInput").value = JSON.stringify(uploadedFiles);
                        } else {
                            console.error("Error: No fileName in response:", response);
                        }
                    });

                    // Saat file dihapus dari Dropzone
                    this.on("removedfile", function (file) {
                        console.log("File removed:", file);

                        // Pastikan uploadedFileName tersedia
                        if (file.uploadedFileName) {
                            console.log("Removing file from server:", file.uploadedFileName);

                            // Tambahkan file ke array removedFiles
                            removedFiles.push(file.uploadedFileName);

                            // Hapus file dari array uploadedFiles
                            uploadedFiles = uploadedFiles.filter(f => f !== file.uploadedFileName);

                            console.log("Updated Uploaded Files:", uploadedFiles);
                            console.log("Removed Files Array:", removedFiles);

                            // Perbarui input hidden untuk uploaded_files dan removed_files
                            document.getElementById("uploadedFilesInput").value = JSON.stringify(uploadedFiles);
                            document.getElementById("removedFilesInput").value = JSON.stringify(removedFiles);

                            // Kirim AJAX request untuk menghapus file di server
                            $.ajax({
                                url: "/pengajuan-klaim-pengobatan/delete-temp",
                                type: "POST",
                                data: {
                                    _token: token,
                                    fileName: file.uploadedFileName
                                },
                                success: function (response) {
                                    console.log("File successfully removed from server:", response);
                                },
                                error: function (error) {
                                    console.error("Failed to delete file on server:", error);
                                }
                            });
                        } else {
                            console.warn("File not uploaded to server, skipping removal.");
                        }
                    });
                }
            });
        }
        
    });
</script>