@extends('layouts.app')
@section('title', 'Restitusi')
@section('content')

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>RESTITUSI KARYAWAN</h2>
                <span>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importKaryawanModal">
                        <i class="bi bi-plus-lg"></i> Import
                    </button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addKaryawanModal">
                        <i class="bi bi-plus-lg"></i> Tambah Data Baru
                    </button>
                </span>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table id="klaimTable" class="table">
                <thead class="table-light">
                    <tr class="text-center">
                        <th class="align-middle">NO</th>
                        <th class="align-middle">Badge</th>
                        <th class="align-middle">Karyawan</th>
                        <th class="align-middle">Deskripsi</th>
                        <th class="align-middle">Nomor</th>
                        <th class="align-middle">Tanggal</th>
                        <th class="align-middle">Nominal</th>
                        <th class="align-middle">Urgensi</th>
                        <th class="align-middle">Status</th>
                        <th class="align-middle">Opt</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($restitusi as $key1 => $data1)
                        <tr class="text-center ">
                            <td>{{ $key1+1 }}</td>
                            <td>{{ $data1->id_badge }}</td>
                            <td class="text-start">{{ $data1->nama_karyawan }}</td>
                            <td class="text-start">{{ $data1->keterangan_pengajuan }}, {{ $data1->deskripsi }}</td>
                            <td>{{ $data1->no_surat_rs }}</td>
                            <td>{{ format_date($data1->tanggal_pengobatan) }}</td>
                            <td class="text-end">{{ format_currency($data1->nominal) }}</td>
                            <td>{{ $data1->urgensi }}</td>
                            <td>
                                @if ($data1->status_pengajuan == 1)
                                    <span class="badge bg-secondary">Menunggu</span>
                                @elseif ($data1->status_pengajuan == 2)
                                    <span class="badge bg-warning">Verifikasi DR</span>
                                @elseif ($data1->status_pengajuan == 3)
                                    <span class="badge bg-success">Verifikasi VP</span>
                                @else
                                    <span class="badge bg-danger">Tidak Diketahui</span>
                                @endif
                            </td>
                            <td>
                                @if (auth()->user()->role === 'superadmin' || auth()->user()->role === 'tko')
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a 
                                                    href="#" 
                                                    class="dropdown-item" 
                                                    data-bs-toggle="modal" 
                                                    {{-- data-bs-target="#modalEditBerkas-{{ $data1->id_pengajuan }}"> --}}
                                                    data-bs-target="#modalUpdate-{{ $data1->id_pengajuan }}">
                                                    <i class="bi bi-file-text me-2"></i>Lihat Berkas
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" onclick="confirmDelete('{{ $data1->id_pengajuan }}')">
                                                    <i class="bi bi-trash me-2"></i>Hapus
                                                </a>
                                                
                                                <!-- Form tersembunyi -->
                                                <form id="delete-form-{{ $data1->id_pengajuan }}" 
                                                    action="/admin/restitusi_karyawan/delete/{{ $data1->id_pengajuan }}" 
                                                    method="POST" 
                                                    class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="modal fade" id="modalUpdate-{{ $data1->id_pengajuan }}" tabindex="-1" aria-labelledby="addKaryawanModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addKaryawanModalLabel">Update Data</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="/admin/restitusi_karyawan/tambah" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        {{ $data1->id_pengajuan }}
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                
                                    <div class="d-flex">
                                    @if (auth()->user()->role === 'dr_hph' && $data1->status_pengajuan === 1)
                                        <form action="{{ route('approval-dr', ['id' => $data1->id_pengajuan]) }}" method="POST" class="w-100">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-warning w-100">Approve</button>
                                        </form>
                                    @elseif (auth()->user()->role === 'vp_osdm' && $data1->status_pengajuan === 2)
                                        <form action="{{ route('approval-vp', ['id' => $data1->id_pengajuan]) }}" method="POST" class="w-100">
                                            @csrf
                                            @method('PUT') <!-- Menggunakan metode PUT -->
                                            <button type="submit" class="btn btn-sm btn-success w-100">Approve</button>
                                        </form>
                                    @endif
                                        {{-- <button class="btn btn-sm btn-success w-100 mx-1">Approve</button>
                                        <button class="btn btn-sm btn-danger w-100 mx-1">Disapprove</button> --}}
                                        <button class="btn btn-sm btn-info mx-1">Berkas</button>
                                    </div>
                                @endif
                                
                            </td>
                        </tr>

                        
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data karyawan</td>
                        </tr>
                    @endforelse
                    
                </tbody>
            </table>
        
        </div>
    </div>

    <!-- MODAL ADD -->
    <div class="modal fade" id="addKaryawanModal" tabindex="-1" aria-labelledby="addKaryawanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKaryawanModalLabel">Tambah Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/admin/restitusi_karyawan/tambah" method="POST">
                {{-- <form action="{{ route('admin.restitusi_karyawan.store') }}" method="POST"> --}}
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="id_badge" class="form-label">ID Badge</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="id_badge" 
                                    name="id_badge" 
                                    value="{{ old('id_badge', auth()->user()->role === 'tko' ? auth()->user()->username : '') }}" 
                                    placeholder="{{ auth()->user()->role === 'tko' ? auth()->user()->username : 'Masukkan ID Badge' }}" 
                                    {{ auth()->user()->role === 'tko' ? 'disabled' : '' }} 
                                    required
                                >
                            </div>
                            

                            <div class="col-md-3">
                                <label for="tanggal_pengobatan" class="form-label">Tanggal pengobatan</label>
                                <input type="date" class="form-control" id="tanggal_pengobatan" name="tanggal_pengobatan"
                                value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label for="no_surat_rs" class="form-label">No. Surat</label>
                                <input type="text" class="form-control" id="no_surat_rs" name="no_surat_rs" required>
                            </div>
                            <div class="col-md-3">
                                <label for="urgensi" class="form-label">Urgensi</label>
                                <select class="form-control" id="urgensi" name="urgensi">
                                    <option value="" selected disabled>Pilih urgensi</option>
                                    <option value="Low" selected>Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="High">High</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="rumah_sakit" class="form-label">Rumah sakit</label>
                                <input type="text" class="form-control" id="rumah_sakit" name="rumah_sakit" required>
                            </div>
                            <div class="col-md-3">
                                <label for="nominal" class="form-label">Nominal</label>
                                <input type="number" class="form-control" id="nominal" name="nominal" step="any" required>
                            </div>

                            <div class="col-12">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                            </div>
                            <div class="col-12">
                                <label for="keterangan_pengajuan" class="form-label">Keterangan pengajuan</label>
                                <textarea class="form-control" id="keterangan_pengajuan" name="keterangan_pengajuan" rows="3" required></textarea>
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

    {{-- MODAL IMPORT --}}

@endsection



@push('scripts')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        // Setup - add a text input to each footer cell
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
                    if (colIdx == 9) return;

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
    </script>
@endpush