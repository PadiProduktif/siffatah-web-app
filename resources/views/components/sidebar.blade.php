<div class="sidebar bg-light p-3">
    <!-- Header Sidebar -->
    <div class="d-flex align-items-center justify-content-between">
        <img src="{{ asset('img/logo-bumn.png') }}" alt="Logo BUMN" 
            class="img-fluid" style="max-width: 100px; max-height: 70px; object-fit: contain;">
        <img src="{{ asset('img/logo-pupuk-indonesia.png') }}" alt="Logo BUMN" 
            class="img-fluid" style="max-width: 100px; max-height: 70px; object-fit: contain;">
        <img src="{{ asset('img/logo-pupuk-kujang.png') }}" alt="PKC Logo" 
            class="img-fluid" style="max-width: 70px; max-height: 70px; object-fit: contain;">
    </div>
    
    <h1 class="fs-5 mb-1">SI FATAH <span class="text-muted" style="font-size: 12px;">v.01</span></h1>
    <p class="text-muted" style="font-size: 12px;">Sistem Informasi Fasilitas dan Kesehatan</p>

    @if(auth()->check() && (auth()->user()->role === 'superadmin' || auth()->user()->role === 'dr_hph'))
        <ul class="nav flex-column">
            <li><a href="/admin/dashboard" class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}">Dashboard</a></li>
            <li><a href="/admin/restitusi_karyawan" class="nav-link {{ Request::is('admin/restitusi_karyawan') ? 'active' : '' }}">Restitusi</a></li>
            <li><a href="/admin/ekses" class="nav-link {{ Request::is('admin/ekses') ? 'active' : '' }}">Ekses</a></li>
            <li><a href="/admin/berkas-pengobatan" class="nav-link {{ Request::is('admin/berkas-pengobatan') ? 'active' : '' }}">Berkas Pengobatan</a></li>
        </ul>

        <ul class="nav flex-column">
            @if(auth()->user()->role === 'superadmin')
                <li><a href="/admin/master_data_karyawan" class="nav-link {{ Request::is('admin/master_data_karyawan') ? 'active' : '' }}">Master Data Karyawan</a></li>
                <li><a href="/admin/kelengkapan_kerja" class="nav-link {{ Request::is('admin/kelengkapan_kerja') ? 'active' : '' }}">Kelengkapan Kerja</a></li>
            @endif
        </ul>
        
        <div class="accordion" id="sidebarAccordion">
            <!-- Klaim Asuransi -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingInsurance">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInsurance" aria-expanded="false" aria-controls="collapseInsurance">
                        Klaim Asuransi
                    </button>
                </h2>
                <div
                    id="collapseInsurance" 
                    class="accordion-collapse collapse {{ request()->is(
                        'admin/klaim_pengobatan',
                        'admin/klaim_kecelakaan',
                        'admin/klaim_kematian',
                        'admin/klaim_purna-jabatan',
                        'admin/klaim_lumpsum-kacamata',
                        'admin/klaim_lumpsum-lahiran') ? 'show' : '' }}" 
                    aria-labelledby="headingInsurance" 
                    data-bs-parent="#sidebarAccordion">
                    <div class="accordion-body">
                        <ul class="nav flex-column">
                            <li><a href="/admin/klaim_pengobatan" class="nav-link {{ request()->is('admin/klaim_pengobatan') ? 'active' : '' }}">Pengobatan</a></li>
                            <li><a href="/admin/klaim_kecelakaan" class="nav-link {{ request()->is('admin/klaim_kecelakaan') ? 'active' : '' }}">Kecelakaan</a></li>
                            <li><a href="/admin/klaim_kematian" class="nav-link {{ request()->is('admin/klaim_kematian') ? 'active' : '' }}">Kematian</a></li>
                            <li><a href="/admin/klaim_purna-jabatan" class="nav-link {{ request()->is('admin/klaim_purna-jabatan') ? 'active' : '' }}">Purna Jabatan</a></li>
                            <li><a href="/admin/klaim_lumpsum-kacamata" class="nav-link {{ request()->is('admin/klaim_lumpsum-kacamata') ? 'active' : '' }}">Lumpsum Kacamata</a></li>
                            <li><a href="/admin/klaim_lumpsum-lahiran" class="nav-link {{ request()->is('admin/klaim_lumpsum-lahiran') ? 'active' : '' }}">Lumpsum Lahiran</a></li>
                        </ul>
                    </div>
                </div>
                
            </div>
            
            <!-- Perlengkapan Kerja -->
            {{-- <div class="accordion-item">
                <h2 class="accordion-header" id="headingPerlengkapanKerja">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePerlengkapanKerja" aria-expanded="false" aria-controls="collapsePerlengkapanKerja">
                        Kelengkapan Kerja
                    </button>
                </h2>
                <div
                    id="collapsePerlengkapanKerja" 
                    class="accordion-collapse collapse {{ request()->is(
                        'admin/wearpack',
                        'admin/seragam-dinas-harian',
                        'admin/seragam-olahraga',
                        'admin/jaket-shift',
                        'admin/jaket-kasual',
                        'admin/sepatu-safety',
                        'admin/sepatu-kantor') ? 'show' : '' }}" 
                    aria-labelledby="headingPerlengkapanKerja" 
                    data-bs-parent="#sidebarAccordion">
                    <div class="accordion-body">
                        <ul class="nav flex-column">
                            <li><a href="/admin/wearpack" class="nav-link {{ request()->is('admin/wearpack') ? 'active' : '' }}">Wearpack</a></li>
                            <li><a href="/admin/seragam-dinas-harian" class="nav-link {{ request()->is('admin/seragam-dinas-harian') ? 'active' : '' }}">Seragam Dinas Harian</a></li>
                            <li><a href="/admin/seragam-olahraga" class="nav-link {{ request()->is('admin/seragam-olahraga') ? 'active' : '' }}">Seragam Olahraga</a></li>
                            <li><a href="/admin/sepatu-safety" class="nav-link {{ request()->is('admin/sepatu-safety') ? 'active' : '' }}">Sepatu Safety</a></li>
                            <li><a href="/admin/sepatu-kantor" class="nav-link {{ request()->is('admin/sepatu-kantor') ? 'active' : '' }}">Sepatu Kantor</a></li>
                            <li><a href="/admin/jaket-shift" class="nav-link {{ request()->is('admin/jaket-shift') ? 'active' : '' }}">Jaket Shift</a></li>
                            <li><a href="/admin/jaket-kasual" class="nav-link {{ request()->is('admin/jaket-kasual') ? 'active' : '' }}">Jaket Casual</a></li>
                        </ul>
                    </div>
                </div>
            </div> --}}
        </div>
        <div class="accordion" id="sidebarAccordion2">
            <!-- Kepersertaan BPJS -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingInsurance2">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInsurance2" aria-expanded="false" aria-controls="collapseInsurance">
                        Kepesertaan BPJS
                    </button>
                </h2>
                <div
                    id="collapseInsurance2" 
                    class="accordion-collapse collapse {{ request()->is('admin/bpjs/bpjs-kesehatan', 'admin/bpjs/bpjs-ketenagakerjaan') ? 'show' : '' }}" 
                    aria-labelledby="headingInsurance2" 
                    data-bs-parent="#sidebarAccordion2">
                    <div class="accordion-body">
                        <ul class="nav flex-column">
                            <li><a href="/admin/bpjs/bpjs-kesehatan" class="nav-link {{ request()->is('admin/bpjs/bpjs-kesehatan') ? 'active' : '' }}">Kesehatan</a></li>
                            <li><a href="/admin/bpjs/bpjs-ketenagakerjaan" class="nav-link {{ request()->is('admin/bpjs/bpjs-ketenagakerjaan') ? 'active' : '' }}">Ketenaga Kerjaan</a></li>
                        </ul>
                    </div>
                </div>
                
            </div>
        </div>

        
    @else
        <ul class="nav flex-column">
            <li><a href="/dashboard" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">Dashboard</a></li>
            <li><a href="/restitusi_karyawan" class="nav-link {{ Request::is('restitusi_karyawan') ? 'active' : '' }}">Restitusi</a></li>
            <li><a href="/ekses" class="nav-link {{ Request::is('ekses') ? 'active' : '' }}">Ekses</a></li>
            <li><a href="/berkas-pengobatan" class="nav-link {{ Request::is('berkas-pengobatan') ? 'active' : '' }}">Berkas Pengobatan</a></li>
            {{-- <li><a href="/keluarga" class="nav-link {{ Request::is('keluarga') ? 'active' : '' }}">Keluarga</a></li> --}}
        </ul>

        <div class="accordion" id="sidebarAccordion">
            <!-- Klaim Asuransi -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingInsurance">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInsurance" aria-expanded="false" aria-controls="collapseInsurance">
                        Klaim Asuransi
                    </button>
                </h2>
                <div
                    id="collapseInsurance" 
                    class="accordion-collapse collapse {{ request()->is(
                        'klaim-pengobatan',
                        'klaim-kecelakaan',
                        'klaim-kematian',
                        'klaim-purna-jabatan',
                        'klaim-lumpsum-kacamata',
                        'klaim-lumpsum-lahiran') ? 'show' : '' }}" 
                    aria-labelledby="headingInsurance" 
                    data-bs-parent="#sidebarAccordion">
                    <div class="accordion-body">
                        <ul class="nav flex-column">
                            <li><a href="/klaim-pengobatan" class="nav-link {{ request()->is('klaim-pengobatan') ? 'active' : '' }}">Pengobatan</a></li>
                            <li><a href="/klaim-kecelakaan" class="nav-link {{ request()->is('klaim-kecelakaan') ? 'active' : '' }}">Kecelakaan</a></li>
                            <li><a href="/klaim-kematian" class="nav-link {{ request()->is('klaim-kematian') ? 'active' : '' }}">Kematian</a></li>
                            <li><a href="/klaim-purna-jabatan" class="nav-link {{ request()->is('klaim-purna-jabatan') ? 'active' : '' }}">Purna Jabatan</a></li>
                            <li><a href="/klaim-lumpsum-kacamata" class="nav-link {{ request()->is('klaim-lumpsum-kacamata') ? 'active' : '' }}">Lumpsum Kacamata</a></li>
                            <li><a href="/klaim-lumpsum-lahiran" class="nav-link {{ request()->is('klaim-lumpsum-lahiran') ? 'active' : '' }}">Lumpsum Lahiran</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <ul class="nav flex-column">
        @if(auth()->check() && (auth()->user()->role === 'tko'))
            <li><a href="/keluarga" class="nav-link {{ Request::is('/keluarga') ? 'active' : '' }}">Keluarga</a></li>
        @endif
        {{-- <li><a href="/kepesertaan-anggota" class="nav-link {{ Request::is('kepesertaan-anggota') ? 'active' : '' }}">Kepesertaan Anggota</a></li> --}}
        <li><a href="/set-profil" class="nav-link {{ Request::is('set-profil') ? 'active' : '' }}">Pengaturan Profil</a></li>
        <li><a href="/logout" class="nav-link text-danger">Logout</a></li>
    </ul>
</div>
