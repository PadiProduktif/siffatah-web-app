@extends('layouts.app')
@section('title', 'Berkas Pengobatan')
@section('content')

    <div class="d-flex justify-content-end align-items-center mb-4">
        <h4 class="me-auto">Berkas Pengobatan</h4>
        <a href="" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDataBaru">+ Masukan Data Baru</a>
    </div>
    <button style="margin-bottom: 20px;" id="deleteSelected" class="btn btn-danger">Hapus Terpilih</button>
    <table id="klaimTable" class="display">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>

                <th>ID Badge</th>
                <th>Nama Karyawan</th>
                {{-- <th>Jabatan Karyawan</th> --}}
                <th>Nama Anggota Keluarga</th>
                <th>Hubungan Keluarga</th>
                <th>nominal</th>
                <th>RS/Klinik</th>
                <th>Urgensi</th>
                <th>No Surat RS</th>
                <th>Tanggal Pengobatan</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($obat as $key1 => $value1)
                <tr>
                    {{-- <td>{{ $key1+1 }}</td> --}}
                    <td><input type="checkbox" class="rowCheckbox" value="{{ $value1->id_berkas_pengobatan }}"></td>
                    <td>{{ $value1->id_badge }}</td>
                    <td>{{ $value1->nama_karyawan }}</td>
                    {{-- <td>{{ $value1->jabatan_karyawan }}</td> --}}
                    <td>{{ $value1->nama_anggota_keluarga }}</td>
                    <td>{{ $value1->deskripsi }}</td>
                    <td>{{ format_currency($value1->nominal) }}</td>
                    <td>{{ $value1->rs_klinik }}</td>
                    <td>{{ $value1->urgensi }}</td>
                    <td>{{ $value1->no_surat_rs }}</td>
                    <td>{{ format_date($value1->tanggal_pengobatan) }}</td>
                    <td>{{ $value1->keterangan }}</td>
                    <td>
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
                                        href="#" 
                                        class="dropdown-item" 
                                        data-bs-toggle="modal" 
                                        {{-- data-bs-target="#modalEditBerkas-{{ $data1->id_berkas_pengobatan }}"> --}}
                                        data-bs-target="#modalUpdate-{{ $value1->id_berkas_pengobatan }}">
                                        <i class="bi bi-file-text me-2"></i>Lihat Berkas
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#" onclick="confirmDelete('{{ $value1->id_berkas_pengobatan }}')">
                                        <i class="bi bi-trash me-2"></i>Hapus
                                    </a>
                                    
                                    <!-- Form tersembunyi -->
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

                
                <div class="modal fade" id="modalUpdate-{{ $value1->id_berkas_pengobatan }}" tabindex="-1" aria-labelledby="addKaryawanModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addKaryawanModalLabel">Update Berkas {{ $value1->no_surat_rs }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="/admin/berkas-pengobatan/update/{{ $value1->id_berkas_pengobatan }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="row text-start">

                                        <div class="col-md-4">
                                            <label for="nama-asuransi" class="form-label">Jabatan Karyawan</label>
                                            <input type="text" class="form-control" id="nama-asuransi" name="jabatan_karyawan" value="{{ $value1->jabatan_karyawan }}">
                                        </div>
                                        <div class="col-md-8">
                                            <label for="nama-asuransi" class="form-label">Nama Anggota Keluarga</label>
                                            <input type="text" class="form-control" id="nama-asuransi" name="nama_anggota_keluarga" value="{{ $value1->nama_anggota_keluarga }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="nama-asuransi" class="form-label">Hubungan Keluarga</label>
                                            <input type="text" class="form-control" id="nama-asuransi" name="hubungan_keluarga" value="{{ $value1->hubungan_keluarga }}">
                                        </div>
                
                                        <div class="col-md-4">
                                            <label for="nominal" class="form-label">Nominal</label>
                                            <input type="text" id="nominal" class="form-control" name="nominal" value="{{ $value1->nominal }}">
                                        </div>
                
                                        <div class="col-md-4">
                                            <label for="nominal" class="form-label">RS / Klinik</label>
                                            <input type="text" id="nominal" class="form-control" name="rs_klinik" value="{{ $value1->rs_klinik }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="urgensi" class="form-label">Urgensi</label>
                                            <select class="form-control" id="urgensi" name="urgensi">
                                                <option value="Low" {{ $value1->urgensi == 'Low' ? 'selected' : '' }}>Low</option>
                                                <option value="Medium" {{ $value1->urgensi == 'Medium' ? 'selected' : '' }}>Medium</option>
                                                <option value="High" {{ $value1->urgensi == 'High' ? 'selected' : '' }}>High</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="nominal" class="form-label"> No Surat RS</label>
                                            <input type="text" id="no_surat_rs" class="form-control" name="no_surat_rs" value="{{ $value1->no_surat_rs }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="tanggal-pengajuan" class="form-label">Tanggal Pengobatan</label>
                                            <input type="date" class="form-control" id="tanggal-pengajuan" name="tanggal_pengobatan" value="{{ $value1->tanggal_pengobatan }}">
                                        </div>
                                        <div class="col-12">
                                            <label for="deskripsi" class="form-label">Deskripsi</label>
                                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" >value="{{ $value1->deskripsi }}"</textarea>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <div id="editAttachmentDropzone-{{ $value1->id_berkas_pengobatan }}" class="dropzone" data-files='@json($value1->file_url)'>
                                                <div class="dz-message">Drag & Drop your files here or click to upload</div>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <h6>Daftar File Sebelumnya:</h6>
                                                <ul id="attachmentList-{{ $value1->id_berkas_pengobatan }}" class="list-group">
                                                    @foreach (json_decode($value1->file_url ?? '[]') as $file)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <img
                                                        style="max-width:300px " src="/uploads/BerkasPengobatan/{{ $file }}" alt="{{ $file }}" class="custom-thumbnail">
                                                        <a href="/uploads/BerkasPengobatan/{{ $file }}" target="_blank">{{ $file }}</a>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeExistingFile('{{ $file }}', '{{ $value1->id_berkas_pengobatan }}')">Hapus</button>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="uploaded_files" id="uploadedFilesInput-{{ $value1->id_berkas_pengobatan }}" value="[]">
                                <input type="hidden" id="removedFilesInput-{{ $value1->id_berkas_pengobatan }}" name="removed_files">
                                
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- MODAL ADD --}}
<div class="modal fade" id="modalDataBaru" tabindex="-1" aria-labelledby="modalDataBaruLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addKaryawanModalLabel">Tambah Data Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- <form id="dataForm" action="{{ route('pengajuan-klaim-pengobatan.store') }}" method="POST" enctype="multipart/form-data" > --}}
            <form id="dataForm" action="{{ route('berkas-pengobatan.store') }}" method="POST" enctype="multipart/form-data" >
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Informasi Dasar -->
                        <div class="col-md-4">
                            <label for="id-badge" class="form-label">ID Badge</label>
                            <select class="form-control select2" id="id-badge" name="id_badge" required>
                                <option value="" selected disabled>Pilih Karyawan</option>
                                @foreach($karyawan as $data)
                                    <option value="{{ $data->id_badge }}">{{ $data->nama_karyawan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label for="nama-asuransi" class="form-label">Nama Karyawan</label>
                            <input type="text" class="form-control" id="nama-asuransi" name="nama_karyawan">
                        </div>
                        <div class="col-md-4">
                            <label for="nama-asuransi" class="form-label">Jabatan Karyawan</label>
                            <input type="text" class="form-control" id="nama-asuransi" name="jabatan_karyawan">
                        </div>
                        <div class="col-md-8">
                            <label for="nama-asuransi" class="form-label">Nama Anggota Keluarga</label>
                            <input type="text" class="form-control" id="nama-asuransi" name="nama_anggota_keluarga">
                        </div>
                        <div class="col-md-4">
                            <label for="nama-asuransi" class="form-label">Hubungan Keluarga</label>
                            <input type="text" class="form-control" id="nama-asuransi" name="hubungan_keluarga">
                        </div>

                        <div class="col-md-4">
                            <label for="nominal" class="form-label">Nominal</label>
                            <input type="text" id="nominal" class="form-control" name="nominal">
                        </div>

                        <div class="col-md-4">
                            <label for="nominal" class="form-label">RS / Klinik</label>
                            <input type="text" id="nominal" class="form-control" name="rs_klinik">
                        </div>
                        <div class="col-md-4">
                            <label for="nominal" class="form-label"> Urgensi</label>
                            <select class="form-control" id="urgensi" name="urgensi" required>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="nominal" class="form-label"> No Surat RS</label>
                            <input type="text" id="no_surat_rs" class="form-control" name="no_surat_rs">
                        </div>
                        <div class="col-md-4">
                            <label for="tanggal-pengajuan" class="form-label">Tanggal Pengobatan</label>
                            <input type="date" class="form-control" id="tanggal-pengajuan" name="tanggal_pengobatan" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-12">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" ></textarea>
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
                <p><strong>Tanggal Pengajuan:</strong> <span id="detailTanggalPengajuan"></span></p>
                <p><strong>Nominal:</strong> <span id="detailNominal"></span></p>
                <p><strong>Deskripsi:</strong> <span id="detailDeskripsi"></span></p>
                <p><strong>Attachments:</strong></p>
                <div id="detailAttachment"></div> <!-- Tempat untuk file attachment -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>



{{-- MODAL IMPORT --}}
<div class="modal fade" id="modalDataExcel" tabindex="-1" aria-labelledby="modalDataExcelLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDataExcelLabel">Masukan Data Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form action="{{ route('pengajuan-klaim-pengobatan.upload') }}" method="POST" enctype="multipart/form-data">
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

@endsection

@push('scripts')

    
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    {{-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script> --}}

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
    </script>

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

            // Event untuk Select All Checkbox
            $('#selectAll').on('click', function () {
                var rows = table.rows({ search: 'applied' }).nodes();
                $('input[type="checkbox"].rowCheckbox', rows).prop('checked', this.checked);
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


    </script>


    <script>
    $(document).ready(function () {
            // Fungsi untuk memformat angka menjadi format Rupiah
            function formatRupiah(number) {
                return 'Rp.' + Number(number).toLocaleString('id-ID');
            }

            // Inisialisasi DataTables
            // var table = $('#tableAdmin').DataTable();

            // Event delegation untuk tombol Edit
            // $('#tableAdmin').on('click', '.editBtn', function (event) {
            //     event.stopPropagation(); // Hentikan event klik dari propagasi ke elemen parent (baris)

            //     // Ambil data dari atribut tombol
            //     const id = $(this).data('id');
            //     const id_badge = $(this).data('id_badge') || '-';
            //     const nama_karyawan = $(this).data('nama_karyawan') || '-';
            //     const unit_kerja = $(this).data('unit_kerja') || '-';
            //     const nama_asuransi = $(this).data('asuransi') || '-';
            //     const rumah_sakit = $(this).data('rumah_sakit') || '-';
            //     const tanggal_pengajuan = $(this).data('tanggal_pengajuan') || '-';
            //     const nominal = $(this).data('nominal') || '0'; // Pastikan defaultnya angka '0' jika kosong
            //     const deskripsi = $(this).data('deskripsi') || '-';

            //     // Pastikan nominal adalah string, kemudian format ke Rupiah
            //     const formattedNominal = formatRupiah(String(nominal).replace('Rp.', '').replace(/,/g, ''));

            //     $('#editIdBadge').val(id_badge || '-');
            //     $('#editNamaKaryawan').val(nama_karyawan || '-');
            //     $('#editUnitKerja').val(unit_kerja || '-');
            //     $('#editNamaAsuransi').val(nama_asuransi || '-');
            //     $('#editRumahSakit').val(rumah_sakit || '-');
            //     $('#editTanggalPengajuan').val(tanggal_pengajuan || '-');
            //     $('#editNominal').val(formattedNominal || '-');
            //     $('#editDeskripsi').val(deskripsi || '-');
            //     // Set action form update
            //     $('#editForm').attr('action', '/admin/klaim_pengobatan/update/' + id);

            //     // Tampilkan modal edit
            //     $('#modalEditData').modal('show');
            // });
        });


    $(document).ready(function () {
        // Event delegation untuk tombol Hapus
        // $('#tableAdmin').on('click', '.deleteBtn', function () {
        //     const id = $(this).data('id'); // Ambil ID data

        //     // Tampilkan konfirmasi dengan SweetAlert2
        //     Swal.fire({
        //         title: 'Apakah Anda yakin?',
        //         text: 'Data akan dihapus dan tidak dapat dikembalikan!',
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#d33',
        //         cancelButtonColor: '#3085d6',
        //         confirmButtonText: 'Ya, hapus!',
        //         cancelButtonText: 'Batal'
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             // Jika dikonfirmasi, kirim permintaan hapus ke server
        //             $.ajax({
        //             url: '/admin/klaim_pengobatan/delete/' + id,
        //             type: 'GET', // Ubah dari DELETE ke GET
        //             success: function (response) {
        //                 Swal.fire('Berhasil!', 'Data berhasil dihapus.', 'success');
        //                 location.reload(); // Reload halaman untuk memperbarui data
        //             },
        //             error: function () {
        //                 Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
        //             }
        //         });
        //         }
        //     });
        // });
    });




        $(document).ready(function () {
            // Pilih semua checkbox
            $('#selectAll').on('click', function () {
                $('.rowCheckbox').prop('checked', this.checked);
            });

            // Perbarui checkbox "Pilih Semua" jika ada perubahan pada baris
            // $('#tableAdmin').on('change', '.rowCheckbox', function () {
            //     if (!this.checked) {
            //         $('#selectAll').prop('checked', false);
            //     } else if ($('.rowCheckbox:checked').length === $('.rowCheckbox').length) {
            //         $('#selectAll').prop('checked', true);
            //     }
            // });

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
                            url: '/admin/klaim_pengobatan/delete-multiple', // Endpoint untuk hapus data
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
                url: "/berkas-pengobatan/upload-temp", // Endpoint sementara untuk upload
                paramName: "file",
                headers: {
                    'X-CSRF-TOKEN': token
                },
                maxFiles: 5,
                maxFilesize: 5, // 5MB
                acceptedFiles: "image/*,.pdf,.doc,.docx,.xls,.xlsx",
                addRemoveLinks: true,
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
                                url: "/berkas-pengobatan/delete-temp",
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
        let existingFiles1 = [];
        // Konfigurasi Dropzone
        

        // Fungsi untuk memperbarui input hidden
        function updateHiddenInput() {
                $('#uploadedFilesInput').val(JSON.stringify(uploadedFiles));
                console.log("Updated Uploaded Files Input:", $('#uploadedFilesInput').val());
            }

            function updateHiddenInput(modalId) {
        const uploadedFiles = window[`uploadedFiles_${modalId}`] || [];
        const removedFiles = window[`removedFiles_${modalId}`] || [];
        document.getElementById(`uploadedFilesInput-${modalId}`).value = JSON.stringify(uploadedFiles);
        document.getElementById(`removedFilesInput-${modalId}`).value = JSON.stringify(removedFiles);
        console.log(`Updated hidden inputs for modal ${modalId}:`, {
            uploadedFiles,
            removedFiles
        });
    }

    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('shown.bs.modal', function () {
            const modalId = modal.getAttribute('id').split('-')[1];
            const dropzoneId = `editAttachmentDropzone-${modalId}`;
            const dropzoneElement = document.querySelector(`#${dropzoneId}`);

            // Inisialisasi array dinamis untuk modal ini
            window[`uploadedFiles_${modalId}`] = window[`uploadedFiles_${modalId}`] || [];
            window[`removedFiles_${modalId}`] = window[`removedFiles_${modalId}`] || [];

            if (!dropzoneElement) {
                console.error(`Dropzone element not found: ${dropzoneId}`);
                return;
            }

            if (!Dropzone.instances.some(dz => dz.element.id === dropzoneId)) {
                console.log(`Initializing Dropzone for: ${dropzoneId}`);

                new Dropzone(`#${dropzoneId}`, {
                    url: "/berkas-pengobatan/upload-temp",
                    paramName: "file",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    maxFilesize: 5,
                    acceptedFiles: "image/*,.pdf,.doc,.docx,.xls,.xlsx",
                    addRemoveLinks: true,
                    dictRemoveFile: "Hapus File",

                    success: function (file, response) {
                        if (response && response.fileName) {
                            console.log(`File uploaded successfully in modal ${modalId}:`, response.fileName);
                            window[`uploadedFiles_${modalId}`].push(response.fileName);
                            updateHiddenInput(modalId);
                        } else {
                            console.error(`File upload failed for modal ${modalId}`);
                        }
                    },

                    removedfile: function (file) {
                        console.log("Removing file:", file);

                        // Periksa apakah file adalah file baru atau file lama
                        if (file.serverFileName) {
                            // Jika file sudah di-upload (file baru), kirim request untuk hapus file
                            $.ajax({
                                url: "/berkas-pengobatan/delete-temp",
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
                        console.log(`Initializing existing files for modal ${modalId}`);
                        const existingFilesFromServer = dropzoneElement.getAttribute('data-files') || '[]';
                        let existingFiles = [];
                        try {
                            existingFiles = JSON.parse(existingFilesFromServer);
                        } catch (error) {
                            console.error(`Invalid JSON in data-files for modal ${modalId}:`, error);
                        }

                        existingFiles.forEach(file => {
                            const mockFile = { name: file, size: 12345, serverFileName: file };
                            this.emit("addedfile", mockFile);
                            this.emit("thumbnail", mockFile, `/uploads/BerkasPengobatan/${file}`);
                            this.emit("complete", mockFile);
                        });
                    }
                });
            }
        });
    });


        function removeExistingFile(fileName, pengajuanId) {
            if (confirm('Apakah Anda yakin ingin menghapus file ini?')) {
                $.ajax({
                    url: '/berkas-pengobatan/delete-temp',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        fileName: fileName,
                        pengajuanId: pengajuanId
                    },
                    success: function (response) {
                        console.log("File berhasil dihapus:", response);
                        
                        // Tambahkan file yang dihapus ke array remove_files
                        let removedFilesInput = $(`#removedFilesInput-${pengajuanId}`);
                        let removedFiles = removedFilesInput.val() ? JSON.parse(removedFilesInput.val()) : [];
                        removedFiles.push(fileName);
                        removedFilesInput.val(JSON.stringify(removedFiles)); // Simpan kembali ke input hidden
                        
                        // Hapus elemen dari daftar
                        $(`#attachmentList-${pengajuanId} li:contains('${fileName}')`).remove();
                    },
                    error: function (error) {
                        console.error("Gagal menghapus file:", error);
                    }
                });
            }
        }
        
    </script>
@endpush