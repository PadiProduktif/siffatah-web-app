<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Si Fatah</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .bg-custom {
            background-color: #9CBESD !important;
            color: white;
        }
    </style>
</head>
<body>
    <header class="bg-orange-600 p-3">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="{{ asset('img/Logo PT Pupuk Kujang png 1.png') }}" alt="PKC Logo" width="100">
                <img src="{{ asset('img/Logo BUMN png 3.png') }}" alt="Logo BUMN" width="150">
                <h1 class="ms-3 text-white fw-bold fs-1">Si Fatah</h1>
            </div>
            <a href="{{ route('login') }}" class="btn btn-warning text-white fw-semibold">Login</a>
        </div>
    </header>

    <main class="min-vh-100">

        <section class="bg-success text-white py-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                <h1 class="display-3 fw-bold">Get More Done with Si Fatah!</h1>
                <p class="lead">Si Fatah (Sistem Informasi Fasilitas dan Kesehatan) adalah aplikasi yang dikembangkan untuk mendukung Unit Kerja Asuransi dan Kesejahteraan Pegawai dalam menjalankan tugas sehari-hari. Aplikasi ini dirancang untuk mempermudah pengelolaan klaim asuransi, proses reimburse, pengajuan kelengkapan kerja, dan berbagai kebutuhan administratif lainnya.</p>
                    </div>
                    <div class="col-md-6 text-center">
                        <img src="{{ asset('img/image 3.png') }}" alt="Restitusi Karyawan" class="img-fluid">
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2 class="fw-bold text-primary">Restitusi Karyawan</h2>
                        <p>Melalui menu ini, user dapat mengajukan restitusi pengobatan secara cepat, memeriksa status pengajuan, serta melengkapi dokumen yang diperlukan. Fitur-fiturnya membantu mengotomatisasi berbagai langkah dalam proses restitusi, sehingga meminimalkan kesalahan dan mempermudah proses pengajuan restitusi. Dengan adanya menu ini, pekerjaan terkait pengelolaan restitusi pengobatan menjadi lebih mudah, terstruktur, dan efisien.</p>
                    </div>
                    <div class="col-md-6 text-center">
                        <img src="{{ asset('img/image 5.png') }}" alt="Restitusi Karyawan" class="img-fluid">
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 bg-light">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 order-md-2">
                        <h1 class="fw-bold text-primary">Pengajuan Klaim</h1>
                        <p>Berfungsi sebagai pusat informasi bagi pengguna untuk memantau dan mengelola pengajuan klaim. Terdapat ringkasan klaim yang mencakup detail tanggal pengajuan, jumlah yang diajukan, serta jenis klaim. Dashboard ini dirancang untuk memberikan visibilitas yang jelas dan real-time kepada user, sehingga memudahkan pemantauan serta mempercepat tindak lanjut dari klaim yang sedang diproses.</p>
                    </div>
                    <div class="col-md-6 text-center">
                    <img src="{{ asset('img/image 4.png') }}" alt="Restitusi Karyawan" class="img-fluid">
                    </div>
                </div>
            </div>
        </section>
        
        <section class="py-5" style="background-color: #3688C0; color: white; position: relative; overflow: hidden;">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="fw-bold text-white">Kepesertaan Anggota BPJS</h1>
                        <p>Di dalam menu ini, user dapat melihat data keanggotaan karyawan, seperti nomor BPJS, jenis kepesertaan (BPJS Kesehatan atau Ketenagakerjaan), status aktif atau tidak aktif, serta informasi terkait iuran dan riwayat pembayaran.</p>
                    </div>
                    <div class="col-md-6 text-center">
                        <img src="{{ asset('img/image.png') }}" alt="Restitusi Karyawan" class="img-fluid">
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 bg-light">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 order-md-2">
                        <h2 class="fw-bold text-primary">Ekses</h2>
                        <p>Pada menu ini, user dapat melihat rincian ekses yang harus ditanggung karyawan karena biaya pengobatan yang melebihi plafon yang ditetapkan atau tidak tercakup dalam asuransi. Dashboard ini menampilkan informasi detail tentang jumlah ekses, tanggal klaim, serta alasan penolakan dari asuransi.</p>
                    </div>
                    <div class="col-md-6 text-center">
                    <img src="{{ asset('img/Image container.png') }}" alt="Restitusi Karyawan" class="img-fluid">
                    </div>
                </div>
            </div>
        </section>
        
        <section class="py-5" style="background-color: #8B2323; color: white; position: relative; overflow: hidden;">
            <!-- Decorative curved lines background -->
            <div class="position-absolute" style="left: 0; top: 0; width: 40%; height: 100%; opacity: 0.1;">
                <svg viewBox="0 0 200 100%" preserveAspectRatio="none">
                    <path d="M0,0 C50,20 50,80 0,100" fill="none" stroke="white" stroke-width="2"/>
                    <path d="M10,0 C60,30 60,70 10,100" fill="none" stroke="white" stroke-width="2"/>
                    <path d="M20,0 C70,40 70,60 20,100" fill="none" stroke="white" stroke-width="2"/>
                </svg>
            </div>
            
            <div class="container position-relative">
                <div class="row text-center">
                    <div class="col-md-12">
                        <h2 class="display-5 fw-bold mb-4">
                            Berkas Restitusi Pengobatan 
                            <br>dari Rumah Sakit
                        <p class="lead">
                            Melalui menu ini, pengguna dapat melihat status pengajuan restitusi yang berasal dari rumah sakit, termasuk berkas yang telah diterima, sedang diverifikasi, atau yang masih memerlukan kelengkapan.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2 class="fw-bold text-primary">Master Data</h2>
                        <p>Menu Dashboard Master Data Karyawan pada aplikasi Si Fatah berfungsi sebagai pusat informasi yang menampilkan data lengkap mengenai karyawan. Melalui menu ini, pengguna dapat mengakses berbagai data penting, seperti nama, data pendidikan, status kepegawaian, dan berkas penting lainnya untuk keperluan berbagai administrasi.</p>
                    </div>
                    <div class="col-md-6 text-center">
                        <img src="{{ asset('img/image 8.png') }}" alt="Restitusi Karyawan" class="img-fluid">
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-primary text-white py-4">
        <div class="container">
            <div class="row">
                <!-- Left Section -->
                <div class="col-md-4">
                    <h3>SI FATAH <span class="fs-6">v.01</span></h3>
                    <p>Sistem Informasi Fasilitas dan Kesehatan untuk mendukung Unit Kerja Asuransi.</p>
                </div>
                <!-- Middle Section -->
                <div class="col-md-4 text-center">
                    <!-- <img src="{{ asset('img/logo-bumn.png') }}" alt="BUMN Logo" width="80">
                    <img src="{{ asset('img/logo-pupuk-indonesia.png') }}" alt="Pupuk Logo" width="80"> -->
                    <img src="{{ asset('img/Logo PT Pupuk Kujang png 1.png') }}" alt="PKC Logo" width="100">
                    <img src="{{ asset('img/Logo BUMN png 3.png') }}" alt="Logo BUMN" width="150">
                </div>
                <!-- Right Section -->
                <div class="col-md-4">
                    <h4>Apakah ada saran?</h4>
                    <p>Berikan saran untuk pengembangan Si Fatah.</p>
                    <a href="#" class="btn btn-secondary">Beri Saran</a>
                </div>
            </div>
            <hr class="my-3">
            <div class="d-flex justify-content-between">
                <p>&copy; 2024 Si Fatah</p>
                <div>
                    <a href="#" class="text-white me-3">Terms</a>
                    <a href="#" class="text-white me-3">Privacy</a>
                    <a href="#" class="text-white">Status</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>