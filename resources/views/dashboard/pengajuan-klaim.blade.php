@extends('layouts.app')
@section('title', 'Pengajuan Klaim')
@section('content')

<!-- Table -->
<div class="table-container bg-white p-3 rounded shadow">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>PENGAJUAN KLAIM</h2>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary">
                <i class="bi bi-filter"></i> Tambahkan Filter
            </button>
            <button class="btn btn-outline-danger">
                <i class="bi bi-trash"></i> Hapus Semua
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table id="klaimTable" class="table table-hover">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" class="form-check-input" id="checkAll">
                    </th>
                    <th>ID BADGE</th>
                    <th>NAMA KARYAWAN</th>
                    <th>UNIT KERJA</th>
                    <th>NAMA ASURANSI</th>
                    <th>RUMAH SAKIT/KLINIK</th>
                    <th>TANGGAL PENGAJUAN</th>
                    <th>NOMINAL</th>
                    <th>DESKRIPSI</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <!-- Data Dummy -->
            <tbody>
                <tr>
                    <td><input type="checkbox" class="form-check-input"></td>
                    <td>3082640</td>
                    <td>Dinda Ganda Resmi</td>
                    <td>Dep. Renc. Terima & Gdng</td>
                    <td>Askes Ramayana</td>
                    <td>Siloam Hospitals Purwakarta</td>
                    <td>26 Juni 2024</td>
                    <td>Rp.500.000</td>
                    <td>-</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-trash me-2"></i>Hapus</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Tandai Data</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-file-text me-2"></i>Lihat Berkas</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="form-check-input"></td>
                    <td>3092764</td>
                    <td>Asti Yulianti</td>
                    <td>Bag. Sales Spesifik</td>
                    <td>Askes Ramayana</td>
                    <td>RSIA Sentul Cikampek</td>
                    <td>11 Januari 2024</td>
                    <td>Rp.1.530.000</td>
                    <td>-</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-trash me-2"></i>Hapus</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Tandai Data</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-file-text me-2"></i>Lihat Berkas</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="form-check-input"></td>
                    <td>3203437</td>
                    <td>Wahyu Setyawati</td>
                    <td>Departemen Akuntansi</td>
                    <td>Askes Ramayana</td>
                    <td>RS Lira Medika</td>
                    <td>03 April 2024</td>
                    <td>Rp.335.000</td>
                    <td>-</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-trash me-2"></i>Hapus</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Tandai Data</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-file-text me-2"></i>Lihat Berkas</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="form-check-input"></td>
                    <td>3921982</td>
                    <td>Yeni Rochyaeni</td>
                    <td>Kompartemen SDM</td>
                    <td>Askes Ramayana</td>
                    <td>RS Santo Borromeus Bandung</td>
                    <td>09 Juli 2024</td>
                    <td>Rp.15.938.000</td>
                    <td>-</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-trash me-2"></i>Hapus</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Tandai Data</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-file-text me-2"></i>Lihat Berkas</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="form-check-input"></td>
                    <td>3103045</td>
                    <td>Ahmad Fauzi</td>
                    <td>Dep. Komunikasi prsh</td>
                    <td>Askes Ramayana</td>
                    <td>Rumah Sakit Mandaya</td>
                    <td>12 Maret 2024</td>
                    <td>Rp.2.345.000</td>
                    <td>-</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-trash me-2"></i>Hapus</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Tandai Data</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-file-text me-2"></i>Lihat Berkas</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="form-check-input"></td>
                    <td>3082643</td>
                    <td>Raden Iman Rajasa</td>
                    <td>Dep. Komunikasi prsh</td>
                    <td>Askes Ramayana</td>
                    <td>Primaya Hospitals Karawang</td>
                    <td>11 Maret 2024</td>
                    <td>Rp.748.450</td>
                    <td>-</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-trash me-2"></i>Hapus</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Tandai Data</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-file-text me-2"></i>Lihat Berkas</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="form-check-input"></td>
                    <td>3143275</td>
                    <td>Dewi Yuliana M.</td>
                    <td>Departemen Manajemen</td>
                    <td>Askes Ramayana</td>
                    <td>Siloam Hospitals Purwakarta</td>
                    <td>28 Februari 2024</td>
                    <td>Rp.5.060.000</td>
                    <td>-</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-trash me-2"></i>Hapus</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Tandai Data</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-file-text me-2"></i>Lihat Berkas</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="form-check-input"></td>
                    <td>3052359</td>
                    <td>Asep Yuda A.</td>
                    <td>Dep. Komunikasi prsh</td>
                    <td>Askes Ramayana</td>
                    <td>RS Hermina Karawang</td>
                    <td>15 Mei 2024</td>
                    <td>Rp.3.320.000</td>
                    <td>-</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-trash me-2"></i>Hapus</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Tandai Data</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-file-text me-2"></i>Lihat Berkas</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="form-check-input"></td>
                    <td>3092812</td>
                    <td>Dion Ridwan</td>
                    <td>Dep. Hukadm Prusahaan</td>
                    <td>Askes Ramayana</td>
                    <td>RS Citra Husada</td>
                    <td>21 Mei 2024</td>
                    <td>Rp.960.000</td>
                    <td>-</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-trash me-2"></i>Hapus</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Tandai Data</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-file-text me-2"></i>Lihat Berkas</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="form-check-input"></td>
                    <td>3082678</td>
                    <td>Wila Sari Dewi</td>
                    <td>Dep. Operasional SDM</td>
                    <td>Askes Ramayana</td>
                    <td>Primaya Hospitals Karawang</td>
                    <td>28 Januari 2024</td>
                    <td>Rp.917.000</td>
                    <td>-</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-trash me-2"></i>Hapus</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Tandai Data</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-file-text me-2"></i>Lihat Berkas</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="form-check-input"></td>
                    <td>3123105</td>
                    <td>Fredi Hermawan</td>
                    <td>Dep. Hukadm Prusahaan</td>
                    <td>Askes Ramayana</td>
                    <td>Siloam Hospitals Purwakarta</td>
                    <td>13 April 2024</td>
                    <td>Rp.3.472.000</td>
                    <td>-</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-trash me-2"></i>Hapus</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Tandai Data</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-file-text me-2"></i>Lihat Berkas</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="form-check-input"></td>
                    <td>3123186</td>
                    <td>Moh. Faessel Arfian</td>
                    <td>Dep. Hukadm Prusahaan</td>
                    <td>Askes Ramayana</td>
                    <td>Rumah Sakit Mandaya</td>
                    <td>14 Juni 2024</td>
                    <td>Rp.4.500.000</td>
                    <td>-</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-trash me-2"></i>Hapus</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Tandai Data</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-file-text me-2"></i>Lihat Berkas</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="form-check-input"></td>
                    <td>3052393</td>
                    <td>Widyanto</td>
                    <td>Dep. TKK & Manrisk</td>
                    <td>Askes Ramayana</td>
                    <td>Rumah Sakit Bhakti Husada</td>
                    <td>01 Februari 2024</td>
                    <td>Rp.500.000</td>
                    <td>-</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-trash me-2"></i>Hapus</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Tandai Data</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-file-text me-2"></i>Lihat Berkas</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="form-check-input"></td>
                    <td>3092788</td>
                    <td>Agus Fitrianto</td>
                    <td>Dep. TKK & Manrisk</td>
                    <td>Askes Ramayana</td>
                    <td>Rumah Sakit Bayu Asih</td>
                    <td>19 Maret 2024</td>
                    <td>Rp.1.500.000</td>
                    <td>-</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-trash me-2"></i>Hapus</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Tandai Data</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-file-text me-2"></i>Lihat Berkas</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="form-check-input"></td>
                    <td>3042281</td>
                    <td>Aang Purnama</td>
                    <td>Dep. TKK & Manrisk</td>
                    <td>Askes Ramayana</td>
                    <td>Primaya Hospitals Karawang</td>
                    <td>10 Februari 2024</td>
                    <td>Rp.10.094.000</td>
                    <td>-</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-trash me-2"></i>Hapus</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Tandai Data</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-file-text me-2"></i>Lihat Berkas</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">
<style>
    .dataTables_wrapper .dataTables_length {
        margin-bottom: 1rem;
    }
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1rem;
    }
    .table thead th {
        white-space: nowrap;
    }
    .table th, .table td {
        vertical-align: middle;
    }
    tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }
    .dataTables_processing {
        background: rgba(255,255,255,0.9);
        color: #333;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    // Setup - add a text input to each footer cell
    $('#klaimTable thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#klaimTable thead');

    var table = $('#klaimTable').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        pageLength: 10,           // Menampilkan 15 data per halaman
        lengthMenu: [
            [10, 25, 50, -1], 
            [10, 25, 50, 'Semua']
        ],
        processing: true,         // Menampilkan pesan saat memproses
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>", // Layout DataTable
        initComplete: function () {
            var api = this.api();

            // For each column
            api.columns().eq(0).each(function (colIdx) {
                // Skip checkbox and action columns
                if (colIdx == 0 || colIdx == 9) return;

                // Add input field
                var cell = $('.filters th').eq(colIdx);
                var title = $(cell).text();
                $(cell).html('<input type="text" class="form-control form-control-sm" placeholder="Filter ' + title + '" />');

                // Add filter functionality
                $('input', $('.filters th').eq(colIdx))
                    .on('keyup change', function () {
                        if (api.column(colIdx).search() !== this.value) {
                            api
                                .column(colIdx)
                                .search(this.value)
                                .draw();
                        }
                    });
            });
        },
        language: {
            search: "Pencarian:",
            lengthMenu: "Menampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ Total Data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 Total Data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Tidak ada data yang cocok",
            processing: "Sedang memproses...",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });

    // Handle check all
    $('#checkAll').change(function() {
        $('tbody input[type="checkbox"]').prop('checked', $(this).prop('checked'));
    });
});
</script>
@endpush

@endsection
