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

    {{-- =======================
         DAFTAR HASIL SOAL
    ======================== --}}
    @forelse ($results as $index => $result)
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Soal {{ $index + 1 }}</h5>

                {{-- Vignette --}}
                @if(!empty($result['vignette']))
                    <p class="mb-2" style="color: #000;">{!! $result['vignette'] !!}</p>
                @endif

                {{-- Gambar soal --}}
                @if (!empty($result['image']))
                    <div class="text-center mb-3">
                        <img src="{{ asset($result['image']) }}" 
                            alt="Gambar Soal" 
                            class="img-fluid rounded shadow-sm"
                            style="max-width: 100%; height: auto; object-fit: contain;">
                    </div>
                @endif

                {{-- Teks pertanyaan --}}
                <p class="mb-3" style="font-weight: 600; color: #000;">
                    {!! $result['question'] ?? '-' !!}
                </p>

                {{-- Pilihan Jawaban --}}
                <ul class="list-unstyled ms-3" style="color: black;">
                    @foreach (['a','b','c','d','e'] as $opt)
                        @php
                            $optionText = $result['options'][$opt] ?? null;
                            $userAnswer = strtolower($result['user_answer'] ?? '');
                            $correctOption = strtolower($result['correct_option'] ?? '');
                            $isUserAnswer = $userAnswer === $opt;
                            $isCorrect = $correctOption === $opt;
                        @endphp

                        @if($optionText)
                            <li class="mb-2">
                                {{-- ✅ Jawaban BENAR --}}
                                @if($isUserAnswer && $isCorrect)
                                    <span style="color: green; font-weight: 600;">
                                        {{ strtoupper($opt) }}. {{ $optionText }} ✅
                                    </span>

                                {{-- ❌ Jawaban SALAH --}}
                                @elseif($isUserAnswer && !$isCorrect)
                                    <span style="color: red; font-weight: 600;">
                                        {{ strtoupper($opt) }}. {{ $optionText }} ❌
                                    </span>

                                {{-- ⚪ Opsi lain (tidak dipilih) --}}
                                @elseif($userAnswer === null || $userAnswer === '')
                                    <span style="color: gray;">
                                        {{ strtoupper($opt) }}. {{ $optionText }}
                                    </span>

                                {{-- Opsi normal --}}
                                @else
                                    <span style="color: black;">
                                        {{ strtoupper($opt) }}. {{ $optionText }}
                                    </span>
                                @endif
                            </li>
                        @endif
                    @endforeach
                </ul>

                {{-- Tampilkan jawaban benar jika salah atau kosong --}}
                @if(empty($result['user_answer']) || strtolower($result['user_answer']) !== strtolower($result['correct_option']))
                    <p style="margin-left: 10px; color: #000;">
                        <strong>Jawaban Benar:</strong>
                        {{ strtoupper($result['correct_option']) }}.
                        {{ $result['options'][strtolower($result['correct_option'])] ?? '-' }}
                    </p>
                @endif

                {{-- Pembahasan --}}
                @if(!empty($result['explanation']))
                    <div class="mt-2">
                        <strong>Pembahasan:</strong>
                        <p class="mb-0">{!! $result['explanation'] !!}</p>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="alert alert-info">Belum ada hasil untuk tryout ini.</div>
    @endforelse

    {{-- =======================
         PAGINATION
    ======================== --}}
                                    <nav aria-label="Page navigation example" class="pagination-box">
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item">
                                            <a class="page-link" href="#" aria-label="Previous">
                                                <span aria-hidden="true"><i class="la la-arrow-left"></i></span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                        </li>
                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item">
                                            <a class="page-link" href="#" aria-label="Next">
                                                <span aria-hidden="true"><i class="la la-arrow-right"></i></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
    <div class="text-center mt-4">
        <a href="{{ route('my.tryout') }}" class="btn btn-secondary">
            ← Kembali ke Dashboard
        </a>
    </div>

</div>

@endsection
