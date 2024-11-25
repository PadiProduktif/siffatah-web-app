<!-- Sidebar -->
<div class="sidebar p-3">
    <div class="d-flex align-items-center">
        <img src="{{ asset('img/Logo PT Pupuk Kujang png 1.png') }}" alt="PKC Logo" width="70">
        <img src="{{ asset('img/Logo BUMN png 3.png') }}" alt="Logo BUMN" width="100">
    </div>
    <br>
    <h1>SI FATAH <span class="text-muted" style="font-size: 12px;">v.01</span></h1>
    <p class="text-muted" style="font-size: 12px;">Sistem Informasi Fasilitas dan Kesehatan</p>
    <ul class="nav flex-column">
        <li><a href="/admin/dashboard" class="{{ Request::is('home') ? 'active' : '' }}">Home</a></li>
        <li><a href="/admin/restitusi_karyawan" class="{{ Request::is('restitusi-karyawan') ? 'active' : '' }}">Restitusi Karyawan</a></li>
        <!-- Pengajuan Klaim harunya dropdown -->
        <li><a href="/admin/klaim_kecelakaan" class="{{ Request::is('pengajuan-klaim') ? 'active' : '' }}">Pengajuan Klaim</a></li>
        <!-- <li><a href="/kepesertaan-anggota" class="{{ Request::is('kepesertaan-anggota') ? 'active' : '' }}">Kepesertaan Anggota</a></li> -->
        <li><a href="/admin/ekses" class="{{ Request::is('ekses') ? 'active' : '' }}">Ekses</a></li>
        <li><a href="/admin/berkas_pengobatan" class="{{ Request::is('berkas-pengobatan') ? 'active' : '' }}">Berkas Pengobatan</a></li>
        <li class="nav-item dropdown">
            <a href="#" 
               class="nav-link dropdown-toggle {{ Request::is('wearpack') || Request::is('sepatu') ? 'active' : '' }}" 
               id="kelengkapanKerjaDropdown" 
               role="button" 
               data-bs-toggle="dropdown" 
               aria-expanded="false">
                Kelengkapan Kerja
            </a>
            <ul class="dropdown-menu" aria-labelledby="kelengkapanKerjaDropdown">
                <li><a href="/admin/kelengkapan_kerja/wearpack" class="dropdown-item {{ Request::is('wearpack') ? 'active' : '' }}">Wearpack</a></li>
                <li><a href="/admin/kelengkapan_kerja/sepatu" class="dropdown-item {{ Request::is('sepatu') ? 'active' : '' }}">Sepatu</a></li>
            </ul>
        </li>
        
        @if(auth()->check() && auth()->user()->role === 'superadmin')
            <li><a href="/admin/master_data_karyawan" class="{{ Request::is('master-data-karyawan') ? 'active' : '' }}">Master Data Karyawan</a></li>
        @endif
        <li><a href="/set-profil" class="{{ Request::is('set-profil') ? 'active' : '' }}">Pengaturan Profil</a></li>
        <li><a href="/logout" class="{{ Request::is('set-profil') ? 'active' : '' }}">Logout</a></li>
    </ul>
</div>
