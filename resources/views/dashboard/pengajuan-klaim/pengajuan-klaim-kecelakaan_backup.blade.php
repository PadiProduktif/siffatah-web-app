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
                        <button class="btn btn-warning btn-sm editBtn" data-bs-toggle="modal" data-bs-target="#modalEditData" 
                            data-id="{{ $data->id }}" 
                            data-id_badge="{{ $data->id_badge }}" 
                            data-nama_karyawan="{{ $data->nama_karyawan }}" 
                            data-unit_kerja="{{ $data->unit_kerja }}"
                            data-asuransi="{{ $data->asuransi }}"
                            data-rumah_sakit="{{ $data->rumah_sakit }}"
                            data-tanggal_kejadian="{{ $data->tanggal_kejadian }}"
                            data-nama_keluarga="{{ $data->nama_keluarga }}"
                            data-hubungan="{{ $data->hubungan }}"
                            data-deskripsi="{{ $data->deskripsi }}"
                            data-file_url="{{ json_encode($data->file_url) }}">

                            Edit
                        </button>
                        <button class="btn btn-danger btn-sm deleteBtn" onclick="hapusData('{{ $data->id }}')">Hapus</button>
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
                                <div id="attachmentDropzone" class="dropzone">
                                    <div class="dz-message">Drag & Drop your files here or click to upload</div>
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

    <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailLabel">Detail Informasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Member ID:</strong> <span id="detailMemberID"></span></p>
                    <p><strong>ID Badge:</strong> <span id="detailIDBadge"></span></p>
                    <p><strong>Nama Karyawan:</strong> <span id="detailNamaKaryawan"></span></p>
                    <p><strong>Unit Kerja:</strong> <span id="detailUnitKerja"></span></p>
                    <p><strong>Nama Pasien:</strong> <span id="detailNamaPasien"></span></p>
                    <p><strong>Deskripsi:</strong> <span id="detailDeskripsi"></span></p>
                    <p><strong>Tanggal Pengajuan:</strong> <span id="detailTanggalPengajuan"></span></p>
                    <p><strong>Jumlah Pengajuan:</strong> <span id="detailJumlahEkses"></span></p>
                    <p><strong>Attachments:</strong></p>
                    <div id="detailAttachment"></div> <!-- Tempat untuk file attachment -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
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
    <!-- Modal Detail Data -->
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailLabel">Detail Informasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>ID Badge:</strong> <span id="detailIDBadge"></span></p>
                <p><strong>Nama Karyawan:</strong> <span id="detailNamaKaryawan"></span></p>
                <p><strong>Unit Kerja:</strong> <span id="detailUnitKerja"></span></p>
                <p><strong>Asuransi:</strong> <span id="detailNamaPasien"></span></p>
                <p><strong>Rumah Sakit/Klinik:</strong> <span id="detailDeskripsi"></span></p>
                <p><strong>Tanggal Kejadian:</strong> <span id="detailTanggalPengajuan"></span></p>
                <p><strong>Nama Keluarga:</strong> <span id="detailJumlahEkses"></span></p>
                <p><strong>Attachments:</strong> <div id="detailAttachment"></div></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
<script>
    $(document).ready(function () {
        // Inisialisasi DataTables
        var table = $('#tablePengajuan').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
        });

        // Event listener untuk klik baris tabel
        $('#tablePengajuan tbody').on('click', 'tr', function (event) {
            // Cegah klik pada tombol Edit atau Hapus agar tidak memicu modal detail
            if ($(event.target).closest('button').length) return;

            const row = $(this); // Ambil elemen baris yang diklik
            const rowData = table.row(row).data(); // Ambil data dari DataTables untuk baris ini

            console.log("Debug Row Data:", rowData); // Debug data row di console

            // Set data ke dalam modal detail
            $('#detailIDBadge').text(rowData[2] || '-'); // ID Badge
            $('#detailNamaKaryawan').text(rowData[3] || '-'); // Nama Karyawan
            $('#detailUnitKerja').text(rowData[4] || '-'); // Unit Kerja
            $('#detailNamaPasien').text(rowData[5] || '-'); // Nama Asuransi
            $('#detailDeskripsi').text(rowData[6] || '-'); // Rumah Sakit/Klinik
            $('#detailTanggalPengajuan').text(rowData[7] || '-'); // Tanggal Kejadian
            $('#detailJumlahEkses').text(rowData[8] || '-'); // Nama Keluarga
            $('#detailAttachment').html('Tidak ada file'); // Kosongkan detail attachment

            // Tampilkan modal
            $('#modalDetail').modal('show');
        });
    });
</script>
<script>


    
    $(document).ready(function () {
        // Event listener untuk klik baris tabel
        $('#tableAdmin tbody').on('click', 'tr', function () {
        // Cegah klik pada tombol Edit agar tidak memicu modal detail
        if ($(event.target).closest('.editBtn, .deleteBtn').length) return;

        const button = $(this).find('button.editBtn'); // Ambil tombol edit di dalam baris
        const rowData = button.data(); // Ambil semua data attributes dari tombol

        console.log("Debug Row Data:", rowData); // Debug data row di console

        // Set data ke dalam modal
        $('#detailMemberID').text(rowData.id_member || '-');
        $('#detailIDBadge').text(rowData.id_badge || '-');
        $('#detailNamaKaryawan').text(rowData.nama_karyawan || '-');
        $('#detailUnitKerja').text(rowData.unit_kerja || '-');
        $('#detailNamaPasien').text(rowData.nama_pasien || '-');
        $('#detailDeskripsi').text(rowData.deskripsi || '-');
        $('#detailTanggalPengajuan').text(rowData.tanggal_pengajuan || '-');
        $('#detailJumlahEkses').text(rowData.jumlah_ekses || '-');

        // Parsing dan menampilkan file_url jika ada
        let attachmentHtml = 'Tidak ada file';

        if (rowData.file_url) {
            console.log("Raw file_url:", rowData.file_url); // Debugging
            let files = [];

            // Jika file_url sudah berupa array, langsung gunakan
            if (Array.isArray(rowData.file_url)) {
                files = rowData.file_url;
            } else {
                // Jika file_url berupa string, parse terlebih dahulu
                try {
                    files = JSON.parse(rowData.file_url);
                } catch (error) {
                    console.error("Error Parsing JSON:", error.message);
                }
            }

            // Tampilkan file sebagai gambar atau link
            if (files.length > 0) {
                attachmentHtml = files.map(file => {
                    const fileExtension = file.split('.').pop().toLowerCase();
                    const filePath = `/uploads/Ekses/${file}`;

                    // Periksa ekstensi file
                    if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                        // Jika file adalah gambar, tampilkan sebagai img tag
                        return `<div><img src="${filePath}" alt="${file}" style="max-width: 150px; height: auto; margin: 5px;"></div>`;
                    } else {
                        // Jika file bukan gambar, tampilkan sebagai link
                        return `<div><a href="${filePath}" target="_blank">${file}</a></div>`;
                    }
                }).join('');
            }
        }

        // Tampilkan hasil pada modal
        $('#detailAttachment').html(attachmentHtml);

        // Tampilkan modal
        $('#modalDetail').modal('show');
    });

    });
</script>
    <script>
        $(document).ready(function () {
            // Event listener untuk klik baris tabel
            $('#tableAdmin tbody').on('click', 'tr', function () {
            // Cegah klik pada tombol Edit agar tidak memicu modal detail
            if ($(event.target).closest('.editBtn, .deleteBtn').length) return;

            const button = $(this).find('button.editBtn'); // Ambil tombol edit di dalam baris
            const rowData = button.data(); // Ambil semua data attributes dari tombol

            console.log("Debug Row Data:", rowData); // Debug data row di console

            // Set data ke dalam modal
            $('#detailMemberID').text(rowData.id_member || '-');
            $('#detailIDBadge').text(rowData.id_badge || '-');
            $('#detailNamaKaryawan').text(rowData.nama_karyawan || '-');
            $('#detailUnitKerja').text(rowData.unit_kerja || '-');
            $('#detailNamaPasien').text(rowData.nama_pasien || '-');
            $('#detailDeskripsi').text(rowData.deskripsi || '-');
            $('#detailTanggalPengajuan').text(rowData.tanggal_pengajuan || '-');
            $('#detailJumlahEkses').text(rowData.jumlah_ekses || '-');

            // Parsing dan menampilkan file_url jika ada
            let attachmentHtml = 'Tidak ada file';

            if (rowData.file_url) {
                console.log("Raw file_url:", rowData.file_url); // Debugging
                let files = [];

                // Jika file_url sudah berupa array, langsung gunakan
                if (Array.isArray(rowData.file_url)) {
                    files = rowData.file_url;
                } else {
                    // Jika file_url berupa string, parse terlebih dahulu
                    try {
                        files = JSON.parse(rowData.file_url);
                    } catch (error) {
                        console.error("Error Parsing JSON:", error.message);
                    }
                }

                // Tampilkan file sebagai gambar atau link
                if (files.length > 0) {
                    attachmentHtml = files.map(file => {
                        const fileExtension = file.split('.').pop().toLowerCase();
                        const filePath = `/uploads/Ekses/${file}`;

                        // Periksa ekstensi file
                        if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                            // Jika file adalah gambar, tampilkan sebagai img tag
                            return `<div><img src="${filePath}" alt="${file}" style="max-width: 150px; height: auto; margin: 5px;"></div>`;
                        } else {
                            // Jika file bukan gambar, tampilkan sebagai link
                            return `<div><a href="${filePath}" target="_blank">${file}</a></div>`;
                        }
                    }).join('');
                }
            }

            // Tampilkan hasil pada modal
            $('#detailAttachment').html(attachmentHtml);

            // Tampilkan modal
            $('#modalDetail').modal('show');
        });

        });
        </script>

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
        if (!$.fn.DataTable.isDataTable('#tablePengajuan')) {
            $('#tablePengajuan').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                destroy: true, // Pastikan tabel dapat di-reinitialize
            });
        }

        // Event listener untuk Select All
        $('#selectAll').on('click', function () {
            var rows = $('#tablePengajuan').DataTable().rows({ search: 'applied' }).nodes();
            $('input[type="checkbox"].rowCheckbox', rows).prop('checked', this.checked);
        });

        // Event listener untuk Checkbox individual
        $('#tablePengajuan tbody').on('change', 'input[type="checkbox"].rowCheckbox', function () {
            if (!this.checked) {
                var el = $('#selectAll').get(0);
                if (el && el.checked && ('indeterminate' in el)) {
                    el.indeterminate = true;
                }
            }
        });

        // Ambil data dari checkbox terpilih
        $('#getSelected').on('click', function () {
            var selected = [];
            $('input[type="checkbox"].rowCheckbox:checked').each(function () {
                selected.push($(this).closest('tr').find('td:nth-child(2)').text());
            });
            alert('Selected ID Badges: ' + selected.join(', '));
        });
    });

        
            
        
        $(document).ready(function () {
            Dropzone.autoDiscover = false;

            // Cegah inisialisasi ganda
            if (Dropzone.instances.length) {
                Dropzone.instances.forEach(instance => instance.destroy());
            }

            let removedFiles = [];
            let uploadedFiles = [];
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const attachmentDropzone = new Dropzone("#attachmentDropzone", {
                url: "/pengajuan-klaim-kecelakaan/upload-temp",
                paramName: "file",
                headers: {
                    'X-CSRF-TOKEN': token
                },
                maxFiles: 5,
                maxFilesize: 5,
                acceptedFiles: "image/*,.pdf,.doc,.docx,.xls,.xlsx",
                addRemoveLinks: true,
                dictRemoveFile: "Hapus File",
                dictDefaultMessage: "Drag & Drop your files here or click to upload",

                init: function () {
                    this.on("success", function (file, response) {
                        if (response && response.fileName) {
                            file.uploadedFileName = response.fileName;
                            uploadedFiles.push(response.fileName);

                            const uploadedFilesInput = document.getElementById("uploadedFilesInput");
                            if (uploadedFilesInput) {
                                uploadedFilesInput.value = JSON.stringify(uploadedFiles);
                            } else {
                                console.error("Element with ID 'uploadedFilesInput' not found.");
                            }
                        }
                    });

                    this.on("removedfile", function (file) {
                        if (file.uploadedFileName) {
                            removedFiles.push(file.uploadedFileName);
                            uploadedFiles = uploadedFiles.filter(f => f !== file.uploadedFileName);

                            const uploadedFilesInput = document.getElementById("uploadedFilesInput");
                            const removedFilesInput = document.getElementById("removedFilesInput");

                            if (uploadedFilesInput) {
                                uploadedFilesInput.value = JSON.stringify(uploadedFiles);
                            }

                            if (removedFilesInput) {
                                removedFilesInput.value = JSON.stringify(removedFiles);
                            }

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
                        }
                    });
                }
            });
        });

        // $('#tablePengajuan tbody').on('click', 'tr', function () {
        //     if ($(event.target).closest('button').length) return; // Hindari klik tombol

        //     const table = $('#tablePengajuan').DataTable();
        //     const rowData = table.row(this).data(); // Ambil data baris

        //     // Parsing file_url jika ada
        //     let files = [];
        //     if (rowData[10]) { // Indeks 10 adalah kolom file_url, sesuaikan dengan kolom Anda
        //         try {
        //             files = JSON.parse(rowData[10]); // Parsing JSON jika file_url berbentuk JSON
        //         } catch (error) {
        //             console.error('Error parsing file_url:', error.message);
        //         }
        //     }

        //     // Render file sebagai gambar atau link
        //     let attachmentHtml = 'Tidak ada file';
        //     if (files.length > 0) {
                
        //         attachmentHtml = files.map(file => {
        //             const fileExtension = file.split('.').pop().toLowerCase();
        //             const filePath = `/uploads/PengajuanKlaim/klaim_Kecelakaan/${file}`; // Pastikan path file sesuai
        //             console.log(filePath)
        //             // Periksa ekstensi file untuk menentukan apakah gambar atau link
        //             if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
        //                 return `<div><img src="${filePath}" alt="${file}" style="max-width: 150px; height: auto; margin: 5px;"></div>`;
        //             } else {
        //                 return `<div><a href="${filePath}" target="_blank">${file}</a></div>`;
        //             }
        //         }).join('');
        //     }

        //     // Tampilkan file di modal
        //     $('#detailAttachment').html(attachmentHtml);

        //     // Tampilkan modal
        //     $('#modalDetail').modal('show');
        // });

        $('#tablePengajuan tbody').on('click', 'tr', function () {
            const table = $('#tablePengajuan').DataTable();
            const rowData = table.row(this).data(); // Data untuk baris yang diklik
            console.log("Row Data:", rowData);
            console.log("File URL:", fileUrl);
            let files = [];
            try {
                // Ambil data file_url dari kolom yang sesuai
                const fileUrl = rowData[10]; // Pastikan kolom index 10 sesuai dengan 'file_url'

                if (fileUrl && fileUrl.trim() !== '') {
                    // Perbaiki format JSON jika perlu
                    files = JSON.parse(fileUrl.replace(/&quot;/g, '"').trim());
                }
            } catch (error) {
                console.error("Error parsing file_url:", error.message);
            }

            // Menampilkan attachment
            let attachmentHtml = 'Tidak ada file';
            if (Array.isArray(files) && files.length > 0) {
                attachmentHtml = files.map(file => {
                    const fileExtension = file.split('.').pop().toLowerCase();
                    const filePath = `/uploads/PengajuanKlaim/klaim_Kecelakaan/${file}`;

                    // Periksa apakah file adalah gambar
                    if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                        return `<div><img src="${filePath}" alt="${file}" style="max-width: 150px; height: auto; margin: 5px;"></div>`;
                    } else {
                        return `<div><a href="${filePath}" target="_blank">${file}</a></div>`;
                    }
                }).join('');
            }

            // Masukkan HTML ke dalam modal
            $('#detailAttachment').html(attachmentHtml);
            $('#modalDetail').modal('show');
        });
        


</script>
@endsection
