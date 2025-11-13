@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')
<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="mb-4">
    <div class="breadcrumb-btn-box mb-4">
        <a href="{{ route('user.dashboard') }}" class="btn theme-btn theme-btn-sm-2 "><i class="la la-arrow-left mr-2"></i>Kembali ke Dashboard</a>
    </div>
    {{-- =======================
         HEADER HASIL TRYOUT
    ======================== --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header fw-semibold" style="background-color: #008cff; color: white;">
            Hasil Tryout: {{ $tryoutName }}
        </div>

        <div class="card-body text-center">
            <h4>Skor Akhir: <strong>{{ $finalScore }}</strong></h4>
            @php
    $score = $finalScore;

    if ($score >= 85) {
        $message = [
            "Keren Banget! pertahankan!",
            "Mantul. Gas terusss!",
            "Good job. Keep going."
        ];
    } elseif ($score >= 75) {
        $message = [
            "Cukup bagus, tetap semangat!",
            "Lumayan oke, masih bisa lebih baik.",
            "Yuk bisa yuk, tingkatkan lagi."
        ];
    } elseif ($score >= 65) {
        $message = [
            "Not bad. Fokusin bagian miss-nya.",
            "Dasarnya udah dapet, tinggal perkuat aja.",
            "Santai, tinggal poles beberapa bagian."
        ];
    } else {
        $message = [
            "Gapapa, ini bagian dari proses.",
            "Belum pas, tapi bukan berarti jauh.",
            "Ambil napas dulu. Besok coba lagi pelan-pelan."
        ];
    }

    $chosen = $message[array_rand($message)];
@endphp

<p class="mt-2 fw-semibold" style="font-size: 15px; color:#333;">
    {{ $chosen }}
</p>

            <p class="mt-2 mb-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="20"  viewBox="0 0 24 24" fill="none">

                <g id="SVGRepo_bgCarrier" stroke-width="0"/>

                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>

                <g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12ZM16.0303 8.96967C16.3232 9.26256 16.3232 9.73744 16.0303 10.0303L11.0303 15.0303C10.7374 15.3232 10.2626 15.3232 9.96967 15.0303L7.96967 13.0303C7.67678 12.7374 7.67678 12.2626 7.96967 11.9697C8.26256 11.6768 8.73744 11.6768 9.03033 11.9697L10.5 13.4393L12.7348 11.2045L14.9697 8.96967C15.2626 8.67678 15.7374 8.67678 16.0303 8.96967Z" fill="#38BB0C"/> </g>

                </svg> Benar: <strong>{{ $correctCount }}</strong> |
                ❌ Salah: <strong>{{ $wrongCount }}</strong> |
                ⚪ Tidak Dijawab: <strong>{{ $unansweredCount }}</strong>
            </p>
            <p class="text-muted small mt-1 mb-2">Total Soal: {{ $totalQuestions }} |
                Waktu Pengerjaan: {{ gmdate("H:i:s", $elapsed_time) }}
            </p>
                <a href="{{ route('tryout.explanation', $result->tryout_package_id) }}" 
       class="btn theme-btn theme-btn-sm theme-btn-white border px-5 py-1">
       <i class="la la-file-text-o mr-1"></i> Lihat Hasil
    </a>
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
                @foreach ($leaderboard as $user)
                <tr @if($user['user_id'] == auth()->id()) class="table-success" @endif>
                    <td class="text-center">#{{ $user['rank'] }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ !empty($user['photo']) ? url('upload/user_images/' . $user['photo']) : url('upload/no_image.jpg') }}"
                                alt="Avatar" width="40" height="40" class="rounded-circle mr-2">
                            <span>{{ $user['name'] }}</span>
                        </div>
                    </td>
                    <td class="text-center">{{ $user['score'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@if (session()->has('earned_badges'))
<script>
  const earned = @json(session('earned_badges'));

  (async () => {
    for (const b of earned) {
      await Swal.fire({
        title: `${b.name || 'Badge'}`,
        html: `
          <div style="text-align:center">
            <img 
              src="{{ url('/') }}/${b.icon}" 
              alt="Badge" 
              style="width:120px; height:120px; margin-bottom:10px;"
            >
            <h5 style="margin-top:8px;">Selamat Kamu Mendapatkan Badge Baru!</h5>            
          </div>
        `,
        width: 560,
        padding: '1.25em',
        color: '#1f2937',
        background: '#fff url("{{ asset('upload/gif/background.jpg') }}") center center / cover no-repeat',
        backdrop: `
          linear-gradient(rgba(0,0,0,.35), rgba(0,0,0,.35)),
          url("{{ asset('upload/gif/confetti-left.gif') }}") left top / 40% auto no-repeat,
          url("{{ asset('upload/gif/confetti-right.gif') }}") right bottom / 40% auto no-repeat
        `,
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
      
        focusConfirm: false,
      });
    }
  })();
</script>
@endif



@endsection
