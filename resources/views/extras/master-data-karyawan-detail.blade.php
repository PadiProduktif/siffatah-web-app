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
    .attachment-list {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f9f9f9;
    }

    .file-item {
        padding: 5px 0;
        border-bottom: 1px solid #eee;
    }

    .file-item:last-child {
        border-bottom: none;
    }

    .file-item .btn {
        margin-left: auto;
        
}
.custom-thumbnail {
    max-width: 150px; /* Atur lebar maksimal */
    max-height: 100px; /* Atur tinggi maksimal */
    object-fit: cover; /* Pastikan gambar tetap proporsional */
    border-radius: 5px; /* Opsional: tambahkan efek rounded */
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
                                {{-- @foreach($dataKaryawan['files'] as $value)
                                    <div class="col-md-4">
                                        <div class="card">
                                            <img src="{{ $value['url'] }}" alt="Thumbnail 1" class="img-thumbnail custom-thumbnail">
                                            <p class="text-center">{{ $value['name'] }}</p>
                                        </div>
                                    </div>
                                @endforeach --}}
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="{{ url('/admin/master_data_karyawan/detail/update_berkas/' . $dataKaryawan->id_karyawan) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <!-- Informasi Dasar -->
                    
                                                <!-- Informasi Dasar -->
                                                
                                                <div class="col-md-12">
                                                    <label for="dropzone" class="form-label">Attachment</label>
                                                    <div id="editAttachmentDropzone" class="dropzone">
                                                        <div class="dz-message">Drag & Drop files here or click to upload</div>
                                                    </div>
                                                    <!-- Tampilkan file lama -->
                                                    <div id="editAttachmentList">
                                                        <!-- File lama akan dimuat melalui JavaScript -->
                                                    </div>
                                                </div>              
                                            </div>
                                        </div>
                                        {{-- <input type="hidden" name="files" id="fileList" value="{{ $dataKaryawan->files }}"> --}}
                                        <input type="hidden" name="uploaded_files" id="uploadedFilesInput" value="[]">
                                        <input type="hidden" name="removed_files" id="removedFilesInput" value="[]">
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
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
           let uploadedFiles = []; // Menyimpan nama file yang sudah diupload ke server
        function updateHiddenInput() {
            $('#uploadedFilesInput').val(JSON.stringify(uploadedFiles));
            console.log("Updated Uploaded Files Input:", $('#uploadedFilesInput').val());
            
        }


    document.addEventListener('DOMContentLoaded', function () {
        Dropzone.autoDiscover = false;
        let oldFiles = []; // Array untuk menyimpan file lama


// Inisialisasi Dropzone Edit




        let existingFiles1 = []; // Menyimpan nama file yang sudah ada di database

        // Konfigurasi Dropzone
        let editAttachmentDropzone = new Dropzone("#editAttachmentDropzone", {
            url: "/master_data_karyawan/upload-temp", // Endpoint sementara untuk upload file
            paramName: "file",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            maxFilesize: 5, // 5MB
            acceptedFiles: "image/*,.pdf,.doc,.docx,.xls,.xlsx",
            addRemoveLinks: true,
            dictRemoveFile: "Hapus File",

            // Event saat file berhasil di-upload
            success: function (file, response) {
                if (response && response.fileName) {
                    console.log("File uploaded successfully:", response);

                    // Hanya tambahkan file ke array jika berhasil diupload

                    uploadedFiles.push(response.fileName);
                    console.log("Uploaded Files Array:", uploadedFiles);

                    // Simpan nama file di elemen Dropzone
                    file.serverFileName = response.fileName;

                    // Update input hidden
                    updateHiddenInput();
                } else {
                    console.error("File upload failed or invalid response:", response);
                }
            },

            // Event saat file dihapus
            removedfile: function (file) {
                console.log("Removing file:", file);

                // Periksa apakah file adalah file baru atau file lama
                if (file.serverFileName) {
                    // Jika file sudah di-upload (file baru), kirim request untuk hapus file
                    $.ajax({
                        url: "/master_data_karyawan/delete-temp",
                        method: "POST",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            fileName: file.serverFileName
                        },
                        success: function () {
                            console.log("File removed from server:", file.serverFileName);

                            // Hapus file dari array uploadedFiles
                            uploadedFiles = uploadedFiles.filter(f => f !== file.serverFileName);
                            console.log("Updated Uploaded Files Array:", uploadedFiles);

                            // Update input hidden
                            updateHiddenInput();
                        },
                        error: function () {
                            console.error("Failed to remove file from server:", file.serverFileName);
                        }
                    });
                } else if (file.name) {
                    // Jika file adalah file lama
                    console.log("Mark file for removal:", file.name);
                    existingFiles1 = existingFiles1.filter(f => f !== file.name);

                    // Update input hidden untuk file lama yang dihapus
                    $('#removedFilesInput').val(JSON.stringify(existingFiles1));
                    console.log("Updated Removed Files Input:", existingFiles1);
                }

                // Hapus file dari tampilan Dropzone
                file.previewElement.remove();
            },

            init: function () {
                // Ambil data-file dari elemen Dropzone
                let existingFilesFromServer = $('#editAttachmentDropzone').data('files') || '[]';

                // Pastikan data adalah string JSON valid
                try {
                    existingFiles = JSON.parse(existingFilesFromServer);
                } catch (error) {
                    console.error("Invalid JSON in data-files:", error);
                    existingFiles = []; // Default ke array kosong jika error
                }

                // Tampilkan file lama di Dropzone
                if (Array.isArray(existingFiles)) {
                    existingFiles.forEach(file => {
                        let mockFile = { name: file, size: 12345, serverFileName: file };
                        this.emit("addedfile", mockFile);
                        this.emit("thumbnail", mockFile, `/uploads/MasterDataKaryawan/Attachments/${file}`);
                        this.emit("complete", mockFile);
                    });
                } else {
                    console.warn("No valid files to display.");
                }
            }
        });
        
        $('#editAttachmentList').on('click', '.remove-old-file', function () {
                const fileToRemove = $(this).data('file');

                // Ambil value input hidden dan update
                let removedFiles = $('#removedFilesInput').val() ? JSON.parse($('#removedFilesInput').val()) : [];
                removedFiles.push(fileToRemove);
                $('#removedFilesInput').val(JSON.stringify(removedFiles));

                console.log("Removed Files Input:", $('#removedFilesInput').val()); // Debug
                $(this).parent().remove(); // Hapus tampilan file
            });
            // let uploadedFiles = []; // Inisialisasi array global untuk file yang diunggah

            editAttachmentDropzone.on("success", function (file, response) {
                console.log("File uploaded:", response.fileName);

                // Cek apakah file sudah ada dalam array
                if (!uploadedFiles.includes(response.fileName)) {
                    uploadedFiles.push(response.fileName); // Tambahkan ke array jika belum ada
                    console.log("Updated uploadedFiles:", uploadedFiles);

                    // Perbarui input hidden
                    $('#uploadedFilesInput').val(JSON.stringify(uploadedFiles));
                }
            });
});
//  Dropzone.autoDiscover = false;
// console.log("Updated Uploaded Files Input:", $('#uploadedFilesInput').val());
document.addEventListener('DOMContentLoaded', function () {
    // Ambil data dari server
    const dataFilesRaw = @json($dataKaryawan['files']);
    console.log("Data Files (Raw):", dataFilesRaw);

    // Validasi dan parsing data menjadi array
    let dataFiles;
    if (Array.isArray(dataFilesRaw)) {
        dataFiles = dataFilesRaw; // Jika sudah array
    } else if (typeof dataFilesRaw === 'string') {
        try {
            dataFiles = JSON.parse(dataFilesRaw); // Parse jika string JSON
        } catch (e) {
            console.error("Failed to parse JSON:", e);
            dataFiles = []; // Fallback ke array kosong jika parsing gagal
        }
    } else {
        dataFiles = []; // Fallback ke array kosong jika bukan array atau string
    }
    console.log("Data Files (Parsed):", dataFiles);

    // Ambil elemen untuk daftar file
    const editAttachmentList = document.getElementById('editAttachmentList');

    // Loop file dan tambahkan ke dalam daftar
    if (Array.isArray(dataFiles)) {
        dataFiles.forEach(file => {
            // Buat elemen file
            const fileDiv = document.createElement('div');
            fileDiv.classList.add('file-item');

            // Tampilkan file dengan nama dan tombol hapus
            fileDiv.innerHTML = `
                <div class="d-flex align-items-center mb-2">
                    
                    <span class="me-3">
                        <img src="/uploads/MasterDataKaryawan/Attachments/${file}" alt="${file}" class="custom-thumbnail">
                        <a href="/uploads/MasterDataKaryawan/Attachments/${file}" target="_blank">${file}</a>
                    </span>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeFile('${file}')">Hapus</button>
                </div>
                
            `
            
            ;

            // Tambahkan elemen file ke dalam daftar
            editAttachmentList.appendChild(fileDiv);
        });
    } else {
        console.warn("Data Files is not a valid array. No files to display.");
    }
});

// Fungsi untuk menghapus file
function removeFile(fileName) {
    $.ajax({
        url: '/master_data_karyawan/delete-temp',
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            fileName: fileName
        },
        success: function (response) {
            console.log('File removed:', fileName);

            // Hapus elemen file dari DOM
            const fileItem = Array.from(document.querySelectorAll('.file-item')).find(item => item.textContent.includes(fileName));
            if (fileItem) fileItem.remove();

            // Tambahkan fileName ke array remove_files di hidden input
            let removedFiles = JSON.parse($('#removedFilesInput').val() || "[]");
            removedFiles.push(fileName);
            $('#removedFilesInput').val(JSON.stringify(removedFiles));

            console.log('Updated Removed Files:', removedFiles);
        },
        error: function (error) {
            console.error('Failed to remove file:', error);
        }
    });
}


   
</script>