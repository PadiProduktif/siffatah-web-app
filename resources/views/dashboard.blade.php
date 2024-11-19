<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SI FATAH</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
            color: #4b5563;
        }
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #f3f4f6;
            padding: 20px;
        }
        .sidebar h1 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 10px 0;
        }
        .sidebar ul li a {
            text-decoration: none;
            color: #6b7280;
            font-size: 16px;
            display: block;
            padding: 10px;
            border-radius: 8px;
            transition: background-color 0.3s;
        }
        .sidebar ul li a:hover, .sidebar ul li a.active {
            background-color: #d1fae5;
            color: #047857;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header .profile {
            display: flex;
            align-items: center;
        }
        .header .profile img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .header .profile span {
            font-size: 18px;
            font-weight: bold;
        }
        .stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .stats .stat {
            width: 18%;
            background-color: #e5e7eb;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }
        .stats .stat h2 {
            font-size: 24px;
            color: #10b981;
        }
        .table-container {
            background: #ffffff;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        table th {
            background-color: #f3f4f6;
            color: #6b7280;
        }
        table tbody tr:hover {
            background-color: #f9fafb;
        }
        .status {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
            color: #ffffff;
        }
        .status.new { background-color: #f59e0b; }
        .status.verified { background-color: #10b981; }
        .status.rejected { background-color: #ef4444; }
        .status.processing { background-color: #3b82f6; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h1>SI FATAH</h1>
            <ul>
                <li><a href="#" class="active">Home</a></li>
                <li><a href="#">Restitusi Karyawan</a></li>
                <li><a href="#">Pengajuan Klaim</a></li>
                <li><a href="#">Kepesertaan Anggota</a></li>
                <li><a href="#">Ekses</a></li>
                <li><a href="#">Berkas Pengobatan</a></li>
                <li><a href="#">Kelengkapan Kerja</a></li>
                <li><a href="#">Master Data Karyawan</a></li>
                <li><a href="#">Pengaturan Profil</a></li>
            </ul>
        </div>
        <!-- Content -->
        <div class="content">
            <!-- Header -->
            <div class="header">
                <div class="profile">
                    <img src="{{ asset('resources/img/user.png') }}" alt="User Profile">
                    <span>Pravitasi Sandrina</span>
                </div>
                <div>
                    <img src="{{ asset('resources/img/notification.png') }}" alt="Notifications">
                </div>
            </div>
            <!-- Stats -->
            <div class="stats">
                <div class="stat">
                    <h2>23</h2>
                    <p>Berkas Baru</p>
                </div>
                <div class="stat">
                    <h2>43</h2>
                    <p>Pengajuan Verifikasi</p>
                </div>
                <div class="stat">
                    <h2>150</h2>
                    <p>Telah Diverifikasi</p>
                </div>
                <div class="stat">
                    <h2>250</h2>
                    <p>Anggaran Disetujui</p>
                </div>
                <div class="stat">
                    <h2>537</h2>
                    <p>Total Data</p>
                </div>
            </div>
            <!-- Table -->
            <div class="table-container">
                <h2>Dashboard Restitusi Pengobatan</h2>
                <table>
                    <thead>
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
                            <td><a href="#">Lihat Detail</a></td>
                            <td>Ronny Khoerudin</td>
                            <td>3022126</td>
                            <td>Pengobatan RS PON</td>
                            <td>123/CJ/RS/2024</td>
                            <td>09 Agustus 2024</td>
                            <td><span class="status new">Berkas Baru</span></td>
                        </tr>
                        <tr>
                            <td><a href="#">Lihat Detail</a></td>
                            <td>Lutfianto Ardian</td>
                            <td>3022127</td>
                            <td>Pengobatan</td>
                            <td>001/CJ/KKP/2024</td>
                            <td>25 Juni 2024</td>
                            <td><span class="status verified">Telah Diverifikasi</span></td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>