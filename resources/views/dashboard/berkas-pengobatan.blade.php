@extends('layouts.app')
@section('title', 'Tagihan Rumah Sakit')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="d-flex justify-content-end align-items-center mb-4">
            <h4 class="me-auto">Tagihan Rumah Sakit</h4>
            
            @if (auth()->user()->role === 'superadmin')
                <a href="" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDataBaru">+ Masukan Invoice Baru</a>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <table id="klaimTable" class="display">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">RS/Klinik</th>
                    <th class="text-center">Surat</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Nominal</th>
                    <th class="text-center">Urgensi</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataTagihan as $key1 => $value1)
                    <tr>
                        <td class="text-center">{{ $key1+1 }}</td>
                        <td>{{ $value1->rs_klinik }}</td>
                        <td>{{ $value1->no_surat }}</td>
                        <td>{{ $value1->keterangan }}</td>
                        <td class="text-end">{{ format_currency($value1->nominal) }}</td>
                        <td class="text-center">{{ $value1->urgensi }}</td>
                        <td>{{ format_date($value1->tanggal_invoice) }}</td>
                        <td>
                            <span class="badge bg-info">2</span>
                            @if ($value1->status == 1)
                                <span class="badge bg-secondary">Menunggu</span>
                            @elseif ($value1->status == 2)
                                <span class="badge bg-warning">Verifikasi DR</span>
                            @elseif ($value1->status == 3)
                                <span class="badge bg-success">Verifikasi VP</span>
                            @else
                                <span class="badge bg-danger">Tidak Diketahui</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a 
                                            href="{{ route('berkas-tagihan-rumah-sakit.show', $value1->id_tagihan) }}" 
                                            class="dropdown-item">
                                            <i class="bi bi-file-text me-2"></i>Lihat Berkas
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#" onclick="confirmDelete('{{ $value1->id_berkas_pengobatan }}')">
                                            <i class="bi bi-trash me-2"></i>Hapus
                                        </a>
                                        
                                        <form id="delete-form-{{ $value1->id_berkas_pengobatan }}" 
                                            action="/admin/berkas-pengobatan/delete/{{ $value1->id_berkas_pengobatan }}" 
                                            method="POST" 
                                            class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

{{-- MODAL ADD --}}
<div class="modal fade" id="modalDataBaru" tabindex="-1" aria-labelledby="modalDataBaruLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addKaryawanModalLabel">Tambah Invoice Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="dataForm" action="{{ route('tagihan-baru.store') }}" method="POST" enctype="multipart/form-data" >
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="rs-klinik" class="form-label">RS / Klinik</label>
                            <input type="text" id="rs-klinik" class="form-control" name="rs_klinik" placeholder="Isi RS/Klinik">
                        </div>
                        <div class="col-md-4">
                            <label for="no-surat" class="form-label"> No Surat RS</label>
                            <input type="text" id="no-surat" class="form-control" name="no_surat">
                        </div>
                        <div class="col-md-2">
                            <label for="urgensi" class="form-label"> Urgensi</label>
                            <select class="form-control" id="urgensi" name="urgensi" required>
                                <option value="Low" selected>Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="tanggal-pengajuan" class="form-label">Tanggal Pengobatan</label>
                            <input type="date" class="form-control" id="tanggal-pengajuan" name="tanggal_invoice" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-12">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Optional"></textarea>
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

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#klaimTable thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#klaimTable thead');
            
            var table = $('#klaimTable').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                pageLength: 10, // Menampilkan 10 data per halaman
                lengthMenu: [
                    [10, 25, 50, -1], 
                    [10, 25, 50, 'Semua']
                ],
                processing: true, // Menampilkan pesan saat memproses
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>", // Layout DataTable
                initComplete: function () {
                    var api = this.api();

                    // For each column
                    api.columns().eq(0).each(function (colIdx) {
                        // Skip action column (misalnya kolom terakhir yang berisi tombol aksi)
                        // if (colIdx == 9) return;
                        
                        // Skip action column (misalnya kolom terakhir yang berisi tombol aksi)
                        if (colIdx == 8) {
                            // Kosongkan kolom jika colIdx == 12
                            var cell = $('.filters th').eq(colIdx);
                            $(cell).html(''); // Atau bisa menggunakan $(cell).html('&nbsp;'); untuk menampilkan spasi
                            return; // Keluar dari fungsi ini
                        }

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
        });
    </script>
@endpush