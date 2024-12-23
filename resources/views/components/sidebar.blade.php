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
        
        @if(auth()->check() && (auth()->user()->role === 'superadmin' || auth()->user()->role === 'dr_hph'))

            <li><a href="/admin/restitusi_karyawan" class="{{ Request::is('restitusi-karyawan') ? 'active' : '' }}">Restitusi Karyawan</a></li>
            <li><a href="/admin/ekses" class="{{ Request::is('ekses') ? 'active' : '' }}">Ekses</a></li>
            <li><a href="/admin/berkas_pengobatan" class="{{ Request::is('berkas-pengobatan') ? 'active' : '' }}">Berkas Pengobatan</a></li>
            <li class="nav-item dropdown">
                <a href="#" 
                class="nav-link dropdown-toggle {{ Request::is('wearpack') || Request::is('sepatu') ? 'active' : '' }}" 
                id="kelengkapanKerjaDropdown" 
                role="button" 
                data-bs-toggle="dropdown" 
                aria-expanded="false">
                    Klaim Asuransi
                </a>
                <ul class="dropdown-menu" aria-labelledby="kelengkapanKerjaDropdown">
                    <li><a href="/admin/klaim_pengobatan" class="dropdown-item {{ Request::is('wearpack') ? 'active' : '' }}">Pengobatan</a></li>
                    <li><a href="/admin/klaim_kecelakaan" class="dropdown-item {{ Request::is('sepatu') ? 'active' : '' }}">Kecelakaan</a></li>
                    <li><a href="/admin/klaim_kematian" class="dropdown-item {{ Request::is('sepatu') ? 'active' : '' }}">Kematian</a></li>
                    <li><a href="/admin/klaim_purna-jabatan" class="dropdown-item {{ Request::is('sepatu') ? 'active' : '' }}">Purna jabatan</a></li>
                    <li><a href="/admin/klaim_lumpsum-kacamata" class="dropdown-item {{ Request::is('sepatu') ? 'active' : '' }}">Lumpsum kacamata</a></li>
                    <li><a href="/admin/klaim_lumpsum-lahiran" class="dropdown-item {{ Request::is('sepatu') ? 'active' : '' }}">Lumpsum lahiran</a></li>
                </ul>
            </li>
            
            @if(auth()->user()->role === 'superadmin')
                <li><a href="/admin/kelengkapan_kerja" class="{{ Request::is('berkas-pengobatan') ? 'active' : '' }}">Kelengkapan Kerja</a></li>
                <li><a href="/admin/master_data_karyawan" class="{{ Request::is('master-data-karyawan') ? 'active' : '' }}">Master Data Karyawan</a></li>
            @endif
        @else
            <li><a href="/ekses" class="{{ Request::is('ekses') ? 'active' : '' }}">Ekses</a></li>
            <li><a href="/restitusi_karyawan" class="{{ Request::is('restitusi-karyawan') ? 'active' : '' }}">Restitusi Karyawan</a></li>
            <li><a href="/berkas_pengobatan" class="{{ Request::is('berkas-pengobatan') ? 'active' : '' }}">Berkas Pengobatan</a></li>
            <li class="nav-item dropdown">
                <a href="#" 
                class="nav-link dropdown-toggle {{ Request::is('wearpack') || Request::is('sepatu') ? 'active' : '' }}" 
                id="kelengkapanKerjaDropdown" 
                role="button" 
                data-bs-toggle="dropdown" 
                aria-expanded="false">
                    Klaim Asuransi
                </a>
                <ul class="dropdown-menu" aria-labelledby="kelengkapanKerjaDropdown">
                    <li><a href="/klaim_pengobatan" class="dropdown-item {{ Request::is('wearpack') ? 'active' : '' }}">Pengobatan</a></li>
                    <li><a href="/klaim_kecelakaan" class="dropdown-item {{ Request::is('sepatu') ? 'active' : '' }}">Kecelakaan</a></li>
                    <li><a href="/klaim_kematian" class="dropdown-item {{ Request::is('sepatu') ? 'active' : '' }}">Kematian</a></li>
                    <li><a href="/klaim_purna-jabatan" class="dropdown-item {{ Request::is('sepatu') ? 'active' : '' }}">Purna jabatan</a></li>
                    <li><a href="/klaim_lumpsum-kacamata" class="dropdown-item {{ Request::is('sepatu') ? 'active' : '' }}">Lumpsum kacamata</a></li>
                    <li><a href="/klaim_lumpsum-lahiran" class="dropdown-item {{ Request::is('sepatu') ? 'active' : '' }}">Lumpsum lahiran</a></li>
                </ul>
            </li>
        @endif
<!-- //point -->
        <li><a href="/set-profil" class="{{ Request::is('set-profil') ? 'active' : '' }}">Pengaturan Profil</a></li>
        <li><a href="/logout">Logout</a></li>
    </ul>
</div>
