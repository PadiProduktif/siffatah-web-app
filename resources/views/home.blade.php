@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

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
    </div>

    <!-- Bar Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Statistik Berkas Bulanan</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyStats" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('monthlyStats').getContext('2d');
    
    // Data dummy untuk 6 bulan terakhir
    const data = {
        labels: ['Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November'],
        datasets: [
            {
                label: 'Berkas Baru',
                data: [15, 20, 25, 18, 23, 28],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            },
            {
                label: 'Pengajuan Verifikasi',
                data: [30, 35, 40, 38, 43, 45],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Telah Diverifikasi',
                data: [100, 120, 130, 140, 150, 160],
                backgroundColor: 'rgba(40, 167, 69, 0.2)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 1
            }
        ]
    };

    new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 30
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        }
    });
});
</script>
@endpush

@endsection
