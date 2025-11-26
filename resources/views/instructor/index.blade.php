@extends('instructor.instructor_dashboard')
@section('instructor')

@php
    $id         = Auth::user()->id;
    $instructor = App\Models\User::find($id);
    $status     = $instructor->status ?? null;
@endphp

<div class="page-content">

    {{-- WELCOME CARD --}}
    <div class="card border-0 shadow-sm mb-4" style="background-color: #e8f3ff;">
        <div class="card-body text-center py-5">
            <h2 class="fw-bold text-primary mb-2">Selamat Datang, Instructor!</h2>
            <p class="text-secondary mb-0">Anda berada di Dashboard Sistem E-Learning ukom.aja</p>
        </div>
    </div>

    {{-- STATISTIK UTAMA --}}
    <div class="row row-cols-1 row-cols-md-2">
        <div class="col mb-3">
            <div class="card radius-10 border-start border-0 border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Peserta</p>
                            <h4 class="my-1 text-primary">{{ $totalPeserta }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-primary text-primary ms-auto">
                            <i class='bx bxs-group'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> 

        <div class="col mb-3">
            <div class="card radius-10 border-start border-0 border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Rata-rata Nilai Peserta</p>
                            <h4 class="my-1 text-primary">{{ $rataRataNilai }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-primary text-primary ms-auto">
                            <i class='bx bxs-bar-chart-alt-2'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col mb-3">
            <div class="card radius-10 border-start border-0 border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Tryout</p>
                            <h4 class="my-1 text-primary">{{ $totalTryout }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-primary text-primary ms-auto">
                            <i class='fadeIn animated bx bx-notepad'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col mb-3">
            <div class="card radius-10 border-start border-0 border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Soal</p>
                            <h4 class="my-1 text-primary">{{ $totalSoal }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-primary text-primary ms-auto">
                            <i class="bx bx-question-mark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end row statistik -->

    {{-- ESTIMASI PER BIDANG – FULLWIDTH --}}
    <div class="row ">
        <div class="col-12">
            <div class="card radius-10 border-0 shadow-sm p-4" style="background-color: rgba(53, 143, 227, 0.04);">
                <h6 class="text-secondary mb-3 text-center" style="font-size: 1rem;">
                    Estimasi Pendalaman per Bidang (Semua Peserta)
                </h6>

                {{-- DAFTAR BIDANG (SCROLLABLE) --}}
                <div id="estimationCards"
                     style="max-height: 320px; overflow-y: auto; padding-right: 6px; margin-bottom: 18px;">
                </div>

                {{-- KETERANGAN WARNA --}}
                <div class="mt-2 text-center small">
                    <h6 class="text-secondary mb-2" style="font-size: 0.9rem;">Keterangan</h6>
                    <div class="d-flex justify-content-center flex-wrap" style="gap: 8px;">
                        <div class="d-flex align-items-center" style="margin: 2px 6px;">
                            <div class="rounded-circle me-1" style="width: 12px; height: 12px; background-color: #dc3545;"></div>
                            <small>Perlu pendalaman tinggi</small>
                        </div>
                        <div class="d-flex align-items-center" style="margin: 2px 6px;">
                            <div class="rounded-circle me-1" style="width: 12px; height: 12px; background-color: #fd7e14;"></div>
                            <small>Cukup perlu</small>
                        </div>
                        <div class="d-flex align-items-center" style="margin: 2px 6px;">
                            <div class="rounded-circle me-1" style="width: 12px; height: 12px; background-color: #28a745;"></div>
                            <small>Sudah baik</small>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div><!-- end row estimasi -->

</div> {{-- end page-content --}}

{{-- HANYA SCRIPT ESTIMASI, TANPA CHART --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartData   = @json($chartData ?? []);
        const chartLabels = @json($chartLabels ?? []);
        const estimationCards = document.getElementById('estimationCards');
        if (!estimationCards) return;

        estimationCards.innerHTML = '';

        // Gabungkan label + nilai
        let combined = chartLabels.map((label, index) => ({
            label: label,
            value: chartData[index] ?? 0
        }));

        // Urutkan dari nilai TERENDAH → TERTINGGI
        combined.sort((a, b) => a.value - b.value);

        combined.forEach(item => {
            let color = '';

            if (item.value < 60) {
                color = '#dc3545';   // merah
            } else if (item.value < 80) {
                color = '#fd7e14';   // oranye
            } else {
                color = '#28a745';   // hijau
            }

            estimationCards.innerHTML += `
                <div class="mb-2">
                    <div class="card border-0 shadow-sm text-center"
                        style="
                            background-color: ${color}0D;
                            border-left: 4px solid ${color};
                            border-radius: 10px;
                            max-width: 600px;
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

@endsection
