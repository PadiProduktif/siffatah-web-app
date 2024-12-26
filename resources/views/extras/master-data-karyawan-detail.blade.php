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
                
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Pasangan
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            
                            {{-- Menampilkan Pasangan --}}
                            @foreach($dataKaryawan['keluarga']['pasangan'] as $pasangan)
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-primary text-white">
                                            Pasangan
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $pasangan['nama'] }}</h5>
                                            <p class="card-text">
                                                <strong>NIK:</strong> {{ $pasangan['NIK'] }}<br>
                                                <strong>Status:</strong> {{ ucfirst($pasangan['status']) }}<br>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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

                                @foreach($dataKaryawan['keluarga']['anak'] as $anak)
                                    <div class="col-lg-4 col-md-6 mb-4">
                                        <div class="card shadow-sm">
                                            <div class="card-header bg-success text-white">
                                                Anak
                                            </div>
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $anak['nama'] }}</h5>
                                                <p class="card-text">
                                                    <strong>NIK:</strong> {{ $anak['NIK'] }}<br>
                                                    <strong>Status:</strong> {{ ucfirst($anak['status']) }}<br>
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
                        <div class="card-header">
                            <h2>Manajemen Berkas Fulan</h2>
                        </div>
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
@endsection

<script>
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