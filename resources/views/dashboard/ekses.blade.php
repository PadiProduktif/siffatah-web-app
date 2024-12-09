@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
   
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
            <!-- Data Dummy -->
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
            @endforeach
            <!-- Tambahkan Data lainnya -->
        </tbody>
    </table>
    <div class="modal fade" id="modalDataBaru" tabindex="-1" aria-labelledby="modalDataBaruLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKaryawanModalLabel">Tambah Data Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form  action="{{ route('ekses.store') }}" method="POST">
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
    </script>
    
    

@endsection