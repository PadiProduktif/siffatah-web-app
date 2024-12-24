@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')


    <div class="container">
        <div class="d-flex justify-content-end align-items-center mb-4">
            <h4 class="me-auto">Pengajuan Klaim - Kematian</h4> <!-- Tambahkan kelas 'me-auto' untuk memberi margin ke kanan pada judul -->
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
        
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <div class="container">
    <button style="margin-bottom: 20px;" id="deleteSelected" class="btn btn-danger">Hapus Terpilih</button>
    <table id="tableAdmin" class="display">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>

                <th>ID Badge</th>
                <th>Nama Karyawan</th>
                <th>Unit Kerja</th>
                <th>Asuransi</th>
                <th>Rumah Sakit/Klinik</th>
                <th>Tanggal Kematian</th>
                <th>Ahli Waris</th>
                <th>Hubungan</th>
                <th>No Polis</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            

            @foreach($pengajuanKlaim as $data)
            <tr class="detailRow"
                data-id="{{ $data->id_klaim_kematian }}" 
                data-id_badge="{{ $data->id_badge }}" 
                data-nama_karyawan="{{ $data->nama_karyawan }}" 
                data-unit_kerja="{{ $data->unit_kerja }}"
                data-asuransi="{{ $data->nama_asuransi }}"
                data-rumah_sakit="{{ $data->rs_klinik }}"
                data-tanggal_wafat="{{ $data->tanggal_wafat }}"
                data-ahli_waris="{{ $data->nama_keluarga }}"
                data-hubungan="{{ $data->hubungan_keluarga }}"
                data-no_polis="{{ $data->no_polis }}"
                data-file_url="{{ $data->file_url }}"> <!-- Tambahkan file_url -->
                <td><input type="checkbox" class="rowCheckbox" value="{{ $data->id_klaim_kematian }}"></td>
                <td>{{ $data->id_badge }}</td>
                <td>{{ $data->nama_karyawan }}</td>
                <td>{{ $data->unit_kerja }}</td>
                <td>{{ $data->nama_asuransi }}</td>
                <td>{{ $data->rs_klinik }}</td>
                @php
                        setlocale(LC_TIME, 'id_ID'); // Set ke Bahasa Indonesia
                        $tanggal_formatted = strftime('%d %B %Y', strtotime($data->tanggal_wafat));
                @endphp
                <td>{{ $tanggal_formatted }}</td>
                <td>{{ $data->nama_keluarga }}</td>
                <td>{{ $data->hubungan_keluarga }}</td>
                <td>{{ $data->no_polis }}</td>
                <td>

                    <button type="button" class="btn btn-warning btn-sm editBtn"
                        data-id="{{ $data->id_klaim_kematian }}" 
                        data-id_badge="{{ $data->id_badge }}" 
                        data-nama_karyawan="{{ $data->nama_karyawan }}" 
                        data-unit_kerja="{{ $data->unit_kerja }}"
                        data-asuransi="{{ $data->nama_asuransi }}"
                        data-rumah_sakit="{{ $data->rs_klinik }}"
                        data-tanggal_wafat="{{ $data->tanggal_wafat }}"
                        data-ahli_waris="{{ $data->nama_keluarga }}"
                        data-hubungan="{{ $data->hubungan_keluarga }}"
                        data-no_polis="{{ $data->no_polis }}"
                        data-file_url="{{ $data->file_url }}"> <!-- Tambahkan file_url -->
                        Edit
                    </button>

                    <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $data->id_klaim_kematian }}">Hapus</button>
                    
                </td>
            </tr>
        @endforeach

            <!-- Tambahkan Data lainnya -->
        </tbody>
    </table>
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
                    <p><strong>Nama Asuransi:</strong> <span id="detailNamaAsuransi"></span></p>
                    <p><strong>Rumah Sakit:</strong> <span id="detailRumahSakit"></span></p>
                    <p><strong>Tanggal Wafat:</strong> <span id="detailTanggalWafat"></span></p>
                    <p><strong>Ahli Waris:</strong> <span id="detailNamaKeluarga"></span></p>
                    <p><strong>Hubungan:</strong> <span id="detailHubungan"></span></p>
                    <p><strong>No Polis:</strong> <span id="detailNoPolis"></span></p>
                    <p><strong>Attachments:</strong></p>
                    <div id="detailAttachment"></div> <!-- Tempat untuk file attachment -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDataBaru" tabindex="-1" aria-labelledby="modalDataBaruLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKaryawanModalLabel">Tambah Data Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="dataForm" action="{{ route('pengajuan-klaim-kematian.store') }}" method="POST" enctype="multipart/form-data" >
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
                                <label for="gelar_belakang" class="form-label">Tanggal Wafat</label>
                                <input type="date" class="form-control" id="tanggal_wafat" name="tanggal_wafat">
                            </div>
                            <div class="col-md-4">
                                <label for="gelar_belakang" class="form-label">Ahli Waris</label>
                                <input type="text" id="nama_keluarga" class="form-control" name="nama_keluarga">
                            </div>
                            <div class="col-md-4">
                                <label for="gelar_belakang" class="form-label">Hubungan Keluarga</label>
                                <input type="text" id="hubungan_keluarga" class="form-control" name="hubungan_keluarga">
                            </div>
                            <div class="col-md-4">
                                <label for="alamat" class="form-label">No Polis</label>
                                <input class="form-control" id="no_polis" name="no_polis" rows="3" required></input>
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

    {{-- edit form --}}
    <div class="modal fade" id="modalEditData" tabindex="-1" aria-labelledby="modalDataBaruLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKaryawanModalLabel">Edit Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form  id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Informasi Dasar -->

                            <!-- Informasi Dasar -->
                            <div class="col-md-2">
                                <label for="id_badge" class="form-label">ID Badge</label>
                                <input type="text" class="form-control" id="editIdBadge" name="id_badge" required>
                            </div>

                            <div class="col-md-5">
                                <label for="gelar_depan" class="form-label">Nama Karyawan</label>
                                <input type="text" class="form-control" id="editNamaKaryawan" name="nama_karyawan">
                            </div>
                            <div class="col-md-5">
                                <label for="nama_karyawan" class="form-label">Unit Kerja</label>
                                <input type="text" class="form-control" id="editUnitKerja" name="unit_kerja" required>
                            </div>
                            <div class="col-md-4">
                                <label for="gelar_belakang" class="form-label">Asuransi</label>
                                <input type="text" class="form-control" id="editNamaAsuransi" name="nama_asuransi">
                            </div>
                            <div class="col-md-5">
                                <label for="gelar_belakang" class="form-label">Rumah Sakit / Klinik</label>
                                <input type="text" class="form-control" id="editRumahSakit" name="rs_klinik">
                            </div>
                            <div class="col-md-3">
                                <label for="gelar_belakang" class="form-label">Tanggal Wafat</label>
                                <input type="date" class="form-control" id="editTanggalWafat" name="tanggal_wafat">
                            </div>
                            <div class="col-md-4">
                                <label for="gelar_belakang" class="form-label">Nama Keluarga</label>
                                <input type="text" id="editAhliWaris" class="form-control" name="nama_keluarga">
                            </div>
                            <div class="col-md-4">
                                <label for="gelar_belakang" class="form-label">Hubungan Keluarga</label>
                                <input type="text" id="editHubungan" class="form-control" name="hubungan_keluarga">
                            </div>
                            <div class="col-4">
                                <label for="no_polis" class="form-label">No Polis</label>
                                <input class="form-control" id="editNoPolis" name="no_polis" rows="3" required></input>
                            </div>
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
                    <input type="hidden" name="uploaded_files" id="uploadedFilesInput" value="[]">
                    <input type="hidden" name="removed_files" id="removedFilesInput" value="[]">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
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
                    <form action="{{ route('pengajuan-klaim-kematian.upload') }}" method="POST" enctype="multipart/form-data">
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
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


 
    <script>
        function toggle(source) {
            checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = source.checked;
            });
        }
    </script>
    <script>
        $(document).ready(function () {
            // Inisialisasi DataTables
            var table = $('#tableAdmin').DataTable({
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
            $('#tableAdmin tbody').on('change', 'input[type="checkbox"].rowCheckbox', function () {
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
        $('#detailIDBadge').text(rowData.id_badge || '-');
        $('#detailNamaKaryawan').text(rowData.nama_karyawan || '-');
        $('#detailUnitKerja').text(rowData.unit_kerja || '-');
        $('#detailNamaAsuransi').text(rowData.asuransi || '-');
        $('#detailRumahSakit').text(rowData.rumah_sakit || '-');
        $('#detailTanggalWafat').text(rowData.tanggal_wafat || '-');
        $('#detailNamaKeluarga').text(rowData.ahli_waris || '-');
        $('#detailHubungan').text(rowData.hubungan || '-');
        $('#detailNoPolis').text(rowData.no_polis || '-');

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
                    console.error("Error Parsing JSON:", error.messsage);
                }
            }

            // Tampilkan file sebagai gambar atau link
            if (files.length > 0) {
                attachmentHtml = files.map(file => {
                    const fileExtension = file.split('.').pop().toLowerCase();
                    const filePath = `/uploads/PengajuanKlaim/klaim_Kematian/${file}`;

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
        // Inisialisasi DataTables
        var table = $('#tableAdmin').DataTable();

        // Event delegation untuk tombol Edit
        $('#tableAdmin').on('click', '.editBtn', function (event) {
            event.stopPropagation(); // Hentikan event klik dari propagasi ke elemen parent (baris)
            
            // Ambil data dari atribut tombol
            // const id = $(this).data('id');
            // const id_member = $(this).data('id_member');
            // const id_badge = $(this).data('id_badge');
            // const nama_karyawan = $(this).data('nama_karyawan');
            // const unit_kerja = $(this).data('unit_kerja');
            // const nama_pasien = $(this).data('nama_pasien');
            // const deskripsi = $(this).data('deskripsi');
            // const tanggal_pengajuan = $(this).data('tanggal_pengajuan');
            // const jumlah_ekses = $(this).data('jumlah_ekses');
            const id = $(this).data('id');
            const id_badge = $(this).data('id_badge') || '-';
            const nama_karyawan = $(this).data('nama_karyawan') || '-';
            const unit_kerja = $(this).data('unit_kerja') || '-';
            const nama_asuransi = $(this).data('asuransi') || '-';
            const rumah_sakit = $(this).data('rumah_sakit') || '-';
            const tanggal_wafat = $(this).data('tanggal_wafat') || '-';
            const ahli_waris = $(this).data('ahli_waris') || '-';
            const hubungan = $(this).data('hubungan') || '-';
            const no_polis = $(this).data('no_polis') || '-';

            // Isi data di dalam form modal
            // $('#editIdMember').val(id_member);
            // $('#editIdBadge').val(id_badge);
            // $('#editNamaKaryawan').val(nama_karyawan);
            // $('#editUnitKerja').val(unit_kerja);
            // $('#editNamaPasien').val(nama_pasien);
            // $('#editDeskripsi').val(deskripsi);
            // $('#editTanggalPengajuan').val(tanggal_pengajuan);
            // $('#editJumlahPengajuan').val(jumlah_ekses);
            $('#editIdBadge').val(id_badge || '-');
            $('#editNamaKaryawan').val(nama_karyawan || '-');
            $('#editUnitKerja').val(unit_kerja || '-');
            $('#editNamaAsuransi').val(nama_asuransi || '-');
            $('#editRumahSakit').val(rumah_sakit || '-');
            $('#editTanggalWafat').val(tanggal_wafat || '-');
            $('#editAhliWaris').val(ahli_waris || '-');
            $('#editHubungan').val(hubungan || '-');
            $('#editNoPolis').val(no_polis || '-');

            // Set action form update
            $('#editForm').attr('action', '/admin/klaim_kematian/update/' + id);

            // Tampilkan modal edit
            $('#modalEditData').modal('show');
        });

    });

    $(document).ready(function () {
    // Event delegation untuk tombol Hapus
    $('#tableAdmin').on('click', '.deleteBtn', function () {
        const id = $(this).data('id'); // Ambil ID data

        // Tampilkan konfirmasi dengan SweetAlert2
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Data akan dihapus dan tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika dikonfirmasi, kirim permintaan hapus ke server
                $.ajax({
                url: '/admin/klaim_kematian/delete/' + id,
                type: 'GET', // Ubah dari DELETE ke GET
                success: function (response) {
                    Swal.fire('Berhasil!', 'Data berhasil dihapus.', 'success');
                    location.reload(); // Reload halaman untuk memperbarui data
                },
                error: function () {
                    Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
                }
            });
            }
        });
    });
});

    


        $(document).ready(function () {
            // Pilih semua checkbox
            $('#selectAll').on('click', function () {
                $('.rowCheckbox').prop('checked', this.checked);
            });

            // Perbarui checkbox "Pilih Semua" jika ada perubahan pada baris
            $('#tableAdmin').on('change', '.rowCheckbox', function () {
                if (!this.checked) {
                    $('#selectAll').prop('checked', false);
                } else if ($('.rowCheckbox:checked').length === $('.rowCheckbox').length) {
                    $('#selectAll').prop('checked', true);
                }
            });

            // Hapus data terpilih
            $('#deleteSelected').on('click', function () {
                const selectedIds = $('.rowCheckbox:checked').map(function () {
                    return $(this).val();
                }).get(); // Ambil ID yang dipilih sebagai array

                if (selectedIds.length === 0) {
                    Swal.fire('Pilih Data!', 'Anda belum memilih data untuk dihapus.', 'warning');
                    return;
                }

                // Konfirmasi sebelum menghapus
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Data yang dipilih akan dihapus!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Kirim permintaan hapus melalui AJAX
                        $.ajax({
                            url: '/admin/klaim_kematian/delete-multiple', // Endpoint untuk hapus data
                            type: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                ids: selectedIds
                            },
                            success: function (response) {
                                Swal.fire('Berhasil!', 'Data berhasil dihapus.', 'success');
                                location.reload(); // Reload halaman untuk memperbarui tabel
                            },
                            error: function () {
                                Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
                            }
                        });
                    }
                });
            });
        });

        Dropzone.autoDiscover = false;

// let uploadedFiles = []; // Array untuk menyimpan nama file yang diunggah
        let removedFiles = []; // Array untuk menyimpan file yang dihapus

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        if (document.querySelector('#attachmentDropzone')) {
            const attachmentDropzone = new Dropzone("#attachmentDropzone", {
                url: "/pengajuan-klaim-kematian/upload-temp", // Endpoint sementara untuk upload
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
                                url: "/pengajuan-klaim-kematian/delete-temp",
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

        let oldFiles = []; // Array untuk menyimpan file lama


        // Inisialisasi Dropzone Edit



        let uploadedFiles = []; // Menyimpan nama file yang sudah diupload ke server
        let existingFiles1 = []; // Menyimpan nama file yang sudah ada di database

        // Konfigurasi Dropzone
        let editAttachmentDropzone = new Dropzone("#editAttachmentDropzone", {
            url: "/pengajuan-klaim-kematian/upload-temp", // Endpoint sementara untuk upload file
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
                        url: "/pengajuan-klaim-kematian/delete-temp",
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
                        this.emit("thumbnail", mockFile, `/uploads/PengajuanKlaim/klaim_Kematian/${file}`);
                        this.emit("complete", mockFile);
                    });
                } else {
                    console.warn("No valid files to display.");
                }
            }
        });

        // Fungsi untuk memperbarui input hidden
        function updateHiddenInput() {
            $('#uploadedFilesInput').val(JSON.stringify(uploadedFiles));
            console.log("Updated Uploaded Files Input:", $('#uploadedFilesInput').val());
        }


        // Event untuk menampilkan file lama pada modal edit
        $('#tableAdmin').on('click', '.editBtn', function () {
            const fileUrl = $(this).data('file_url'); // Ambil file lama dari data attribute

            let files = [];

            if (Array.isArray(fileUrl)) {
                files = fileUrl;
            } else if (typeof fileUrl === 'string') {
                try {
                    files = JSON.parse(fileUrl.trim());
                } catch (error) {
                    console.error("Error parsing file_url:", error.message);
                }
            }

            console.log("Processed files:", files); // Debugging

            $('#editAttachmentList').empty(); // Kosongkan list sebelumnya
            oldFiles = []; // Reset array file lama

            if (fileUrl) {
                try {
                    let files = [];

                // Validasi fileUrl
                if (Array.isArray(fileUrl)) {
                    files = fileUrl; // Jika sudah array, langsung gunakan
                } else if (typeof fileUrl === 'string') {
                    try {
                        files = JSON.parse(fileUrl.trim()); // Parsing jika string JSON
                    } catch (error) {
                        console.error("Error parsing file_url:", error.message);
                        files = []; // Default ke array kosong jika gagal
                    }
                } else {
                    console.warn("Unexpected fileUrl format:", fileUrl);
                }

                    files.forEach((file, index) => {
                        const filePath = `/uploads/PengajuanKlaim/klaim_Kematian/${file}`;
                        const fileExtension = file.split('.').pop().toLowerCase();

                        // Tampilkan file lama sebagai gambar atau link
                        let fileHtml = '';
                        if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                            fileHtml = `
                                <div class="uploaded-file" id="file-${index}">
                                    <img src="${filePath}" alt="${file}">
                                    <p>${file}</p>
                                    <button type="button" class="btn btn-danger btn-sm remove-old-file" 
                                        data-file="${file}" data-index="${index}">Hapus</button>
                                </div>`;
                        } else {
                            fileHtml = `
                                <div id="file-${index}">
                                    <a href="${filePath}" target="_blank">${file}</a>
                                    <button type="button" class="btn btn-danger btn-sm remove-old-file" 
                                        data-file="${file}" data-index="${index}">Hapus</button>
                                </div>`;
                        }

                        $('#editAttachmentList').append(fileHtml);
                        oldFiles.push(file); // Simpan file lama di array
                    });
                } catch (error) {
                    console.error("Error parsing file_url:", error);
                }
            }

//point
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


        // Debug sebelum form submit
        $('#editForm').on('submit', function (e) {
            console.log("Final Uploaded Files Input:", $('#uploadedFilesInput').val());
        });
        console.log("Removed Files Input:", $('#removedFilesInput').val());
        console.log("Uploaded Files Input:", $('#uploadedFilesInput').val());
        
        $('#editForm').on('submit', function (e) {
            e.preventDefault();

            const uploadedFilesInput = $('#uploadedFilesInput').val();
            const removedFilesInput = $('#removedFilesInput').val();

            let formData = new FormData(this);

            // Tambahkan uploadedFilesInput secara manual ke FormData
            formData.append('uploaded_files', uploadedFilesInput);
            formData.append('removed_files', removedFilesInput);

            console.log("===== Data Form =====");
            formData.forEach((value, key) => {
                console.log(`${key}:`, value);
            });

            // Kirim form dengan AJAX
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    Swal.fire('Berhasil!', 'Data berhasil diperbarui.', 'success');
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                },
                error: function (error) {
                    console.error('Error:', error);
                    Swal.fire('Gagal!', 'Terjadi kesalahan saat menyimpan data.', 'error');
                }
            });
        });

        
    </script>
    
    

@endsection