@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')

@php
    $id = Auth::user()->id;
    $profileData = App\Models\User::find($id);
@endphp

<div class="container py-4" style="max-width: 1100px; margin-top: -20px;">
    {{-- Profile Header --}}
    <div class="card border-0 shadow-sm p-4 rounded-4" style="background-color: #e8f2ff; border-radius: 25px;">
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
                   Selamat datang, {{ $profileData->name }}!
                </h3>
            </div>
        </div>

        <hr class="my-3" style="opacity: 0.2;">

        {{-- Tryout Performance --}}
        <h5 class="d-flex align-items-center mb-4" style="color:#000; font-weight:600;">
            <i class="bi bi-bar-chart-line me-2" style="color:#1f8b4c; font-size:20px;"></i>
            Performa Tryout
        </h5>


        <div class="row text-center g-4 mb-4 justify-content-center">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body text-center">
                        <h3 class="fw-bold mb-0" style="color:#1f8b4c;">{{ $totalTryout }}</h3>
                        <p class="text-muted mb-0">Total Tryout</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body text-center">
                        <h3 class="fw-bold mb-0" style="color:#0d6efd;">{{ $totalSelesai }} / {{ $totalTryout }}</h3>
                        <p class="text-muted mb-0">Sudah Dikerjakan</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body text-center">
                        <h3 class="fw-bold mb-0" style="color:#f4b400;">{{ $rataSkor }}</h3>
                        <p class="text-muted mb-0">Rata-rata Nilai</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4" ;>
    {{-- Card kiri: Chart --}}
        <div class="col-md-7" >
            <div class="card border-0 shadow-sm p-4 rounded-4 h-100" style="background-color: #e8f2ff; border-radius: 25px;">
                <h6 class="fw-bold mb-3">Rata-rata Nilai per Bidang</h6>
                <div style="height: 350px;">
                    <canvas id="subjectChart"></canvas>
                </div>
            </div>
        </div>

    {{-- Card kanan: Estimasi --}}
    <div class="col-md-5">
        <div class="card border-0 shadow-sm p-4 rounded-4 h-100"
            style="background-color: #e8f2ff; border-radius: 25px;">
            
            <h6 class="fw-bold mb-3 text-center" style="font-size: 1rem;">Estimasi Pendalaman per Bidang</h6>

            {{-- Daftar bidang (scrollable) --}}
            <div id="estimationCards" 
                style="max-height: 280px; overflow-y: auto; padding-right: 6px; margin-bottom: 20px;">
            </div>

            {{-- Keterangan warna (tetap di bawah, tidak ikut scroll) --}}
            <div class="mt-3 text-center small">
                <h6 class="fw-semibold mb-3" style="font-size: 0.9rem;">Keterangan</h6>
                <div class="d-flex justify-content-center flex-wrap" style="gap: 8px; margin-top: 6px;">
                    <div class="d-flex align-items-center" style="margin: 2px 6px;">
                        <div class="rounded-circle mr-1" style="width: 12px; height: 12px; background-color: #dc3545;"></div>
                        <small>Perlu pendalaman tinggi</small>
                    </div>
                    <div class="d-flex align-items-center" style="margin: 2px 6px;">
                        <div class="rounded-circle mr-1" style="width: 12px; height: 12px; background-color: #fd7e14;"></div>
                        <small>Cukup perlu</small>
                    </div>
                    <div class="d-flex align-items-center" style="margin: 2px 6px;">
                        <div class="rounded-circle mr-1" style="width: 12px; height: 12px; background-color: #28a745;"></div>
                        <small>Sudah baik</small>
                    </div>
                </div>

            </div>
        </div>
    </div>

    </div>


</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('subjectChart');
const subjectChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Rata-rata Nilai',
            data: @json($chartData),
            backgroundColor: '#358FF7',
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
                        family: 'Poppins, sans-serif',
                        size: 13,
                        weight: 'normal'
                    },
                    color: '#333'
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


// === BAGIAN TAMBAHAN: Estimasi Pendalaman per Bidang ===
document.addEventListener('DOMContentLoaded', function() {
    const chartData = @json($chartData);
    const chartLabels = @json($chartLabels);
    const estimationCards = document.getElementById('estimationCards');
    estimationCards.innerHTML = '';

    // 1. Gabungkan label + data
    let combined = chartLabels.map((label, index) => {
        return {
            label: label,
            value: chartData[index]
        };
    });

    // 2. Urutkan dari nilai TERKECIL → TERBESAR
    combined.sort((a, b) => a.value - b.value);

    // 3. Generate card setelah diurutkan
    combined.forEach(item => {
        let color = '';

        if (item.value < 60) {
            color = '#dc3545'; // merah
        } else if (item.value < 80) {
            color = '#fd7e14'; // oranye
        } else {
            color = '#28a745'; // hijau
        }

        estimationCards.innerHTML += `
            <div class="col-md-12 mb-2">
                <div class="card border-0 shadow-sm text-center estimation-card" 
                    style="
                        background-color: ${color}0D; 
                        border-left: 4px solid ${color}; 
                        border-radius: 10px; 
                        max-width: 320px; 
                        margin: 0 auto; 
                        min-height: 55px; 
                        display: flex; 
                        align-items: center;
                    ">
                    <div class="card-body py-2 px-3 d-flex justify-content-between align-items-center w-100">
                        <h6 class="fw-normal mb-0 text-dark" style="font-size: 13px;">${item.label}</h6>
                        <h6 class="fw-normal mb-0" style="font-size: 14px; color: ${color};">${item.value}%</h6>
                    </div>
                </div>
            </div>
        `;
    });
});

</script>




<style>
body {  
   
}
.card { 
    transition: all 0.2s ease; 
}
.card:hover { 
    transform: translateY(-2px); 
}
</style>
@endsection
