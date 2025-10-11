@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')

@php
    if (!isset($results)) {
        $results = collect();
    } elseif (!($results instanceof \Illuminate\Support\Collection)) {
        $results = collect($results);
    }

    $correctCount = $correctCount ?? $results->where('is_correct', true)->count();
    $unansweredCount = $unansweredCount ?? $results->filter(function($r){
        return empty($r['user_answer']);
    })->count();
    $wrongCount = $wrongCount ?? max(0, $results->count() - $correctCount - $unansweredCount);
    $finalScore = $finalScore ?? ($results->count() ? round(($correctCount / $results->count()) * 100, 2) : 0);

    $perPage = 10;
    $page = (int) request()->get('page', 1);
    if ($page < 1) $page = 1;
    $offset = ($page - 1) * $perPage;
    $totalPages = max(1, (int) ceil($results->count() / $perPage));
    $pagedResults = $results->slice($offset, $perPage)->values();
@endphp

<div class="container mt-4 mb-5" style="color: black;">

    {{-- Header Tryout --}}
    <div class="card border-0 shadow-sm mb-4">
        {{-- Warna header biru muda --}}
        <div class="card-header fw-semibold" style="background-color: #6fb8ff; color: black;">
            {{-- Hasil Tryout: {{ $tryout->tryout_name ?? '-' }} --}}
            Hasil Tryout: {{ $tryoutName }}

        </div>

        {{-- Body hasil tryout (semua teks hitam) --}}
        <div class="card-body text-center" style="color: black;">
            <h4>Skor Akhir: <span style="color: black;">{{ $finalScore }}</span></h4>
            <p class="mt-2">
                Jawaban Benar: <strong>{{ $correctCount }}</strong> |
                Salah: <strong>{{ $wrongCount }}</strong> |
                Tidak Dijawab: <strong>{{ $unansweredCount }}</strong>
            </p>
        </div>
    </div>

    {{-- Soal (10 per halaman) --}}
    @forelse ($pagedResults as $index => $result)
        <div class="card mb-4 shadow-sm" style="color: black;">
            <div class="card-body">
                <h5 class="fw-bold">Soal {{ $offset + $index + 1 }}</h5>

                @if(!empty($result['vignette']))
                    <p class="mb-2" style="color: black;">{{ $result['vignette'] }}</p>
                @endif

                {{-- @if (!empty($result['image']))
                    <div class="text-center mb-3">
                        <img src="{{ asset('upload/questions/'.$result['image']) }}" 
                            alt="Gambar Soal" 
                            class="img-fluid rounded shadow-sm"
                            style="max-width: 100%; height: auto; object-fit: contain;">
                    </div>
                @endif --}}
                @if (!empty($result['image']))
                    <div class="text-center mb-3">
                        <img src="{{ asset($result['image']) }}" 
                            alt="Gambar Soal" 
                            class="img-fluid rounded shadow-sm"
                            style="max-width: 100%; height: auto; object-fit: contain;">
                    </div>
                @endif



                <p>{!! $result['question'] ?? '-' !!}</p>

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
                            <li style="margin-bottom: 4px;">
                                {{-- Jawaban user BENAR --}}
                                @if($isUserAnswer && $isCorrect)
                                    <span style="color: green; font-weight: 600;">
                                        {{ strtoupper($opt) }}. {{ $optionText }} ✅
                                    </span>

                                {{-- Jawaban user SALAH --}}
                                @elseif($isUserAnswer && !$isCorrect)
                                    <span style="color: red; font-weight: 600;">
                                        {{ strtoupper($opt) }}. {{ $optionText }} ❌
                                    </span>

                                {{-- Opsi lain --}}
                                @else
                                    <span style="color: black;">
                                        {{ strtoupper($opt) }}. {{ $optionText }}
                                    </span>
                                @endif
                            </li>
                        @endif
                    @endforeach
                </ul>

                {{-- Jika salah, tampilkan jawaban benar di bawah semua opsi --}}
                @if(!empty($result['user_answer']) && strtolower($result['user_answer']) !== strtolower($result['correct_option']))
                    <p style="margin-left: 10px; color: black; font-weight: 500;">
                        Jawaban benar:
                        <strong>
                            {{ strtoupper($result['correct_option']) }}.
                            {{ $result['options'][strtolower($result['correct_option'])] ?? '-' }}
                        </strong>
                    </p>
                @endif


                {{-- Pembahasan --}}
                @if(!empty($result['explanation']))
                    <p style="color: black;">
                        <strong>Pembahasan:</strong><br>
                        {!! $result['explanation'] !!}
                    </p>
                @endif
            </div>
        </div>
    @empty
        <div class="alert alert-info" style="color: black;">Tidak ada hasil soal untuk ditampilkan.</div>
    @endforelse

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-4">
        <nav>
            <ul class="pagination">
                <li class="page-item {{ $page <= 1 ? 'disabled' : '' }}">
                    <a class="page-link" href="?page={{ max(1, $page - 1) }}">Prev</a>
                </li>
                @for ($i = 1; $i <= $totalPages; $i++)
                    <li class="page-item {{ $i == $page ? 'active' : '' }}">
                        <a class="page-link" href="?page={{ $i }}">{{ $i }}</a>
                    </li>
                @endfor
                <li class="page-item {{ $page >= $totalPages ? 'disabled' : '' }}">
                    <a class="page-link" href="?page={{ min($totalPages, $page + 1) }}">Next</a>
                </li>
            </ul>
        </nav>
    </div>

    <div class="text-center mt-3">
        <a href="{{ route('my.tryout') }}" class="btn btn-secondary">
            Kembali ke Dashboard
        </a>
    </div>

</div>


@endsection
