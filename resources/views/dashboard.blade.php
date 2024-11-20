<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SI FATAH</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
        }
        .sidebar {
            background-color: #f3f4f6;
            min-height: 100vh;
        }
        .sidebar h1 {
            font-size: 24px;
            font-weight: bold;
        }
        .sidebar a {
            color: #6b7280;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 8px;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background-color: #d1fae5;
            color: #047857;
        }
        .header img {
            height: 50px;
            width: 50px;
            object-fit: cover;
        }
        .status {
            padding: 5px 10px;
            border-radius: 5px;
            color: #ffffff;
            font-size: 14px;
        }
        .status.new { background-color: #f59e0b; }
        .status.verified { background-color: #10b981; }
        .status.rejected { background-color: #ef4444; }
        .status.processing { background-color: #3b82f6; }

    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3">
            <h1>SI FATAH</h1>
            <ul class="nav flex-column">
                <li><a href="#" class="active">Home</a></li>
                <li><a href="#">Restitusi Karyawan</a></li>
                <li><a href="#">Pengajuan Klaim</a></li>
                <li><a href="#">Kepesertaan Anggota</a></li>
                <li><a href="#">Ekses</a></li>
                <li><a href="#">Berkas Pengobatan</a></li>
                <li class="nav-item dropdown">
                    <a href="#" 
                    class="nav-link dropdown-toggle" 
                    id="kelengkapanKerjaDropdown" 
                    role="button" 
                    data-bs-toggle="dropdown" 
                    aria-expanded="false">
                        Kelengkapan Kerja
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="kelengkapanKerjaDropdown">
                        <li><a href="#" class="dropdown-item">Wearpack</a></li>
                        <li><a href="#" class="dropdown-item">Sepatu</a></li>
                    </ul>
                </li>
                <li><a href="#">Master Data Karyawan</a></li>
                <li><a href="#">Pengaturan Profil</a></li>
            </ul>
        </div>
        
        <!-- Content -->
        <div class="flex-grow-1 p-3">
            <!-- Header -->
            <div class="header d-flex justify-content-between align-items-center mb-4">
                <div class="profile d-flex align-items-center">
                    <img src="{{ asset('resources/img/user.png') }}" alt="User Profile" class="rounded-circle me-2">
                    <span class="fw-bold">Pravitasi Sandrina</span>
                </div>
                <img src="{{ asset('resources/img/notification.png') }}" alt="Notifications" class="img-fluid">
            </div>
            <!-- Stats -->
            <div class="row g-3 mb-4">
                <div class="col-md">
                    <div class="stat bg-light text-center p-3 rounded shadow">
                        <h2 class="text-success">23</h2>
                        <p>Berkas Baru</p>
                    </div>
                </div>
                <div class="col-md">
                    <div class="stat bg-light text-center p-3 rounded shadow">
                        <h2 class="text-success">43</h2>
                        <p>Pengajuan Verifikasi</p>
                    </div>
                </div>
                <div class="col-md">
                    <div class="stat bg-light text-center p-3 rounded shadow">
                        <h2 class="text-success">150</h2>
                        <p>Telah Diverifikasi</p>
                    </div>
                </div>
                <div class="col-md">
                    <div class="stat bg-light text-center p-3 rounded shadow">
                        <h2 class="text-success">250</h2>
                        <p>Anggaran Disetujui</p>
                    </div>
                </div>
                <div class="col-md">
                    <div class="stat bg-light text-center p-3 rounded shadow">
                        <h2 class="text-success">537</h2>
                        <p>Total Data</p>
                    </div>
                </div>
            </div>
            <!-- Table -->
            <div class="table-container bg-white p-3 rounded shadow">
                <h2>Dashboard Restitusi Pengobatan</h2>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Aksi</th>
                                <th>Nama</th>
                                <th>ID Badge</th>
                                <th>Deskripsi</th>
                                <th>Nomor Surat</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><a href="#" class="btn btn-link">Lihat Detail</a></td>
                                <td>Ronny Khoerudin</td>
                                <td>3022126</td>
                                <td>Pengobatan RS PON</td>
                                <td>123/CJ/RS/2024</td>
                                <td>09 Agustus 2024</td>
                                <td><span class="status new">Berkas Baru</span></td>
                            </tr>
                            <tr>
                                <td><a href="#" class="btn btn-link">Lihat Detail</a></td>
                                <td>Lutfianto Ardian</td>
                                <td>3022127</td>
                                <td>Pengobatan</td>
                                <td>001/CJ/KKP/2024</td>
                                <td>25 Juni 2024</td>
                                <td><span class="status verified">Telah Diverifikasi</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
