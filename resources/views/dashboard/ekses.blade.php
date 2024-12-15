@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<style>
.uploaded-file {
    display: inline-block;
    margin: 10px;
    text-align: center;
}

.uploaded-file img {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border: 1px solid #ddd;
    border-radius: 8px;
}

.uploaded-file p {
    margin-top: 5px;
    font-size: 14px;
}
.detailRow {
        cursor: pointer; /* Pointer berubah menjadi tangan */
    }
.detailRow:hover {
        background-color: #f8f9fa; /* Highlight baris ketika dihover */
    }
/* .dropzone {
    border: 2px dashed #007bff;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 5px;
    text-align: center;
    margin-top: 10px;
}
.dz-message {
    color: #007bff;
    font-size: 16px;
} */
</style>
    <div class="container">
        <div class="d-flex justify-content-end align-items-center mb-4">
            <h4 class="me-auto">Ekses</h4> <!-- Tambahkan kelas 'me-auto' untuk memberi margin ke kanan pada judul -->
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
                <th><input type="checkbox" id="selectAll"></th> <!-- Checkbox untuk Select All -->
                <th>Member ID</th>
                <th>ID Badge</th>
                <th>Nama Karyawan</th>
                <th>Unit Kerja</th>
                <th>Nama Pasien</th>
                <th>Deskripsi</th>
                <th>Tanggal Pengajuan</th>
                <th>Jumlah Pengajuan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data Dummy
            @foreach($ekses as $item)
            <tr>
                <td><input type="checkbox" class="rowCheckbox" value="{{ $item->id_ekses }}"></td>
                <th>{{ $item->id_member }}</th>
                <th>{{ $item->id_badge }}</th>
                <th>{{ $item->nama_karyawan }}</th>
                <th>{{ $item->unit_kerja }}</th>
                <td>{{ $item->nama_pasien }}</td>
                <td>{{ $item->deskripsi }}</td>
                @php
                    setlocale(LC_TIME, 'id_ID'); // Set ke Bahasa Indonesia
                    $tanggal_formatted = strftime('%d %B %Y', strtotime($item->tanggal_pengajuan));
                @endphp
                <td>{{ $tanggal_formatted }}</td>
                @php
                    $formatted_jumlah = 'Rp.' . number_format($item->jumlah_ekses, 0, ',', '.');
                @endphp
                <td>{{ $formatted_jumlah }}</td>

                <td>
                    <button type="button" class="btn btn-warning btn-sm editBtn"
                    data-id="{{ $item->id_ekses }}"
                    data-id_member="{{ $item->id_member }}"
                    data-id_badge="{{ $item->id_badge }}"
                    data-nama_karyawan="{{ $item->nama_karyawan }}"
                    data-unit_kerja="{{ $item->unit_kerja }}"
                    data-nama_pasien="{{ $item->nama_pasien }}"
                    data-deskripsi="{{ $item->deskripsi }}"
                    data-tanggal_pengajuan="{{ $item->tanggal_pengajuan }}"
                    data-jumlah_ekses="{{ $formatted_jumlah }}"

                    data-bs-toggle="modal" data-bs-target="#modalEditData">
                Edit
                </button>
                <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $item->id_ekses }}">Hapus</button>
                </td>
            </tr>
            @endforeach -->
            @foreach($ekses as $item)
        <tr class="detailRow"
            data-id="{{ $item->id_ekses }}"
            data-id_member="{{ $item->id_member }}"
            data-id_badge="{{ $item->id_badge }}"
            data-nama_karyawan="{{ $item->nama_karyawan }}"
            data-unit_kerja="{{ $item->unit_kerja }}"
            data-nama_pasien="{{ $item->nama_pasien }}"
            data-deskripsi="{{ $item->deskripsi }}"
            data-tanggal_pengajuan="{{ $item->tanggal_pengajuan }}"
            data-jumlah_ekses="{{ $formatted_jumlah }}"
            data-file_url="{{ $item->file_url }}"> <!-- Tambahkan file_url -->
            <td><input type="checkbox" class="rowCheckbox" value="{{ $item->id_ekses }}"></td>
            <th>{{ $item->id_member }}</th>
            <th>{{ $item->id_badge }}</th>
            <th>{{ $item->nama_karyawan }}</th>
            <th>{{ $item->unit_kerja }}</th>
            <td>{{ $item->nama_pasien }}</td>
            <td>{{ $item->deskripsi }}</td>
            @php
                    setlocale(LC_TIME, 'id_ID'); // Set ke Bahasa Indonesia
                    $tanggal_formatted = strftime('%d %B %Y', strtotime($item->tanggal_pengajuan));
            @endphp
            <td>{{ $tanggal_formatted }}</td>
            @php
                $formatted_jumlah = 'Rp.' . number_format($item->jumlah_ekses, 0, ',', '.');
            @endphp
            <td>{{ $formatted_jumlah }}</td>
            <td>
                <!-- <button type="button" class="btn btn-warning btn-sm editBtn" data-bs-toggle="modal" data-bs-target="#modalEditData">
                    Edit
                </button> -->
                <button type="button" class="btn btn-warning btn-sm editBtn"
                    data-id="{{ $item->id_ekses }}"
                    data-id_member="{{ $item->id_member }}"
                    data-id_badge="{{ $item->id_badge }}"
                    data-nama_karyawan="{{ $item->nama_karyawan }}"
                    data-unit_kerja="{{ $item->unit_kerja }}"
                    data-nama_pasien="{{ $item->nama_pasien }}"
                    data-deskripsi="{{ $item->deskripsi }}"
                    data-tanggal_pengajuan="{{ $item->tanggal_pengajuan }}"
                    data-jumlah_ekses="{{ $formatted_jumlah }}"
                    data-file_url="{{ $item->file_url }}">
                    Edit
                </button>

                <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $item->id_ekses }}">Hapus</button>
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

    <div class="modal fade" id="modalDataBaru" tabindex="-1" aria-labelledby="modalDataBaruLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKaryawanModalLabel">Tambah Data Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="dataForm" action="{{ route('ekses.store') }}" method="POST" enctype="multipart/form-data" >
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Informasi Dasar -->
                            <div class="col-md-2">
                                <label for="id_badge" class="form-label">Member ID</label>
                                <input type="text" class="form-control" id="id_member" name="id_member" required>
                            </div>

                            <div class="col-md-2">
                                <label for="gelar_depan" class="form-label">ID Badge</label>
                                <input type="text" class="form-control" id="id_badge" name="id_badge">
                            </div>
                            <div class="col-md-5">
                                <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
                                <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan" required>
                            </div>
                            <div class="col-md-3">
                                <label for="gelar_belakang" class="form-label">Unit Kerja</label>
                                <input type="text" class="form-control" id="unit_kerja" name="unit_kerja">
                            </div>
                            <div class="col-md-5">
                                <label for="gelar_belakang" class="form-label">Nama Pasien</label>
                                <input type="text" class="form-control" id="nama_pasien" name="nama_pasien">
                            </div>
                            <div class="col-md-3">
                                <label for="gelar_belakang" class="form-label">Tanggal Pengajuan</label>
                                <input type="date" class="form-control" id="tanggal_pengajuan" name="tanggal_pengajuan">
                            </div>
                            <div class="col-md-4">
                                <label for="gelar_belakang" class="form-label">Jumlah Pengajuan</label>
                                <input type="text" id="nominal" class="form-control" name="jumlah_pengajuan">
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
                <form  id="editForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Informasi Dasar -->
                            <div class="col-md-2">
                                <label for="id_badge" class="form-label">Member ID</label>
                                <input type="text" class="form-control" id="editIdMember" name="id_member" required>
                            </div>

                            <div class="col-md-2">
                                <label for="gelar_depan" class="form-label">ID Badge</label>
                                <input type="text" class="form-control" id="editIdBadge" name="id_badge">
                            </div>
                            <div class="col-md-5">
                                <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
                                <input type="text" class="form-control" id="editNamaKaryawan" name="nama_karyawan" required>
                            </div>
                            <div class="col-md-3">
                                <label for="gelar_belakang" class="form-label">Unit Kerja</label>
                                <input type="text" class="form-control" id="editUnitKerja" name="unit_kerja">
                            </div>
                            <div class="col-md-5">
                                <label for="gelar_belakang" class="form-label">Nama Pasien</label>
                                <input type="text" class="form-control" id="editNamaPasien" name="nama_pasien">
                            </div>
                            <div class="col-md-3">
                                <label for="gelar_belakang" class="form-label">Tanggal Pengajuan</label>
                                <input type="date" class="form-control" id="editTanggalPengajuan" name="tanggal_pengajuan">
                            </div>
                            <div class="col-md-4">
                                <label for="gelar_belakang" class="form-label">Jumlah Pengajuan</label>
                                <input type="text" id="editJumlahPengajuan" class="form-control" name="jumlah_pengajuan">
                            </div>
                            <div class="col-12">
                                <label for="alamat" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="editDeskripsi" name="deskripsi" rows="3" required></textarea>
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
                    {{-- <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-success">Upload</button>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
    <script>
        new Cleave('#nominal', {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand',
            prefix: 'Rp ',
            rawValueTrimPrefix: true
        });
    </script>
 
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
        // Inisialisasi DataTables
        var table = $('#tableAdmin').DataTable();

        // Event delegation untuk tombol Edit
        $('#tableAdmin').on('click', '.editBtn', function () {
            // Ambil data dari atribut tombol
            const id = $(this).data('id');
            const id_member = $(this).data('id_member');
            const id_badge = $(this).data('id_badge');
            const nama_karyawan = $(this).data('nama_karyawan');
            const unit_kerja = $(this).data('unit_kerja');
            const nama_pasien = $(this).data('nama_pasien');
            const deskripsi = $(this).data('deskripsi');
            const tanggal_pengajuan = $(this).data('tanggal_pengajuan');
            const jumlah_ekses = $(this).data('jumlah_ekses');

            // Isi data di dalam form modal
            $('#editId').val(id);
            $('#editIdMember').val(id_member);
            $('#editIdBadge').val(id_badge);
            $('#editNamaKaryawan').val(nama_karyawan);
            $('#editUnitKerja').val(unit_kerja);
            $('#editNamaPasien').val(nama_pasien);
            $('#editDeskripsi').val(deskripsi);
            $('#editTanggalPengajuan').val(tanggal_pengajuan);
            $('#editJumlahPengajuan').val(jumlah_ekses);

            // Ubah action form agar mengarah ke endpoint update
            // $('#editForm').attr('action', '/kelengkapan_kerja/update/' + id);
            // let baseUrl = window.location.origin;
            // $('#editForm').attr('action', baseUrl + '/kelengkapan_kerja/update/' + id);
            $('#editForm').attr('action', '/admin/ekses/update/' + id);
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
                url: '/admin/ekses/delete/' + id,
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
                            url: '/admin/ekses/delete-multiple', // Endpoint untuk hapus data
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

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const attachmentDropzone = new Dropzone("#attachmentDropzone", {
            url: "/upload-temp",
            paramName: "file",
            headers: {
                'X-CSRF-TOKEN': token // Kirim CSRF token di header
            },
            maxFiles: 5,
            maxFilesize: 5, // 5MB
            acceptedFiles: "image/*,.pdf",
            addRemoveLinks: true,
            dictDefaultMessage: "Drag & Drop your files here or click to upload",

            init: function () {
                this.on("success", function (file, response) {
                    console.log("File uploaded:", response);

                    // Tambahkan file ke input hidden
                    const hiddenInput = document.createElement("input");
                    hiddenInput.type = "hidden";
                    hiddenInput.name = "uploaded_files[]";
                    hiddenInput.value = response.fileName;
                    document.getElementById("dataForm").appendChild(hiddenInput);
                });
            }
        });

        document.getElementById("dataForm").addEventListener("submit", function (event) {
            console.log("Form Data:");
            const formData = new FormData(this);
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }
        });

    </script>
    
    

@endsection