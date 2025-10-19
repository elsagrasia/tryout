@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')

@php
    $id = Auth::user()->id;
    $profileData = App\Models\User::find($id);
@endphp

<div class="container py-5" style="max-width: 1100px;">
    {{-- Profile Header --}}
    <div class="card border-0 shadow-sm p-4 rounded-4" style="background-color: #f9fcf9; border-radius: 25px;">
        <div class="d-flex align-items-center mb-4">
            {{-- Foto Profil --}}
            <img src="{{ (!empty($profileData->photo)) ? url('upload/user_images/'.$profileData->photo) : url('upload/no_image.jpg') }}"
                alt="Profile"
                class="rounded-circle me-4"
                width="90" height="90"
                style="object-fit: cover; border: 2px solid #e0e0e0;">
            
            {{-- Nama dan info --}}
            <div class="d-flex align-items-center" style="padding-left: 20px;">
                <h3 class="mb-2 fw-semibold" style="color: #1a3c34; font-size: 1.6rem;">
                    {{ $profileData->name }}
                </h3>
            </div>
        </div>

        <hr class="my-3" style="opacity: 0.2;">

        {{-- Tryout Performance --}}
        <h5 class="d-flex align-items-center mb-4" style="color:#000; font-weight:600;">
            <i class="bi bi-bar-chart-line me-2" style="color:#1f8b4c; font-size:20px;"></i>
            Tryout Performance
        </h5>


        <div class="row text-center g-4 mb-4 justify-content-center">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body text-center">
                        <h3 class="fw-bold mb-0" style="color:#1f8b4c;">{{ $totalTryout }}</h3>
                        <p class="text-muted mb-0">Total Tryout</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body text-center">
                        <h3 class="fw-bold mb-0" style="color:#0d6efd;">{{ $totalSelesai }}</h3>
                        <p class="text-muted mb-0">Sudah Dikerjakan</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body text-center">
                        <h3 class="fw-bold mb-0" style="color:#f4b400;">{{ $rataSkor }}</h3>
                        <p class="text-muted mb-0">Rata-rata Nilai</p>
                    </div>
                </div>
            </div>
        </div>

        

        {{-- Level Progress --}}
        <div class="mb-3 mt-2">
            <div class="d-flex justify-content-between small mb-2">
                <span class="fw-semibold text-dark">Level Progress</span>
                <span class="text-muted">{{ $progress ?? '53%' }}</span>
            </div>
            <div class="progress" style="height: 10px; border-radius: 12px; background-color: #e6e6e6;">
                <div class="progress-bar bg-success" role="progressbar"
                    style="width: {{ $progress ?? '53%' }}; border-radius: 12px;">
                </div>
            </div>
        </div>

        <p class="mt-2 text-muted small mb-0">Level 5 - Expert Quiz Master</p>
    </div>

    {{-- Chart --}}
    <div class="card border-0 shadow-sm p-4 rounded-4 mt-4" style="border-radius: 25px;">
        <h6 class="fw-bold mb-3">Rata-rata Nilai per Bidang</h6>
        <div style="height: 350px;"> 
            <canvas id="subjectChart"></canvas>
        </div>
    </div>
</div>

{{-- ChartJS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('subjectChart');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Rata-rata Nilai',
            data: @json($chartData),
            backgroundColor: '#66bb6a',
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false, // biar ukuran bisa diatur manual
        scales: {
            x: {
                ticks: {
                    font: {
                        weight: 'bold' // bikin tulisan bidang tebal
                    }
                }
            },
            y: {
                beginAtZero: true,
                max: 100
            }
        },
        plugins: {
            legend: { display: false }
        }
    }
});
</script>

<style>
body { 
    font-family: 'Poppins', sans-serif; 
    background-color: #f5f7fa; 
}
.card { 
    transition: all 0.2s ease; 
}
.card:hover { 
    transform: translateY(-2px); 
}
</style>
@endsection
