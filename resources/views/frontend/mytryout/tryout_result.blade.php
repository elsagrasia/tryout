@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')

<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@php
    $user = Auth::user();
    $initials = strtoupper(\Illuminate\Support\Str::limit($user->name, 2, ''));
    $score = $finalScore;

    if ($score >= 80) {
        $message = [
            "Keren banget! Pertahankan!",           
            "Good job. Keep going."
        ];
        $color = '#28a745';
    } elseif ($score >= 60) {
        $message = [
            "Cukup bagus, tetap semangat!",           
            "Yuk bisa yuk, tingkatkan lagi."
        ];
        $color = '#fd7e14';
    } else {
        $message = [
            "Gapapa, ini bagian dari proses.",
            "Tetap semangat ya!",           
        ];
        $color = '#dc3545';
    }

    $chosen = $message[array_rand($message)];
@endphp

<div class="dashboard-container">
    <div class="breadcrumb-btn-box mb-4">
        <a href="{{ route('user.dashboard') }}" class="btn theme-btn theme-btn-sm-2">
            <i class="la la-arrow-left mr-2"></i>Kembali ke Dashboard
        </a>
    </div>

    {{-- =======================
         HEADER HASIL TRYOUT (STYLE BARU)
    ======================== --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 ">
        <div class="card-body text-center ">

            {{-- Avatar + nama --}}
            <div class="d-flex flex-column align-items-center mb-4">          
                <p class="fs-18 font-weight-medium mb-2">
                    Nilai
                </p>
                <h1 class="fs-60 font-weight-bold" style="color: {{ $color }};">
                    {{ $finalScore }}
                </h1>
                <h4 class="mt-1 font-weight-semi-bold">
                    {{ $chosen }}
                </h4>
            </div>        

            {{-- 3 kartu statistik seperti contoh pertama --}}
            <div class="row g-3 justify-content-center mt-2">

                {{-- Right Answer --}}
                <div class="col-md-3">
                    <div class="h-100 d-flex flex-column align-items-center justify-content-center"
                         style="background-color:#d9f7e5; border-radius:20px; padding:25px 10px;">
                        <div class="mb-2" style="font-size:32px; color:#15803d;">
                            <i class="la la-check-circle"></i>
                        </div>
                        <h3 class="mb-1" style="font-weight:700; color:#111827;">
                            {{ $correctCount }} / {{ $totalQuestions }}
                        </h3>
                        <p class="mb-0 text-muted">Jawaban Benar</p>
                    </div>
                </div>

                {{-- Wrong Answer --}}
                <div class="col-md-3">
                    <div class="h-100 d-flex flex-column align-items-center justify-content-center"
                         style="background-color:#FFE8C6; border-radius:20px; padding:25px 10px;">
                        <div class="mb-2" style="font-size:34px; color:#F59E0B;">
                            <i class="la la-question-circle"></i>
                        </div>
                        <h3 class="mb-1" style="font-weight:700; color:#111827;">
                            {{ $doubtCount }}
                        </h3>
                        <p class="mb-0 text-muted">Jawaban Ragu</p>
                    </div>
                </div>
                {{-- Wrong Answer --}}
                <div class="col-md-3">
                    <div class="h-100 d-flex flex-column align-items-center justify-content-center"
                         style="background-color:#E8EDF5; border-radius:20px; padding:25px 10px;">
                        <div class="mb-2" style="font-size:32px; color:#3E5B99;">
                            <i class="la la-minus-circle"></i>
                        </div>
                        <h3 class="mb-1" style="font-weight:700; color:#111827;">
                            {{ $unansweredCount }}
                        </h3>
                        <p class="mb-0 text-muted">Tidak Terjawab</p>
                    </div>
                </div>

                {{-- Time --}}
                <div class="col-md-3">
                    <div class="h-100 d-flex flex-column align-items-center justify-content-center"
                         style="background-color:#F5E3FF; border-radius:20px; padding:25px 10px;">
                        <div class="mb-2" style="font-size:32px; color:#9333EA;">
                            <i class="la la-clock-o"></i>
                        </div>
                        <h3 class="mb-1" style="font-weight:700; color:#111827;">
                            {{ gmdate("i:s", $elapsed_time) }}
                        </h3>
                        <p class="mb-0 text-muted">Menit</p>
                    </div>
                </div>

            </div>

            {{-- Tombol lihat hasil --}}
            <div class="mt-4">
                <a href="{{ route('tryout.explanation', $result->tryout_package_id) }}"
                   class="btn btn-outline-primary px-5 ">
                    <i class="la la-file-text-o mr-1"></i> Lihat Hasil
                </a>
            </div>

        </div>
    </div>
</div>

{{-- ======= CURRENT USER INFO ======= --}}
<div class="alert alert-info text-center shadow-sm">
    <strong>Posisi Kamu:</strong> #{{ $currentRank }} dengan nilai {{ $finalScore }}
</div>

{{-- ======= TABLE LEADERBOARD ======= --}}
<div class="table-responsive mt-4">
    <table class="table generic-table">
        <thead>
            <tr>
                <th scope="col" class="text-center">Rank</th>
                <th scope="col">Nama</th>
                <th scope="col" class="text-center">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaderboard as $userRow)
                <tr @if($userRow['user_id'] == auth()->id()) class="table-success" @endif>
                    <td class="text-center">#{{ $userRow['rank'] }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ !empty($userRow['photo']) ? url('upload/user_images/' . $userRow['photo']) : url('upload/no_image.jpg') }}"
                                 alt="Avatar" width="40" height="40" class="rounded-circle mr-2">
                            <span>{{ $userRow['name'] }}</span>
                        </div>
                    </td>
                    <td class="text-center">{{ $userRow['score'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- TOAST NOTIFIKASI POIN TRYOUT --}}
<div id="point-toast" class="point-toast d-none">
    <div class="sparkle-container"></div>

    <div class="point-toast-icon">
        <i class="la la-coins"></i>
    </div>
    <div class="point-toast-body">
        <div class="point-toast-title">Tryout Selesai!</div>
        <div class="point-toast-text">
            Selamat kamu mendapatkan <span id="point-toast-amount">+0</span> poin dari tryout ini 🎉
        </div>
    </div>

    <button type="button" class="point-toast-close">&times;</button>
</div>

{{-- BADGE POPUP --}}
@if (session()->has('earned_badges'))
<script>
  const earned = @json(session('earned_badges'));

  (async () => {
    for (const b of earned) {
      await Swal.fire({
        html: `
          <div style="text-align:center; margin-bottom:5px;">

            <img
              src="{{ url('/') }}/${b.icon}"
              alt="Badge"
              style="width:140px; height:140px; margin-bottom:10px;"
            >

            <h4 style="font-weight:700; margin:0;">
              ${b.name || 'Badge'}
            </h4>

            <h5 style="margin-top:8px; font-weight:500;">
              Selamat! Kamu Berhasil Meraih Badge Baru!
            </h5>
            <h6 style="margin-top:5px; font-weight:500;">
              ${b.description || ''}
            </h6>

          </div>
        `,
        width: 560,
        padding: '1.25em',
        color: '#1f2937',
        background: '#fff url("{{ asset('upload/gif/background.jpg') }}") center center / cover no-repeat',
        backdrop: `
          linear-gradient(rgba(0,0,0,.6), rgba(0,0,0,.6)),
          url("{{ asset('upload/gif/confetti-left.gif') }}") left top / 40% auto no-repeat,
          url("{{ asset('upload/gif/confetti-right.gif') }}") right bottom / 40% auto no-repeat
        `,
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
        customClass: {
          popup: 'rounded-swal'
        }
      });
    }
  })();


document.addEventListener('DOMContentLoaded', function () {
    // Ambil poin dari session (dari redirect controller)
    const pointsEarned = @json(session('points_earned'));

    // Kalau tidak ada poin, tidak perlu tampilkan toast
    if (!pointsEarned || pointsEarned <= 0) {
        return;
    }

    // ========== SPARKLE (sama seperti di blog) ==========
    function spawnSparkles() {
        const container = document.querySelector('.sparkle-container');
        if (!container) return;

        for (let i = 0; i < 4; i++) {
            const s = document.createElement('div');
            s.classList.add('sparkle');

            s.style.left = (10 + Math.random() * 200) + 'px';
            s.style.top  = (5 + Math.random() * 40) + 'px';
            s.style.animationDelay = (Math.random() * 0.3) + 's';

            container.appendChild(s);
            setTimeout(() => s.remove(), 1500);
        }
    }

    let sparkleInterval = null;

    function startSparkleLoop() {
        if (sparkleInterval) return;
        sparkleInterval = setInterval(() => {
            const toast = document.getElementById('point-toast');
            if (!toast || toast.classList.contains('d-none')) {
                clearInterval(sparkleInterval);
                sparkleInterval = null;
                return;
            }
            spawnSparkles();
        }, 1200);
    }

    function stopSparkleLoop() {
        if (sparkleInterval) {
            clearInterval(sparkleInterval);
            sparkleInterval = null;
        }
    }

    // ========== TOAST ==========
    function showPointToast(points) {
        const toast      = document.getElementById('point-toast');
        const amountSpan = document.getElementById('point-toast-amount');
        const closeBtn   = toast.querySelector('.point-toast-close');

        if (!toast || !amountSpan) return;

        amountSpan.textContent = `+${points}`;

        toast.classList.remove('d-none');
        requestAnimationFrame(() => {
            toast.classList.add('show');
        });

        spawnSparkles();
        startSparkleLoop();

        const hideTimer = setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.classList.add('d-none');
                stopSparkleLoop();
            }, 250);
        }, 8000);

        if (closeBtn) {
            closeBtn.onclick = function () {
                clearTimeout(hideTimer);
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.classList.add('d-none');
                    stopSparkleLoop();
                }, 250);
            };
        }
    }

    // Langsung tampilkan toast setelah halaman result dimuat
    showPointToast(pointsEarned);
});


</script>
@endif

<style>
  .rounded-swal {
    border-radius: 20px !important;
    overflow: hidden;
  }
</style>

@endsection
