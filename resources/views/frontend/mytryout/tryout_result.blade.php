@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')

<div class="container mt-4 mb-5" style="color: black;">

    {{-- =======================
         HEADER HASIL TRYOUT
    ======================== --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header fw-semibold" style="background-color: #6fb8ff; color: black;">
            Hasil Tryout: {{ $tryoutName }}
        </div>

        <div class="card-body text-center">
            <h4>Skor Akhir: <strong>{{ $finalScore }}</strong></h4>
            <p class="mt-2 mb-0">
                ✅ Benar: <strong>{{ $correctCount }}</strong> |
                ❌ Salah: <strong>{{ $wrongCount }}</strong> |
                ⚪ Tidak Dijawab: <strong>{{ $unansweredCount }}</strong>
            </p>
            <p class="text-muted small mt-1">Total Soal: {{ $totalQuestions }} |
                Waktu Pengerjaan: {{ gmdate("H:i:s", $elapsed_time) }}
            </p>
        </div>
    </div>
</div>

@endsection
