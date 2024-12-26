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
        <div class="card-body">

            
            <table id="klaimTable" class="table">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>NO</th>
                        <th>Badge</th>
                        <th>Karyawan</th>
                        <th>Deskripsi</th>
                        <th>Nomor Surat</th>
                        <th>Tanggal Surat</th>
                        <th>Nominal</th>
                        <th>Urgency</th>
                        <th>Status</th>
                        <th>Opt</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($restitusi as $key1 => $data1)
                        <tr class="text-center ">
                            <td>{{ $key1+1 }}</td>
                            <td>{{ $data1->id_badge }}</td>
                            <td class="text-start">{{ $data1->nama_karyawan }}</td>
                            <td class="text-start">{{ $data1->deskripsi }}</td>
                            <td>{{ $data1->no_surat_rs }}</td>
                            <td>{{ format_date($data1->tanggal_pengobatan) }}</td>
                            <td class="text-end">{{ format_currency($data1->nominal) }}</td>
                            <td>{{ $data1->urgensi }}</td>
                            <td>
                                @if ($data1->status_pengajuan == 1)
                                    <span class="badge bg-warning">Menunggu</span>
                                @elseif ($data1->status_pengajuan == 2)
                                    <span class="badge bg-success">Diverifikasi</span>
                                @elseif ($data1->status_pengajuan == 3)
                                    <span class="badge bg-danger">Ditolak</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Diketahui</span>
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
                                                href="#" 
                                                class="dropdown-item" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalLihatBerkas-{{ $data1->id_pengajuan }}">
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
                            </td>
                        </tr>
                    @empty
                        <div class="modal fade" id="modalLihatBerkas-{{ $data1->id_pengajuan }}" tabindex="-1" aria-labelledby="modalLabel-{{ $data1->id_pengajuan }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel-{{ $data1->id_pengajuan }}">Detail Berkas</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>ID Pengajuan:</strong> {{ $data1->id_pengajuan }}</p>
                                        <p><strong>Nama Karyawan:</strong> {{ $data1->nama_karyawan }}</p>
                                        <p><strong>Deskripsi:</strong> {{ $data1->deskripsi }}</p>
                                        <p><strong>Nominal:</strong> {{ number_format($data1->nominal, 0, ',', '.') }}</p>
                                        <p><strong>Urgensi:</strong> {{ $data1->urgensi }}</p>
                                        <p><strong>Tanggal Pengobatan:</strong> {{ $data1->tanggal_pengobatan }}</p>
                                        <!-- Tambahkan detail lain sesuai kebutuhan -->
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <a href="/admin/master_data_karyawan/detail/{{ $data1->id_pengajuan }}" class="btn btn-primary">
                                            Lihat Berkas Lengkap
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                    
                    
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
                                    value="{{ auth()->user()->role === 'tko' ? auth()->user()->username : '' }}" 
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
    <div class="modal fade" id="importKaryawanModal" tabindex="-1" aria-labelledby="modalDataExcelLabel" aria-hidden="true">

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