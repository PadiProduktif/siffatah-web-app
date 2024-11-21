@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <!-- Table -->
    <div class="table-container bg-white p-3 rounded shadow">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>MASTER DATA KARYAWAN</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addKaryawanModal">
                <i class="bi bi-plus-lg"></i> Tambah Karyawan
            </button>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead class="table-light">
                    <tr>
                        <th>ID Badge</th>
                        <th>Nama Karyawan</th>
                        <th>Nama Lengkap</th>
                        <th>Pendidikan</th>
                        <th>Jenis Kelamin</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($karyawan as $k)
                        <tr>
                            <td>{{ $k->id_badge }}</td>
                            <td>{{ $k->nama_karyawan }}</td>
                            <td>{{ $k->nama_lengkap }}</td>
                            <td>{{ $k->pendidikan }}</td>
                            <td>{{ $k->jenis_kelamin }}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-info" onclick="editKaryawan('{{ $k->id_karyawan }}')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('{{ $k->id_karyawan }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
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

    <!-- Modal Tambah Karyawan -->
    <div class="modal fade" id="addKaryawanModal" tabindex="-1" aria-labelledby="addKaryawanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKaryawanModalLabel">Tambah Data Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/admin/master_data_karyawan/tambah" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Informasi Dasar -->
                            <div class="col-md-6">
                                <label for="id_badge" class="form-label">ID Badge</label>
                                <input type="text" class="form-control" id="id_badge" name="id_badge" required>
                            </div>
                            <div class="col-md-6">
                                <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
                                <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan" required>
                            </div>
                            
                            <!-- Nama Lengkap dan Gelar -->
                            <div class="col-md-4">
                                <label for="gelar_depan" class="form-label">Gelar Depan</label>
                                <input type="text" class="form-control" id="gelar_depan" name="gelar_depan">
                            </div>
                            <div class="col-md-4">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                            </div>
                            <div class="col-md-4">
                                <label for="gelar_belakang" class="form-label">Gelar Belakang</label>
                                <input type="text" class="form-control" id="gelar_belakang" name="gelar_belakang">
                            </div>

                            <!-- Informasi Personal -->
                            <div class="col-md-6">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required>
                            </div>
                            <div class="col-md-6">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                            </div>

                            <!-- Dropdown Selections -->
                            <div class="col-md-4">
                                <label for="pendidikan" class="form-label">Pendidikan</label>
                                <select class="form-select" id="pendidikan" name="pendidikan" required>
                                    <option value="">Pilih Pendidikan</option>
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA">SMA</option>
                                    <option value="D3">D3</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="agama" class="form-label">Agama</label>
                                <select class="form-select" id="agama" name="agama" required>
                                    <option value="">Pilih Agama</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="status_pernikahan" class="form-label">Status Pernikahan</label>
                                <select class="form-select" id="status_pernikahan" name="status_pernikahan" required>
                                    <option value="">Pilih Status</option>
                                    <option value="Belum Menikah">Belum Menikah</option>
                                    <option value="Menikah">Menikah</option>
                                    <option value="Cerai">Cerai</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="keluarga" class="form-label">Keluarga</label>
                                <input type="text" class="form-control" id="keluarga" name="keluarga">
                            </div>

                            <!-- Alamat -->
                            <div class="col-12">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                            </div>

                            <!-- File Uploads -->
                            <!-- <div class="col-md-6">
                                <label for="url_foto_diri" class="form-label">Foto Diri</label>
                                <input type="file" class="form-control" id="url_foto_diri" name="url_foto_diri">
                            </div>
                            <div class="col-md-6">
                                <label for="url_file_ktp" class="form-label">File KTP</label>
                                <input type="file" class="form-control" id="url_file_ktp" name="url_file_ktp">
                            </div>
                            <div class="col-md-6">
                                <label for="url_file_kk" class="form-label">File KK</label>
                                <input type="file" class="form-control" id="url_file_kk" name="url_file_kk">
                            </div>
                            <div class="col-md-6">
                                <label for="url_file_buku_nikah" class="form-label">File Buku Nikah</label>
                                <input type="file" class="form-control" id="url_file_buku_nikah" name="url_file_buku_nikah">
                            </div>
                            <div class="col-md-6">
                                <label for="url_file_akta_kelahiran" class="form-label">File Akta Kelahiran</label>
                                <input type="file" class="form-control" id="url_file_akta_kelahiran" name="url_file_akta_kelahiran">
                            </div>
                            <div class="col-md-6">
                                <label for="url_npwp" class="form-label">File NPWP</label>
                                <input type="file" class="form-control" id="url_npwp" name="url_npwp">
                            </div>
                            <div class="col-12">
                                <label for="url_lamaran_pekerjaan" class="form-label">File Lamaran Pekerjaan</label>
                                <input type="file" class="form-control" id="url_lamaran_pekerjaan" name="url_lamaran_pekerjaan">
                            </div> -->
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

    <!-- Form hidden untuk submit -->
    <form id="delete-form-{{ $k->id_karyawan }}" 
          action="/admin/master_data_karyawan/delete/{{ $k->id_karyawan }}" 
          method="POST" 
          class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

// Tampilkan SweetAlert2 untuk pesan sukses/error dari controller
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 1500,
        showConfirmButton: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '{{ session('error') }}'
    });
@endif
</script>
@endpush
