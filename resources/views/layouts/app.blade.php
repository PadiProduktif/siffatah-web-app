<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - SI FATAH')</title>
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
                <div class="profile d-flex align-items-center">
                    <img src="{{ asset('resources/img/user.png') }}" alt="User Profile" class="rounded-circle me-2" style="height: 50px; width: 50px;">
                    <span class="fw-bold">Pravitasi Sandrina</span>
                </div>
                <img src="{{ asset('resources/img/notification.png') }}" alt="Notifications" class="img-fluid" style="height: 50px;">
            </div>
            
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
