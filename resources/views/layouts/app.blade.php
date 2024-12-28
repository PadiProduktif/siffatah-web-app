<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Dashboard - SI FATAH')</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>

        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Poppins:wght@400;700&display=swap" rel="stylesheet">


        <style>
            /* body {
                font-family: Arial, sans-serif;
                background-color: #f9fafb;
            } */
            /* Terapkan font Poppins ke seluruh dokumen */
            body {
                font-family: 'Poppins', sans-serif;
                /* font-family: 'Sora', sans-serif; */
                /* font-family: 'Libre Baskerville', sans-serif; */
                /* font-family: 'Montserrat', sans-serif; */
                font-size: 16px;
                line-height: 1.6;
                color: #333;
                margin: 0;
                padding: 0;
                background-color: #f9fafb;
            }

            /* h1 {
                font-weight: 700;
                font-size: 2.5rem;
                margin-bottom: 1rem;
            } */

            h2 {
                font-weight: 500;
                font-size: 2rem;
                margin-bottom: 0.8rem;
            }

            p {
                font-size: 1rem;
                margin-bottom: 1rem;
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

            .accordion-button {
                /* background: none; */
                /* border: none; */
                padding: 10px;
                /* font-size: inherit; */
                /* color: #0d6efd; */
                /* text-decoration: none; */
                /* display: inline; */
            }
            
            .icon-circle {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                /* background-color: #00FC74; */
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
            </div>
        </div> 

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

        {{-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script> --}}
        {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script> --}}
        {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> --}}

        {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script> --}}
        
        {{-- kalau pakai chart js v2 --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>

        {{-- kalau pakai chartjs  v3
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script> --}}
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>

        <script>
            // Toast Success
            @if(session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: '{{ session("success") }}',
                    background: '#4CAF50',  // Hijau untuk success
                    color: '#ffffff',  // Teks putih agar kontras dengan latar hijau
                    showConfirmButton: false,
                    timer: 3000
                });
            @endif
        
            // Toast Error
            @if(session('error'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: '{{ session("error") }}',
                    background: '#f44336',  // Merah untuk error
                    color: '#ffffff',  // Teks putih agar kontras dengan latar hijau
                    showConfirmButton: false,
                    timer: 3000
                });
            @endif
            
            $(document).ready(function() {
                $('.select2').select2({
                    theme: 'bootstrap-5', // Pastikan menggunakan tema bootstrap 5 jika diperlukan
                    placeholder: "Pilih Data",
                    allowClear: true
                });
            });
        </script>

        @stack('scripts')
    </body>
</html>