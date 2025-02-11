@extends('layouts.app')
@section('title', 'Master - Kelengkapan Kerja')
@section('content')
    <style>
        .chart-container {
            width: 100%;
            max-width: 600px; /* Atur lebar maksimum */
            max-height: 500px;
            margin: auto;
        }

        #chartCanvas {
            max-height: 100; /* Atur tinggi maksimum */
        }
    </style>
    <div class="container-fluid">
        <div class="d-flex justify-content-end align-items-center mb-4">
            <button id="btn-generate" class="btn btn-success ms-2">+ Tarik Periode Baru</button>
        </div>
    </div>
    <div class="container-fluid">
    <table id="table-kelengkapan" class="table">
        <thead>
            <tr>  
                <th>No</th>  
                <th>Periode</th>  
                <th>Jumlah</th>  
                <th>Wearpack</th>  
                <th>Olahraga</th>
                <th>Dinas</th>
                <th>Jaket Shift</th>  
                <th>Jaket Casual</th>  
                <th>Sepatu Kantor</th>  
                <th>Sepatu Safety</th>  
                <th>Opsi</th>  
            </tr>  
        </thead>
        <tbody>
            @foreach($dataKelengkapanKerja as $key1 => $value1)
                <tr>
                    <th class="text-center">{{ $key1+1 }}</th>
                    <th class="text-center">
                        {{ format_date_locale($value1->periode) }}
                        {{-- <br>
                        ({{ format_date_human($value1->periode) }}) --}}
                    </th>
                    <th>{{ $value1->count_karyawan }} orang</th>
                    @php  
                        // Daftar jenis item yang ingin ditampilkan  
                        $items = [  
                            'Wearpack' => $value1->wearpack,  
                            'Seragam Olahraga' => $value1->seragam_olahraga,  
                            'Seragam Dinas Harian' => $value1->seragam_dinas_harian,  
                            'Jaket Shift' => $value1->jaket_shift,  
                            'Jaket Casual' => $value1->jaket_casual,  
                            'Sepatu Kantor' => $value1->sepatu_kantor,  
                            'Sepatu Safety' => $value1->sepatu_safety,  
                        ];  
                    @endphp  
                    
                    @foreach($items as $itemName => $itemValues)  
                        <td>  
                            @foreach($itemValues as $value2)  
                                {{ $value2['size'] === null ? 'Unkwn' : $value2['size'] }} -> {{ $value2['jumlah'] }} stel <br>  
                            @endforeach  
                        </td>  
                    @endforeach  
                    <td>
                        {{-- <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $value1->periode }}">  
                            <i class="fas fa-trash"></i> <!-- Ikon untuk hapus -->  
                        </button>   --}}
                        
                        <form action="{{ route('delete.kelengkapan', $value1->periode) }}" method="POST" style="display:inline;">  
                            @csrf  
                            @method('DELETE')  
                            <button type="submit" class="btn btn-danger btn-sm deleteBtn" data-id="{{ $value1->periode }}">  
                                <i class="fas fa-trash"></i> <!-- Ikon untuk hapus -->  
                            </button>  
                        </form>  
                        <button class="btn btn-success btn-sm btn-export" data-id="{{ $value1->periode }}">
                            <i class="fas fa-file-export"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalDataBaru" tabindex="-1" aria-labelledby="modalDataBaruLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addKaryawanModalLabel">Tambah Data Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form  action="{{ route('kelengkapan-kerja.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Informasi Dasar -->
                        <div class="col-md-2">
                            <label for="id_badge" class="form-label">ID Badge</label>
                            <input type="text" class="form-control" id="id_badge" name="id_badge" required>
                        </div>

                        <div class="col-md-4">
                            <label for="gelar_depan" class="form-label">Nama Karyawan</label>
                            <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan">
                        </div>
                        <div class="col-md-3">
                            <label for="nama_karyawan" class="form-label">Cost Center</label>
                            <input type="text" class="form-control" id="cost_center" name="cost_center" required>
                        </div>
                        <div class="col-md-3">
                            <label for="gelar_belakang" class="form-label">Unit Kerja</label>
                            <input type="text" class="form-control" id="unit_kerja" name="unit_kerja">
                        </div>
                        <div class="col-md-4">
                            <label for="jenis_kelamin" class="form-label">Sepatu Kantor</label>
                            <select class="form-select" id="sepatu_kantor" name="sepatu_kantor" required>
                                <option value="">Pilih Ukuran Sepatu</option>
                                <option value="34">34</option>
                                <option value="35">35</option>
                                <option value="36">36</option>
                                <option value="37">37</option>
                                <option value="38">38</option>
                                <option value="39">39</option>
                                <option value="40">40</option>
                                <option value="41">41</option>
                                <option value="42">42</option>
                                <option value="43">43</option>
                                <option value="44">44</option>
                                <option value="45">45</option>
                                <option value="45">45</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="jenis_kelamin" class="form-label">Sepatu Safety</label>
                            <select class="form-select" id="sepatu_safety" name="sepatu_safety" required>
                                <option value="">Pilih Ukuran Sepatu</option>
                                <option value="34">34</option>
                                <option value="35">35</option>
                                <option value="36">36</option>
                                <option value="37">37</option>
                                <option value="38">38</option>
                                <option value="39">39</option>
                                <option value="40">40</option>
                                <option value="41">41</option>
                                <option value="42">42</option>
                                <option value="43">43</option>
                                <option value="44">44</option>
                                <option value="45">45</option>
                                <option value="45">45</option>
                            </select>
                        </div>
                    
                        <div class="col-md-4">
                            <label for="jenis_kelamin" class="form-label">Wearpack Cover All</label>
                            <select class="form-select" id="wearpack_cover_all" name="wearpack_cover_all" required>
                                <option value="">Pilih Jenis Ukuran</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="XL">XL</option>
                                <option value="2XL">2XL</option>
                                <option value="3XL">3XL</option>
                                <option value="4XL">4XL</option>
                                <option value="5XL">5XL</option>
                                <option value="6XL">6XL</option>
                            </select>
                        </div>

                        <!-- Dropdown Selections -->
                        <div class="col-md-4">
                            <label for="pendidikan" class="form-label">Jaket Shift</label>
                            <select class="form-select" id="jaket_shift" name="jaket_shift" required>
                                <option value="">Pilih Pendidikan</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="XL">XL</option>
                                <option value="2XL">2XL</option>
                                <option value="3XL">3XL</option>
                                <option value="4XL">4XL</option>
                                <option value="5XL">5XL</option>
                                <option value="6XL">6XL</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="agama" class="form-label">Seragam Olahraga</label>
                            <select class="form-select" id="seragam_olahraga" name="seragam_olahraga" required>
                                <option value="">Pilih Ukuran</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="XL">XL</option>
                                <option value="2XL">2XL</option>
                                <option value="3XL">3XL</option>
                                <option value="4XL">4XL</option>
                                <option value="5XL">5XL</option>
                                <option value="6XL">6XL</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="status_pernikahan" class="form-label">Jaket Casual</label>
                            <select class="form-select" id="jaket_casual" name="jaket_casual" required>
                                <option value="">Pilih Ukuran Jaket</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="XL">XL</option>
                                <option value="2XL">2XL</option>
                                <option value="3XL">3XL</option>
                                <option value="4XL">4XL</option>
                                <option value="5XL">5XL</option>
                                <option value="6XL">6XL</option>
                            </select>
                        </div>
                    
                        <!-- Alamat -->
                        <div class="col-md-4">
                            <label for="status_pernikahan" class="form-label">Seragam Dinas Harian</label>
                            <select class="form-select" id="seragam_dinas_harian" name="seragam_dinas_harian" required>
                                <option value="">Pilih Ukuran Seragam</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="XL">XL</option>
                                <option value="2XL">2XL</option>
                                <option value="3XL">3XL</option>
                                <option value="4XL">4XL</option>
                                <option value="5XL">5XL</option>
                                <option value="6XL">6XL</option>
                            </select>
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
                            <label for="id_badge" class="form-label">ID</label>
                            <input type="text" class="form-control" id="editId" name="id_kelengkapan_kerja" >
                        </div>
                        <div class="col-md-2">
                            <label for="id_badge" class="form-label">ID Badge</label>
                            <input type="text" class="form-control" id="editIdBadge" name="id_badge" required>
                        </div>

                        <div class="col-md-4">
                            <label for="gelar_depan" class="form-label">Nama Karyawan</label>
                            <input type="text" class="form-control" id="editNamaKaryawan" name="nama_karyawan">
                        </div>
                        <div class="col-md-3">
                            <label for="nama_karyawan" class="form-label">Cost Center</label>
                            <input type="text" class="form-control" id="editCostCenter" name="cost_center" required>
                        </div>
                        <div class="col-md-3">
                            <label for="gelar_belakang" class="form-label">Unit Kerja</label>
                            <input type="text" class="form-control" id="editUnitKerja" name="unit_kerja">
                        </div>
                        <div class="col-md-4">
                            <label for="jenis_kelamin" class="form-label">Sepatu Kantor</label>
                            <select class="form-select" id="editSepatuKantor" name="sepatu_kantor" required>
                                <option value="">Pilih Ukuran Sepatu</option>
                                <option value="34">34</option>
                                <option value="35">35</option>
                                <option value="36">36</option>
                                <option value="37">37</option>
                                <option value="38">38</option>
                                <option value="39">39</option>
                                <option value="40">40</option>
                                <option value="41">41</option>
                                <option value="42">42</option>
                                <option value="43">43</option>
                                <option value="44">44</option>
                                <option value="45">45</option>
                                <option value="45">45</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="jenis_kelamin" class="form-label">Sepatu Safety</label>
                            <select class="form-select" id="editSepatuSafety" name="sepatu_safety" required>
                                <option value="">Pilih Ukuran Sepatu</option>
                                <option value="34">34</option>
                                <option value="35">35</option>
                                <option value="36">36</option>
                                <option value="37">37</option>
                                <option value="38">38</option>
                                <option value="39">39</option>
                                <option value="40">40</option>
                                <option value="41">41</option>
                                <option value="42">42</option>
                                <option value="43">43</option>
                                <option value="44">44</option>
                                <option value="45">45</option>
                                <option value="45">45</option>
                            </select>
                        </div>
                    
                        <div class="col-md-4">
                            <label for="jenis_kelamin" class="form-label">Wearpack Cover All</label>
                            <select class="form-select" id="editWearpackCoverAll" name="wearpack_cover_all" required>
                                <option value="">Pilih Jenis Ukuran</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="XL">XL</option>
                                <option value="2XL">2XL</option>
                                <option value="3XL">3XL</option>
                                <option value="4XL">4XL</option>
                                <option value="5XL">5XL</option>
                                <option value="6XL">6XL</option>
                            </select>
                        </div>

                        <!-- Dropdown Selections -->
                        <div class="col-md-4">
                            <label for="pendidikan" class="form-label">Jaket Shift</label>
                            <select class="form-select" id="editJaketShift" name="jaket_shift" required>
                                <option value="">Pilih Pendidikan</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="XL">XL</option>
                                <option value="2XL">2XL</option>
                                <option value="3XL">3XL</option>
                                <option value="4XL">4XL</option>
                                <option value="5XL">5XL</option>
                                <option value="6XL">6XL</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="agama" class="form-label">Seragam Olahraga</label>
                            <select class="form-select" id="editSeragamOlahraga" name="seragam_olahraga" required>
                                <option value="">Pilih Ukuran</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="XL">XL</option>
                                <option value="2XL">2XL</option>
                                <option value="3XL">3XL</option>
                                <option value="4XL">4XL</option>
                                <option value="5XL">5XL</option>
                                <option value="6XL">6XL</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="status_pernikahan" class="form-label">Jaket Casual</label>
                            <select class="form-select" id="editJaketCasual" name="jaket_casual" required>
                                <option value="">Pilih Ukuran Jaket</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="XL">XL</option>
                                <option value="2XL">2XL</option>
                                <option value="3XL">3XL</option>
                                <option value="4XL">4XL</option>
                                <option value="5XL">5XL</option>
                                <option value="6XL">6XL</option>
                            </select>
                        </div>
                        

                        <!-- Alamat -->
                        <div class="col-md-4">
                            <label for="status_pernikahan" class="form-label">Seragam Dinas Harian</label>
                            <select class="form-select" id="editSeragamDinasHarian" name="seragam_dinas_harian" required>
                                <option value="">Pilih Ukuran Seragam</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="XL">XL</option>
                                <option value="2XL">2XL</option>
                                <option value="3XL">3XL</option>
                                <option value="4XL">4XL</option>
                                <option value="5XL">5XL</option>
                                <option value="6XL">6XL</option>
                            </select>
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
            <form action="{{ route('kelengkapan-kerja.upload') }}" method="POST" enctype="multipart/form-data">
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
@endsection

@push('scripts')
<script>
    
    $(document).ready(function () {
        $(".btn-export").on("click", function () {
            let createdAt = $(this).data("id");
            // window.location.href = "/kelengkapan-kerja/export?created_at=" + createdAt;

            window.open("/kelengkapan-kerja/export?created_at=" + createdAt, "_blank");
        });


        
        $('#table-kelengkapan thead tr:first')  
            .clone(true)  
            .addClass('filters')  
            .appendTo('#table-kelengkapan thead');  

        var table = $('#table-kelengkapan').DataTable({  
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
                    if (colIdx == 10) {  
                        var cell = $('.filters th').eq(colIdx);  
                        $(cell).html('');  
                        return;  
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
        
        $('#btn-generate').on('click', function() {  
            // Ambil ID dari data-id atribut tombol  
            var id = $(this).data('id');  
            // Buat URL berdasarkan route yang telah ditentukan  
            var url = '/admin/master_data_karyawan/generate-kelengkapan/' + id;  
            // Arahkan ke URL dan reload halaman  
            window.location.href = url;  
        });  
    });

</script>
@endpush