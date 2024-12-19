<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>@yield('title', 'Dashboard - SI FATAH')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>


    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

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
        .status.new { background-color: #f59e0b; color: #fff; padding: 5px 10px; border-radius: 5px; }
        .status.verified { background-color: #10b981; color: #fff; padding: 5px 10px; border-radius: 5px; }
        .status.rejected { background-color: #ef4444; color: #fff; padding: 5px 10px; border-radius: 5px; }
        .status.processing { background-color: #3b82f6; color: #fff; padding: 5px 10px; border-radius: 5px; }
        .uploaded-file {
            display: inline-block;
            margin: 10px;
            text-align: center;
        }
        
        .uploaded-file img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        
        .uploaded-file p {
            margin-top: 5px;
            font-size: 14px;
        }
        .detailRow {
                cursor: pointer; /* Pointer berubah menjadi tangan */
            }
        .detailRow:hover {
                background-color: #f8f9fa; /* Highlight baris ketika dihover */
        }
    </style>
    @stack('styles')
</head>
<body>
    
    <div class="d-flex">
        @include('components.sidebar')

        <div class="flex-grow-1 p-3">
            <div class="header d-flex justify-content-between align-items-center mb-4">
                <div class="profile d-flex align-items-center">
                    <i class="bi bi-person-circle me-2" style="font-size: 2rem; color: #6b7280;"></i>
                    <span class="fw-bold"><strong>{{ auth()->user()->fullname }}</strong></span>
                </div>
            </div>

            @yield('content')

            <!-- Script to Show Toast -->
            @if (session('toast_message'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: '{{ session('toast_message') == 'success' ? 'success' : 'error' }}',
                        title: '{{ session('toast_message') == 'success' ? session('toast_success') : session('toast_message') }}',
                        showConfirmButton: false,
                        timer: 3000,
                        background: '{{ session('toast_message') == 'success' ? '#28a745' : '#dc3545' }}', // Hijau untuk sukses, Merah untuk error
                        color: '#ffffff' // Warna teks menjadi putih agar kontras dengan background
                    });
                });
            </script>
            @endif
        </div>
    </div> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script> --}}

    {{-- kalau pakai chart js v2 --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>

    kalau pakai chartjs  v3
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script> --}}

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @stack('scripts')

</body>
</html>