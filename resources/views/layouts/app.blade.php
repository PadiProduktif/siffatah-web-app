<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - SI FATAH')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

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
</style>

    </style>
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main Content -->
        <div class="flex-grow-1 p-3">
            <!-- Header -->
            <div class="header d-flex justify-content-between align-items-center mb-4">
                <!-- Profile Section -->
                <div class="profile d-flex align-items-center">
                    <i class="bi bi-person-circle me-2" style="font-size: 2rem; color: #6b7280;"></i>
                    <span class="fw-bold">Pravitasi Sandrina</span>
                </div>
                <!-- Settings Icon -->
                <i class="bi bi-gear-fill" style="font-size: 2rem; color: #6b7280;"></i>
            </div>

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
