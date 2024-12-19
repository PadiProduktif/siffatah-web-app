@extends('layouts.app')

@section('title', 'Pengajuan Klaim Kecelakaan')

@section('content')
<div class="container">
    <div class="d-flex justify-content-end align-items-center mb-4">
        <h4 class="me-auto">Pengajuan Klaim Kecelakaan</h4> <!-- Tambahkan kelas 'me-auto' untuk memberi margin ke kanan pada judul -->
        <a href="" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDataBaru">+ Masukan Data Baru</a>
        <a href="" class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#modalDataExcel">+ Masukan Data Excel</a> <!-- Tambahkan kelas 'ms-2' untuk memberi margin kiri pada tombol kedua -->
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container">
    <button style="margin-bottom: 20px;" id="deleteSelected" class="btn btn-danger">Hapus Terpilih</button>
    <table id="tablePengajuan" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>No.</th>
                <th>ID Badge</th>
                <th>Nama Karyawan</th>
                <th>Unit Kerja</th>
                <th>Asuransi</th>
                <th>Rumah Sakit/Klinik</th>
                <th>Tanggal Kejadian</th>
                <th>Nama Keluarga</th>
                <th>Hubungan</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengajuanKlaim as $index => $data)
                <tr>
                    <td><input type="checkbox" class="rowCheckbox" value="{{ $data->id_klaim_kecelakaan }}"></td>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->id_badge }}</td>
                    <td>{{ $data->nama_karyawan }}</td>
                    <td>{{ $data->unit_kerja }}</td>
                    <td>{{ $data->nama_asuransi }}</td>
                    <td>{{ $data->rs_klinik }}</td>
                    <td>{{ date('Y-m-d', strtotime($data->tanggal_kejadian)) }}</td>
                    <td>{{ $data->nama_keluarga }}</td>
                    <td>{{ $data->hubungan_keluarga }}</td>
                    <td>{{ $data->deskripsi }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditData" 
                            data-id="{{ $data->id }}" 
                            data-id_badge="{{ $data->id_badge }}" 
                            data-nama_karyawan="{{ $data->nama_karyawan }}" 
                            data-unit_kerja="{{ $data->unit_kerja }}"
                            data-asuransi="{{ $data->asuransi }}"
                            data-rumah_sakit="{{ $data->rumah_sakit }}"
                            data-tanggal_kejadian="{{ $data->tanggal_kejadian }}"
                            data-nama_keluarga="{{ $data->nama_keluarga }}"
                            data-hubungan="{{ $data->hubungan }}"
                            data-deskripsi="{{ $data->deskripsi }}">
                            Edit
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="hapusData('{{ $data->id }}')">Hapus</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>

<!-- Modal Tambah Data -->
<div class="modal fade" id="modalTambahData" tabindex="-1" aria-labelledby="modalTambahDataLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('pengajuan-klaim.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahDataLabel">Tambah Data Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {{-- <div class="modal-body">
                    @include('pengajuan-klaim._form', ['data' => new \App\Models\PengajuanKlaim\Klaim_kecelakaan])
                </div> --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
    <div class="modal fade" id="modalDataBaru" tabindex="-1" aria-labelledby="modalDataBaruLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKaryawanModalLabel">Tambah Data Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="dataForm" action="{{ route('pengajuan-klaim.store') }}" method="POST" enctype="multipart/form-data" >
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Informasi Dasar -->
                            <div class="col-md-2">
                                <label for="id_badge" class="form-label">ID Badge</label>
                                <input type="text" class="form-control" id="id_badge" name="id_badge" required>
                            </div>

                            <div class="col-md-5">
                                <label for="gelar_depan" class="form-label">Nama Karyawan</label>
                                <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan">
                            </div>
                            <div class="col-md-5">
                                <label for="nama_karyawan" class="form-label">Unit Kerja</label>
                                <input type="text" class="form-control" id="unit_kerja" name="unit_kerja" required>
                            </div>
                            <div class="col-md-4">
                                <label for="gelar_belakang" class="form-label">Asuransi</label>
                                <input type="text" class="form-control" id="nama_asuransi" name="nama_asuransi">
                            </div>
                            <div class="col-md-5">
                                <label for="gelar_belakang" class="form-label">Rumah Sakit / Klinik</label>
                                <input type="text" class="form-control" id="rs_klinik" name="rs_klinik">
                            </div>
                            <div class="col-md-3">
                                <label for="gelar_belakang" class="form-label">Tanggal Kejadian</label>
                                <input type="date" class="form-control" id="tanggal_kejadian" name="tanggal_kejadian">
                            </div>
                            <div class="col-md-8">
                                <label for="gelar_belakang" class="form-label">Nama Keluarga</label>
                                <input type="text" id="nama_keluarga" class="form-control" name="nama_keluarga">
                            </div>
                            <div class="col-md-4">
                                <label for="gelar_belakang" class="form-label">Hubungan Keluarga</label>
                                <input type="text" id="hubungan_keluarga" class="form-control" name="hubungan_keluarga">
                            </div>
                            <div class="col-12">
                                <label for="alamat" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                            </div>
                            <div class="col-md-12">
                                <label for="dropzone" class="form-label">Attachment</label>
                                <div class="dropzone" id="attachmentDropzone">
                                    <div class="dz-message">
                                        Drag & Drop your files here or click to upload
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="uploaded_files" id="uploadedFilesInput" value="[]">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            
            
            </div>
        </div>
    </div>
<!-- Modal Edit Data -->
<div class="modal fade" id="modalEditData" tabindex="-1" aria-labelledby="modalEditDataLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('pengajuan-klaim.update', ['id' => 0]) }}" method="POST" id="editForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditDataLabel">Edit Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {{-- <div class="modal-body">
                    @include('pengajuan-klaim._form', ['data' => null])
                </div> --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </form>
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
            <form action="{{ route('ekses.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="file_excel" class="form-label">Upload File Excel</label>
                    <input type="file" class="form-control" id="file_excel" name="file_excel" accept=".xlsx, .xls">
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
            </div>

        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
<script>
    function hapusData(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            $.ajax({
                url: '/pengajuan-klaim/' + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    alert('Data berhasil dihapus!');
                    location.reload();
                },
                error: function() {
                    alert('Terjadi kesalahan saat menghapus data!');
                }
            });
        }
    }

    $('#modalEditData').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const form = $('#editForm');
        form.attr('action', '/pengajuan-klaim/' + button.data('id'));
        form.find('input[name=id_badge]').val(button.data('id_badge'));
        form.find('input[name=nama_karyawan]').val(button.data('nama_karyawan'));
        form.find('input[name=unit_kerja]').val(button.data('unit_kerja'));
        form.find('input[name=asuransi]').val(button.data('asuransi'));
        form.find('input[name=rumah_sakit]').val(button.data('rumah_sakit'));
        form.find('input[name=tanggal_kejadian]').val(button.data('tanggal_kejadian'));
        form.find('input[name=nama_keluarga]').val(button.data('nama_keluarga'));
        form.find('input[name=hubungan]').val(button.data('hubungan'));
        form.find('textarea[name=deskripsi]').val(button.data('deskripsi'));
    });

    $(document).ready(function () {
            // Inisialisasi DataTables
            var table = $('#tablePengajuan').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
            });
    
            // Event untuk Select All Checkbox
            $('#selectAll').on('click', function () {
                var rows = table.rows({ search: 'applied' }).nodes();
                $('input[type="checkbox"].rowCheckbox', rows).prop('checked', this.checked);
            });
    
            // Event untuk mengontrol Select All Checkbox jika checkbox baris diubah
            $('#tablePengajuan tbody').on('change', 'input[type="checkbox"].rowCheckbox', function () {
                if (!this.checked) {
                    var el = $('#selectAll').get(0);
                    if (el && el.checked && ('indeterminate' in el)) {
                        el.indeterminate = true;
                    }
                }
            });
    
            // Ambil Data Checkbox yang Dipilih
            $('#getSelected').on('click', function () {
                var selected = [];
                $('input[type="checkbox"].rowCheckbox:checked').each(function () {
                    selected.push($(this).closest('tr').find('td:nth-child(2)').text()); // Ambil ID Badge
                });
    
                alert('Selected ID Badges: ' + selected.join(', '));
            });
        });
        
            Dropzone.autoDiscover = false;
            let removedFiles = [];
            let uploadedFiles = []; // Array untuk menyimpan file yang dihapus
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const attachmentDropzone = new Dropzone("#attachmentDropzone", {
                url: "/pengajuan-klaim-kecelakaan/upload-temp", // Endpoint sementara untuk upload
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
                                url: "/pengajuan-klaim-kecelakaan/delete-temp",
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
</script>
@endsection
