@extends('layouts.app')
@section('title', 'Invoice')
@section('content')

<style>
    .custom-thumbnail {
        border-radius: 5px; /* Menambahkan sudut melengkung */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Menambahkan bayangan */
    }
    .select2-container .select2-selection--single {
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.375rem;
    }
</style>

{{-- MODAL ADD --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-end align-items-center mb-4">
                <h4 class="me-auto">Berkas Rumah Sakit - Invoice {{ $dataTagihan['no_surat'] }}</h4>
                
                @if (auth()->user()->role === 'superadmin')
                    <a href="" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDataBaru">+ Masukan Peserta Baru</a>
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalDataBaru" tabindex="-1" aria-labelledby="modalDataBaruLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKaryawanModalLabel">Tambah Invoice Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="dataForm" action="{{ route('berkas-pengobatan.store') }}" method="POST" enctype="multipart/form-data" >
                    @csrf
                    
                    <input type="number" class="d-none" name="id_tagihan" value="{{ $dataTagihan->id_tagihan }}">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-lg-3 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Nama Karaywan</label>
                                    <select id="form-add-karyawan" class="form-select select-2-single-modal" name="id_badge">
                                        @foreach($dataKaryawan as $data)
                                            <option value="{{ $data->id_badge }}">{{ $data->nama_karyawan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Nama Pasien</label>
                                    <select id="form-add-keluarga" class="form-control select-2-single-modal" name="nama_anggota_keluarga">
                                        <option value="">Pilih pasien</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3 col-sm-12">
                                <label for="urgensi" class="form-label"> Urgensi</label>
                                <select class="form-control" id="urgensi" name="urgensi" required>
                                    <option value="Low" selected>Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="High">High</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-sm-12">
                                <label for="tanggal-pengajuan" class="form-label">Tanggal Pengobatan</label>
                                <input type="date" class="form-control" id="tanggal-pengajuan" name="tanggal_invoice" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-lg-12">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Optional"></textarea>
                            </div>

                            <div class="col-lg-12">
                                <h5>Rincian Biaya Pengajuan</h5>
                                <div id="biayaPengajuanContainer">
                                    <div class="row biaya-pengajuan-item mb-3">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control nominal-pengajuan" name="nominal_pengajuan[]" placeholder="Nominal">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control deskripsi-pengajuan" name="deskripsi_pengajuan[]" placeholder="Deskripsi">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-remove">Hapus</button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="addBiayaPengajuan" class="btn btn-primary w-100 mt-2">Tambah Rincian Biaya</button>
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
{{-- ./ MODAL ADD --}}

<div class="row">
    <div class="col-lg-12">
        <table id="klaimTable" class="display">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Badge</th>
                    <th class="text-center">Karyawan</th>
                    <th class="text-center">Pasien</th>
                    <th class="text-center">Nominal</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataObat as $key1 => $value1)
                    <tr>
                        <td class="text-center">{{ $key1+1 }}</td>
                        <td class="text-center">{{ $value1->id_badge }}</td>
                        <td>{{ $value1->nama_karyawan }}</td>
                        <td>{{ $value1->pasien }}</td>
                        <td class="text-end">{{ format_currency($value1->total_biaya) }}</td>
                        <td class="text-center">{{ format_date($value1->tanggal_invoice) }}</td>
                        <td>
                            {{ $value1->totalRincian }} Rincian Biaya,
                            {{ $value1->keterangan }}
                        </td>
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
                                    @if ($value1->status == 1)
                                        <li>
                                            <a class="dropdown-item text-success" href="#" data-bs-toggle="modal" data-bs-target="#approveModal-{{ $value1->id_berkas_pengobatan }}">
                                                @if (auth()->user()->role === 'superadmin')
                                                    <i class="bi bi-check me-2"></i>Screening by SDM
                                                @elseif (auth()->user()->role === 'dr_hph')
                                                    <i class="bi bi-check me-2"></i>Screening by Dr. HPH
                                                @elseif (auth()->user()->role === 'vp_osdm')
                                                    <i class="bi bi-check me-2"></i>Screening by VP
                                                @endif
                                            </a>
                                        </li>
                                    @endif
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

                            <!-- Modal -->
                            <div class="modal fade" id="approveModal-{{ $value1->id_berkas_pengobatan }}" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="approveModalLabel">Approve Screening</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-lg-3 col-sm-12 mb-3">
                                                    <label for="urgensi" class="form-label">Urgensi</label>
                                                    <select class="form-select" id="urgensi" name="urgensi" required>
                                                        <option value="Low" {{ $value1->urgensi == 'Low' ? 'selected' : '' }}>Low</option>
                                                        <option value="Medium" {{ $value1->urgensi == 'Medium' ? 'selected' : '' }}>Medium</option>
                                                        <option value="High" {{ $value1->urgensi == 'High' ? 'selected' : '' }}>High</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-3 col-sm-12 mb-3">
                                                    <label for="tanggal-pengajuan" class="form-label">Tanggal Pengobatan</label>
                                                    <input type="date" class="form-control" id="tanggal-pengajuan" name="tanggal_invoice" value="{{ $value1->tanggal_invoice }}">
                                                </div>
                                                <div class="col-lg-12 mb-3">
                                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Optional">{{ $value1->deskripsi }}</textarea>
                                                </div>
                                        
                                                <div class="col-lg-12 mb-2">
                                                    <h5>Rincian Biaya Pengajuan</h5>
                                                    <div id="UpdateBiayaPengajuanContainer">
                                                        @foreach ($dataRincianBiayaRAW as $key2 => $value2)
                                                            <div class="row biaya-pengajuan-item mb-3 align-items-end">
                                                                <div class="col-md-5">
                                                                    <input type="text" class="form-control nominal-pengajuan" name="nominal_pengajuan[]" value="{{ $value2->nominal_pengajuan }}">
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <input type="text" class="form-control deskripsi-pengajuan" name="deskripsi_pengajuan[]" value="{{ $value2->deskripsi_biaya }}">
                                                                </div>
                                                                <div class="col-md-2 text-end">
                                                                    <button type="button" class="btn btn-danger btn-remove">Hapus</button>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <button type="button" id="UpdateBiayaPengajuan" class="btn btn-primary w-100 mt-2">Tambah Rincian Biaya</button>
                                                </div>
                                        
                                                <div class="col-md-12 mb-3">
                                                    <div id="editAttachmentDropzone" class="dropzone">
                                                        <div class="dz-message">Drag & Drop your files here or click to upload</div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-primary" onclick="document.getElementById('approve-form-{{ $value1->id_berkas_pengobatan }}').submit();">Approve By Screening</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                    

                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        async function formAddKeluarga() {
            // Ambil nilai dari input
            const badge = $('#form-add-karyawan').val();

            // Validasi apakah badge kosong
            if (!badge) {
                alert("Badge harus diisi.");
                return;
            }

            try {
                // Panggil AJAX untuk mengirim data
                const result = await $.ajax({
                    type: "POST",
                    url: "/get-keluarga", // URL ke route Laravel
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Kirim CSRF token
                    },
                    data: { badge }, // Kirim data badge
                });

                // Proses data respons
                if (result.karyawan || result.keluarga) {
                    const karyawanOptions = result.karyawan.map(data =>
                        `<option value="${data.id_badge}">${data.nama_lengkap}</option>`
                    ).join('');
                    const keluargaOptions = result.keluarga.map(data =>
                        `<option value="${data.id_non_karyawan}">${data.nama}</option>`
                    ).join('');
                    const optionsHTML = `
                        ${karyawanOptions}
                        ${keluargaOptions}
                    `;

                    // // Isi dropdown dengan opsi baru
                    $("#form-add-keluarga").html(optionsHTML);
                } else {
                    alert("Data tidak ditemukan.");
                }
            } catch (error) {
                alert("Gagal mengambil data. Silakan coba lagi.");
            }
        }
        
        // Function to initialize Cleave.js for Rupiah format
        function initializeCleaveForNominal() {
            document.querySelectorAll('.nominal-pengajuan').forEach(input => {
                if (!input.cleaveInstance) {
                    input.cleaveInstance = new Cleave(input, {
                        numeral: true,
                        numeralThousandsGroupStyle: 'thousand',
                        prefix: 'Rp ',
                        rawValueTrimPrefix: true
                    });
                }
            });
        }

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

        $(document).ready(function () {
            initializeCleaveForNominal();
            formAddKeluarga()
            $('body').on("change","#form-add-karyawan", function() {
                formAddKeluarga()
            });

            // 
                // Add new biaya pengajuan row
                document.getElementById('addBiayaPengajuan').addEventListener('click', function () {
                    const container = document.getElementById('biayaPengajuanContainer');
                    const newItem = document.createElement('div');
                    newItem.classList.add('row', 'biaya-pengajuan-item', 'mb-3');
                    newItem.innerHTML = `
                        <div class="col-md-5">
                            <input type="text" class="form-control nominal-pengajuan" name="nominal_pengajuan[]" placeholder="Nominal">
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control deskripsi-pengajuan" name="deskripsi_pengajuan[]" placeholder="Deskripsi">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-remove">Hapus</button>
                        </div>
                    `;
                    container.appendChild(newItem);

                    // Reinitialize Cleave.js for the new input
                    initializeCleaveForNominal();
                });

                // Remove biaya pengajuan row
                document.getElementById('biayaPengajuanContainer').addEventListener('click', function (e) {
                    if (e.target.classList.contains('btn-remove')) {
                        e.target.closest('.biaya-pengajuan-item').remove();
                    }
                });
            // 

            // 
                // Add new biaya pengajuan row
                document.getElementById('UpdateBiayaPengajuan').addEventListener('click', function () {
                    const container = document.getElementById('UpdateBiayaPengajuanContainer');
                    const newItem = document.createElement('div');
                    newItem.classList.add('row', 'biaya-pengajuan-item', 'mb-3');
                    newItem.innerHTML = `
                        <div class="col-md-5">
                            <input type="text" class="form-control nominal-pengajuan" name="nominal_pengajuan[]" placeholder="Nominal">
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control deskripsi-pengajuan" name="deskripsi_pengajuan[]" placeholder="Deskripsi">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-remove">Hapus</button>
                        </div>
                    `;
                    container.appendChild(newItem);

                    // Reinitialize Cleave.js for the new input
                    initializeCleaveForNominal();
                });

                // Remove biaya pengajuan row
                document.getElementById('UpdateBiayaPengajuanContainer').addEventListener('click', function (e) {
                    if (e.target.classList.contains('btn-remove')) {
                        e.target.closest('.biaya-pengajuan-item').remove();
                    }
                });
            // 
            
            $('.select-2-single-modal').select2({
                dropdownParent: $('#modalDataBaru'),
                placeholder: "Pilih Opsi", // Placeholder jika diperlukan
                allowClear: false,         // Menambahkan opsi clear
                width: '100%' // Atur lebar dropdown menjadi 100% dari elemen induknya
                // theme: 'bootstrap-5', // Menggunakan tema Bootstrap 5
            });
            
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
                        if (colIdx == 7) {
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

        // DROPZONE
            Dropzone.autoDiscover = false;

            let oldFiles = []; // Array untuk menyimpan file lama
            let uploadedFiles = []; // Menyimpan nama file yang sudah diupload ke server
            let existingFiles1 = []; // Menyimpan nama file yang sudah ada di database

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const attachmentDropzoneElement = document.querySelector('#attachmentDropzone');
            const editAttachmentDropzoneElement = document.querySelector('#editAttachmentDropzone');

            if (attachmentDropzoneElement) {
                // Check if Dropzone is already initialized
                if (!attachmentDropzoneElement.dropzone) {
                    const attachmentDropzone = new Dropzone(attachmentDropzoneElement, {
                        url: "/restitusi_karyawan/upload-temp", // Endpoint for upload
                        headers: {
                            'X-CSRF-TOKEN': token // Include CSRF token in headers
                        },
                        // Additional Dropzone options can be added here
                    });
                } else {
                    console.warn('Dropzone is already attached to this element.');
                }
            }

            if (editAttachmentDropzoneElement) {
                // Check if Dropzone is already initialized
                if (!editAttachmentDropzoneElement.dropzone) {
                    const attachmentDropzone = new Dropzone(editAttachmentDropzoneElement, {
                        url: "/restitusi_karyawan/upload-temp", // Endpoint for upload
                        headers: {
                            'X-CSRF-TOKEN': token // Include CSRF token in headers
                        },
                        // Additional Dropzone options can be added here
                    });
                } else {
                    console.warn('Dropzone is already attached to this element.');
                }
            }
        
            // if (document.querySelector('#attachmentDropzone')) {
            //     const attachmentDropzone = new Dropzone("#attachmentDropzone", {
            //         url: "/restitusi_karyawan/upload-temp", // Endpoint sementara untuk upload
            //         paramName: "file",
            //         headers: {
            //             'X-CSRF-TOKEN': token
            //         },
            //         maxFiles: 5,
            //         maxFilesize: 2, // 5MB
            //         acceptedFiles: "image/*,.pdf,.doc,.docx,.xls,.xlsx",
            //         addRemoveLinks: true,
            //         dictRemoveFile: "Hapus File",
            //         dictDefaultMessage: "Drag & Drop your files here or click to upload",

            //         init: function () {
            //             // Saat file berhasil diunggah
            //             this.on("success", function (file, response) {
            //                 console.log("File upload response:", response);

            //                 if (response && response.fileName) {
            //                     file.uploadedFileName = response.fileName; // Simpan nama file di objek Dropzone file
            //                     uploadedFiles.push(response.fileName); // Tambahkan nama file ke array uploadedFiles

            //                     console.log("Uploaded file added:", response.fileName);
            //                     console.log("Uploaded Files Array:", uploadedFiles);

            //                     // Perbarui input hidden dengan file yang diunggah
            //                     document.getElementById("uploadedFilesInput").value = JSON.stringify(uploadedFiles);
            //                 } else {
            //                     console.error("Error: No fileName in response:", response);
            //                 }
            //             });

            //             // Saat file dihapus dari Dropzone
            //             this.on("removedfile", function (file) {
            //                 console.log("File removed:", file);

            //                 // Pastikan uploadedFileName tersedia
            //                 if (file.uploadedFileName) {
            //                     console.log("Removing file from server:", file.uploadedFileName);

            //                     // Tambahkan file ke array removedFiles
            //                     removedFiles.push(file.uploadedFileName);

            //                     // Hapus file dari array uploadedFiles
            //                     uploadedFiles = uploadedFiles.filter(f => f !== file.uploadedFileName);

            //                     console.log("Updated Uploaded Files:", uploadedFiles);
            //                     console.log("Removed Files Array:", removedFiles);

            //                     // Perbarui input hidden untuk uploaded_files dan removed_files
            //                     document.getElementById("uploadedFilesInput").value = JSON.stringify(uploadedFiles);
            //                     document.getElementById("removedFilesInput").value = JSON.stringify(removedFiles);

            //                     // Kirim AJAX request untuk menghapus file di server
            //                     $.ajax({
            //                         url: "/restitusi_karyawan/delete-temp",
            //                         type: "POST",
            //                         data: {
            //                             _token: token,
            //                             fileName: file.uploadedFileName
            //                         },
            //                         success: function (response) {
            //                             console.log("File successfully removed from server:", response);
            //                         },
            //                         error: function (error) {
            //                             console.error("Failed to delete file on server:", error);
            //                         }
            //                     });
            //                 } else {
            //                     console.warn("File not uploaded to server, skipping removal.");
            //                 }
            //             });
            //         }
            //     });
            // }

            // function removeExistingFile(fileName, pengajuanId) {
            //     if (confirm('Apakah Anda yakin ingin menghapus file ini?')) {
            //         $.ajax({
            //             url: '/restitusi_karyawan/delete-temp',
            //             method: 'POST',
            //             data: {
            //                 _token: $('meta[name="csrf-token"]').attr('content'),
            //                 fileName: fileName,
            //                 pengajuanId: pengajuanId
            //             },
            //             success: function (response) {
            //                 console.log("File berhasil dihapus:", response);
                            
            //                 // Tambahkan file yang dihapus ke array remove_files
            //                 let removedFilesInput = $(`#removedFilesInput-${pengajuanId}`);
            //                 let removedFiles = removedFilesInput.val() ? JSON.parse(removedFilesInput.val()) : [];
            //                 removedFiles.push(fileName);
            //                 removedFilesInput.val(JSON.stringify(removedFiles)); // Simpan kembali ke input hidden
                            
            //                 // Hapus elemen dari daftar
            //                 $(`#attachmentList-${pengajuanId} li:contains('${fileName}')`).remove();
            //             },
            //             error: function (error) {
            //                 console.error("Gagal menghapus file:", error);
            //             }
            //         });
            //     }
            // }

            // function updateHiddenInput(modalId) {
            //     const uploadedFiles = window[`uploadedFiles_${modalId}`] || [];
            //     const removedFiles = window[`removedFiles_${modalId}`] || [];
            //     document.getElementById(`uploadedFilesInput-${modalId}`).value = JSON.stringify(uploadedFiles);
            //     document.getElementById(`removedFilesInput-${modalId}`).value = JSON.stringify(removedFiles);
            //     console.log(`Updated hidden inputs for modal ${modalId}:`, {
            //         uploadedFiles,
            //         removedFiles
            //     });
            // }
        // ./ DROPZONE
    </script>
@endpush