@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <style>
        .chart-container {
            width: 100%;
            max-width: 600px; /* Atur lebar maksimum */
            max-height: 200px;
            margin: auto;
        }

        #chartCanvas {
            max-height: 100; /* Atur tinggi maksimum */
        }
    </style>
    <div class="container">
        <div class="d-flex justify-content-end align-items-center mb-4">
            <h4 class="me-auto">Kelengkapan Kerja</h4> <!-- Tambahkan kelas 'me-auto' untuk memberi margin ke kanan pada judul -->
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
    <div class="chart-container mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5>Chart Kelengkapan Kerja</h5>
            <div>
                <label for="chartSwitch">Pilih Item: </label>
                <select id="chartSwitch" class="form-select form-select-sm">
                    <option value="sepatu_kantor" selected>Sepatu Kantor</option>
                    <option value="sepatu_safety">Sepatu Safety</option>
                    <option value="wearpack_cover_all">Wearpack Cover All</option>
                    <option value="jaket_shift">Jaket Shift</option>
                    <option value="seragam_olahraga">Seragam Olahraga</option>
                    <option value="jaket_casual">Jaket Casual</option>
                    <option value="seragam_dinas_harian">Seragam Dinas Harian</option>
                </select>
            </div>
        </div>
        <canvas id="chartCanvas" width="100%" height="40"></canvas>
    </div>
    <div class="container">
    <button style="margin-bottom: 20px;" id="deleteSelected" class="btn btn-danger">Hapus Terpilih</button>
    <table id="kelengkapanKerjaTable" class="display">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th> <!-- Checkbox untuk Select All -->
                <th>ID Badge</th>
                <th>Nama Karyawan</th>
                <th>Cost Center</th>
                <th>Unit Kerja</th>
                <th>Sepatu Kantor</th>
                <th>Sepatu Safety</th>
                <th>Wearpack Cover All</th>
                <th>Jaket Shift</th>
                <th>Seragam Olahraga</th>
                <th>Jaket Casual</th>
                <th>Seragam Dinas Harian</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data Dummy -->
            @foreach($kelengkapan as $item)
            <tr>
                <td><input type="checkbox" class="rowCheckbox" value="{{ $item->id_kelengkapan_kerja }}"></td>
                <th>{{ $item->id_badge }}</th>
                <th>{{ $item->nama_karyawan }}</th>
                <th>{{ $item->cost_center }}</th>
                <th>{{ $item->unit_kerja }}</th>
                <td>{{ $item->sepatu_kantor }}</td>
                <td>{{ $item->sepatu_safety }}</td>
                <td>{{ $item->wearpack_cover_all }}</td>
                <td>{{ $item->jaket_shift }}</td>
                <td>{{ $item->seragam_olahraga }}</td>
                <td>{{ $item->jaket_casual }}</td>
                <td>{{ $item->seragam_dinas_harian }}</td>
                <td>
                    <button type="button" class="btn btn-warning btn-sm editBtn"
                    data-id="{{ $item->id_kelengkapan_kerja }}"
                    data-id_badge="{{ $item->id_badge }}"
                    data-nama_karyawan="{{ $item->nama_karyawan }}"
                    data-cost_center="{{ $item->cost_center }}"
                    data-unit_kerja="{{ $item->unit_kerja }}"
                    data-sepatu_kantor="{{ $item->sepatu_kantor }}"
                    data-sepatu_safety="{{ $item->sepatu_safety }}"
                    data-wearpack_cover_all="{{ $item->wearpack_cover_all }}"
                    data-jaket_shift="{{ $item->jaket_shift }}"
                    data-seragam_olahraga="{{ $item->seragam_olahraga }}"
                    data-jaket_casual="{{ $item->jaket_casual }}"
                    data-seragam_dinas_harian="{{ $item->seragam_dinas_harian }}"
                    data-bs-toggle="modal" data-bs-target="#modalEditData">
                Edit
                </button>
                <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $item->id_kelengkapan_kerja }}">Hapus</button>
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
            var table = $('#kelengkapanKerjaTable').DataTable({
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
            $('#kelengkapanKerjaTable tbody').on('change', 'input[type="checkbox"].rowCheckbox', function () {
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
        var table = $('#kelengkapanKerjaTable').DataTable();

        // Event delegation untuk tombol Edit
        $('#kelengkapanKerjaTable').on('click', '.editBtn', function () {
            // Ambil data dari atribut tombol
            const id = $(this).data('id');
            const id_badge = $(this).data('id_badge');
            const nama_karyawan = $(this).data('nama_karyawan');
            const cost_center = $(this).data('cost_center');
            const unit_kerja = $(this).data('unit_kerja');
            const sepatu_kantor = $(this).data('sepatu_kantor');
            const sepatu_safety = $(this).data('sepatu_safety');
            const wearpack_cover_all = $(this).data('wearpack_cover_all');
            const jaket_shift = $(this).data('jaket_shift');
            const seragam_olahraga = $(this).data('seragam_olahraga');
            const jaket_casual = $(this).data('jaket_casual');
            const seragam_dinas_harian = $(this).data('seragam_dinas_harian');

            // Isi data di dalam form modal
            $('#editId').val(id);
            $('#editIdBadge').val(id_badge);
            $('#editNamaKaryawan').val(nama_karyawan);
            $('#editCostCenter').val(cost_center);
            $('#editUnitKerja').val(unit_kerja);
            $('#editSepatuKantor').val(sepatu_kantor);
            $('#editSepatuSafety').val(sepatu_safety);
            $('#editWearpackCoverAll').val(wearpack_cover_all);
            $('#editJaketShift').val(jaket_shift);
            $('#editSeragamOlahraga').val(seragam_olahraga);
            $('#editJaketCasual').val(jaket_casual);
            $('#editSeragamDinasHarian').val(seragam_dinas_harian);

            // Ubah action form agar mengarah ke endpoint update
            // $('#editForm').attr('action', '/kelengkapan_kerja/update/' + id);
            // let baseUrl = window.location.origin;
            // $('#editForm').attr('action', baseUrl + '/kelengkapan_kerja/update/' + id);
            $('#editForm').attr('action', '/admin/kelengkapan_kerja/update/' + id);
        });
    });

    $(document).ready(function () {
    // Event delegation untuk tombol Hapus
    $('#kelengkapanKerjaTable').on('click', '.deleteBtn', function () {
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
                url: '/admin/kelengkapan_kerja/delete/' + id,
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
    // Data awal (dari Laravel)
    const chartData = @json($chartData);

    // Fungsi untuk membuat dataset chart
    function generateChartData(item) {
        const labels = chartData[item].map(data => data[item]);
        const data = chartData[item].map(data => data.jumlah);

        return { labels, data };
    }

    // Inisialisasi Chart.js
    const ctx = document.getElementById('chartCanvas').getContext('2d');
    let currentChart;

        function renderChart(item) {
            const { labels, data } = generateChartData(item);

            if (currentChart) currentChart.destroy(); // Hapus chart lama jika ada

            currentChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: `Jumlah per ukuran (${item.replace('_', ' ')})`,
                        data: data,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Render chart pertama kali
        renderChart('sepatu_kantor');

        // Event listener untuk switch button
        $('#chartSwitch').on('change', function () {
            const selectedItem = $(this).val();
            renderChart(selectedItem);
        });
    });

        $(document).ready(function () {
            // Pilih semua checkbox
            $('#selectAll').on('click', function () {
                $('.rowCheckbox').prop('checked', this.checked);
            });

            // Perbarui checkbox "Pilih Semua" jika ada perubahan pada baris
            $('#kelengkapanKerjaTable').on('change', '.rowCheckbox', function () {
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
                            url: '/admin/kelengkapan_kerja/delete-multiple', // Endpoint untuk hapus data
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