<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
        <title>@yield('title', 'Dashboard - SI FATAH')</title>

        <!-- CSS Libraries -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"> --}}
        {{-- <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.1/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" /> --}}
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Poppins:wght@400;700&display=swap" rel="stylesheet">

        <!-- Custom Styles -->
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                font-size: 16px;
                line-height: 1.6;
                color: #333;
                margin: 0;
                padding: 0;
                background-color: #f9fafb;
            }
            .sidebar {
                background-color: #f3f4f6;
                min-height: 100vh;
            }
            .sidebar a:hover,
            .sidebar a.active {
                background-color: #d1fae5;
                color: #047857;
            }
            .select2-container--bootstrap-5 .select2-selection {
                border-radius: 0.375rem; /* Sesuaikan dengan border-radius Bootstrap 5 */
            }
            
            .select2-container {
                z-index: 1050; /* Pastikan ini lebih tinggi dari modal Bootstrap */
            }
            .select2-dropdown {
                z-index: 1060 !important; /* Pastikan dropdown juga lebih tinggi */
            }
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
                cursor: pointer;
            } /* Pointer berubah menjadi tangan */

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
                /* background-color: #00FC74; */}
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

        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
        {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

        <!-- Chart.js v2 -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>


        <script>
            // Toast Notifications
            @if(session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: '{{ session("success") }}',
                    background: '#4CAF50',
                    color: '#ffffff',
                    showConfirmButton: false,
                    timer: 3000
                });
            @endif
            @if(session('error'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: '{{ session("error") }}',
                    background: '#f44336',
                    color: '#ffffff',
                    showConfirmButton: false,
                    timer: 3000
                });
            @endif

            
        
            // $('.select2').select2({
            //         theme: 'bootstrap-5', // Menggunakan tema Bootstrap 5
            //     placeholder: "Pilih Data", // Placeholder default
            //     allowClear: true, // Tombol clear
            //     width: '100%' // Pastikan lebar sesuai kontainer
            // });

        </script>
        @stack('scripts')
    </body>
</html>