{{-- @extends('frontend.dashboard.user_dashboard')
@section('userdashboard')

<div class="container mt-4 mb-5">
    <div class="card shadow-sm border-0">
        <!-- Header Tryout -->
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">{{ $tryout->tryout_name }}</h5>
            <span class="fw-semibold"><i class="la la-clock me-1"></i>{{ $tryout->duration }} Menit</span>
        </div>

        <!-- Body Tryout -->
        <div class="card-body">
            <form action="{{ route('tryout.submit', $tryout->id) }}" method="POST">
                @csrf

                <div class="mb-3 text-muted small">
                    Jumlah Soal: <strong>{{ $tryout->questions->count() }}</strong>
                </div>

                <hr>

                <!-- Loop Soal -->
                @foreach ($tryout->questions as $index => $question)
                    <div class="mb-4">
                        <h6 class="fw-bold">{{ $index + 1 }}. {{ $question->question_text }}</h6>

                        @foreach (['A', 'B', 'C', 'D', 'E'] as $option)
                            @php 
                                $optionField = 'option_' . strtolower($option);
                            @endphp
                            @if(!empty($question->$optionField))
                                <div class="form-check mt-2">
                                    <input 
                                        class="form-check-input" 
                                        type="radio" 
                                        name="answers[{{ $question->id }}]" 
                                        id="q{{ $question->id }}_{{ $option }}" 
                                        value="{{ $option }}">
                                    <label class="form-check-label" for="q{{ $question->id }}_{{ $option }}">
                                        {{ $option }}. {{ $question->$optionField }}
                                    </label>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <hr>
                @endforeach

                <!-- Tombol Submit -->
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="la la-check-circle me-1"></i> Selesai & Kirim Jawaban
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection --}} 




{{-- @extends('frontend.dashboard.user_dashboard')
@section('userdashboard')

<div class="container-fluid mt-3 mb-5">
    <div class="row">
        <!-- Sidebar kiri: Nomor Soal dan Timer -->
        <div class="col-md-3 border-end">
            <h6 class="fw-bold mb-3">Daftar Soal</h6>
            <div class="d-flex flex-wrap justify-content-start" style="gap:10px;">
                @foreach ($tryout->questions as $index => $question)
                    <button type="button" 
                        class="btn btn-outline-danger btn-sm question-btn" 
                        data-index="{{ $index }}"
                        id="btn-{{ $question->id }}"
                        style="width:45px; height:45px;">
                        {{ $index + 1 }}
                    </button>
                @endforeach
            </div>

            <div class="mt-4">
                <h6 class="fw-bold">Sisa Waktu:</h6>
                <div id="timer" class="fw-bold text-danger fs-5">--:--:--</div>
            </div>

            @php
                $hours = floor($tryout->duration / 60);
                $minutes = $tryout->duration % 60;
            @endphp

            <div class="mt-3 text-muted small">
                Durasi Total: 
                @if ($hours > 0)
                    {{ $hours }} Jam
                @endif
                @if ($minutes > 0)
                    {{ $minutes }} Menit
                @endif
            </div>

           
        </div>

        <!-- Bagian kanan: Soal -->
        <div class="col-md-9">
            <form action="{{ route('tryout.submit', $tryout->id) }}" method="POST" id="tryoutForm">
                @csrf

                @foreach ($tryout->questions as $index => $question)
                <div class="question-card" id="question-{{ $index }}" style="{{ $index === 0 ? '' : 'display:none;' }}">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold">Soal Nomor {{ $index + 1 }}</h5>
                        <span class="badge bg-info text-white">{{ $tryout->tryout_name }}</span>
                    </div>

                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-body">
                            <p class="fw-semibold">{{ $question->question_text }}</p>

                            @foreach (['A', 'B', 'C', 'D', 'E'] as $option)
                                @php 
                                    $optionField = 'option_' . strtolower($option);
                                @endphp
                                @if(!empty($question->$optionField))
                                    <div class="form-check mb-2">
                                        <input type="radio" 
                                            class="form-check-input answer-radio" 
                                            name="answers[{{ $question->id }}]" 
                                            value="{{ $option }}"
                                            data-question="{{ $question->id }}">
                                        <label class="form-check-label">{{ $option }}. {{ $question->$optionField }}</label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Tombol Navigasi -->
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary prev-btn" style="{{ $index == 0 ? 'visibility:hidden;' : '' }}">← Sebelumnya</button>
                        @if ($index == count($tryout->questions) - 1)
                            <button type="submit" class="btn btn-success">Selesai & Kirim</button>
                        @else
                            <button type="button" class="btn btn-primary next-btn">Selanjutnya →</button>
                        @endif
                    </div>
                </div>
                @endforeach
            </form>
        </div>
    </div>
</div> --}}

{{-- SCRIPT NAVIGASI + TIMER --}}
{{-- <script>
document.addEventListener('DOMContentLoaded', function () {
    const totalQuestions = {{ count($tryout->questions) }};
    let currentIndex = 0;

    const questionCards = document.querySelectorAll('.question-card');
    const questionButtons = document.querySelectorAll('.question-btn');

    // Highlight aktif
    function highlightActiveButton() {
        questionButtons.forEach((btn, idx) => {
            btn.classList.remove('btn-primary');
            if (idx === currentIndex) {
                btn.classList.add('btn-primary');
                btn.classList.remove('btn-outline-danger', 'btn-success');
            }
        });
    }
    highlightActiveButton();

    // Navigasi antar soal
    document.querySelectorAll('.next-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            questionCards[currentIndex].style.display = 'none';
            currentIndex++;
            questionCards[currentIndex].style.display = 'block';
            highlightActiveButton();
            window.scrollTo(0, 0);
        });
    });

    document.querySelectorAll('.prev-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            questionCards[currentIndex].style.display = 'none';
            currentIndex--;
            questionCards[currentIndex].style.display = 'block';
            highlightActiveButton();
            window.scrollTo(0, 0);
        });
    });

    // Warna tombol saat dijawab
    document.querySelectorAll('.answer-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            const qid = this.dataset.question;
            const btn = document.getElementById('btn-' + qid);
            if (btn) {
                btn.classList.remove('btn-outline-danger', 'btn-primary');
                btn.classList.add('btn-success', 'text-white');
            }
        });
    });

    // Klik tombol nomor soal (langsung lompat)
    questionButtons.forEach((btn, idx) => {
        btn.addEventListener('click', function() {
            questionCards[currentIndex].style.display = 'none';
            currentIndex = idx;
            questionCards[currentIndex].style.display = 'block';
            highlightActiveButton();
            window.scrollTo(0, 0);
        });
    });

    // Timer (format jam:menit:detik)
    let duration = {{ $tryout->duration * 60 }};
    const timerDisplay = document.getElementById('timer');

    const timerInterval = setInterval(() => {
        const hours = Math.floor(duration / 3600);
        const minutes = Math.floor((duration % 3600) / 60);
        const seconds = duration % 60;

        timerDisplay.textContent =
            `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

        duration--;

        if (duration < 0) {
            clearInterval(timerInterval);
            alert('Waktu habis! Jawaban akan dikirim otomatis.');
            document.getElementById('tryoutForm').submit();
        }
    }, 1000);
});
</script>

@endsection --}}




@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')

<div class="container-fluid mt-3 mb-5">
    <div class="row">
        <!-- ============================
             SIDEBAR KIRI - DAFTAR SOAL
        ============================= -->
        <div class="col-md-3 border-end">
            <h6 class="fw-bold mb-3">Daftar Soal</h6>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($tryout->questions as $index => $question)
                    <button type="button" 
                        class="btn btn-secondary btn-sm question-btn" 
                        data-index="{{ $index }}"
                        id="btn-{{ $question->id }}"
                        style="width:45px; height:45px; border: 1px solid #ccc;">
                        {{ $index + 1 }}
                    </button>
                @endforeach
            </div>

            <div class="mt-4">
                <h6 class="fw-bold">Sisa Waktu:</h6>
                <div id="timer" class="fw-bold text-danger fs-5"></div>
            </div>
        </div>

        <!-- ============================
             BAGIAN KANAN - SOAL TRYOUT
        ============================= -->
        <div class="col-md-9">
            <form id="tryoutForm" action="{{ route('tryout.submit', $tryout->id) }}" method="POST">
                @csrf

                @foreach ($tryout->questions as $index => $question)
                    <div class="question-card" 
                        style="{{ $index == 0 ? '' : 'display:none;' }}">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold">Soal Nomor {{ $index + 1 }}</h5>
                            <span class="badge bg-info text-white">
                                {{ $tryout->tryout_name }}
                            </span>
                        </div>

                        <div class="card shadow-sm border-0">

                            <div class="card-body">

                                {{-- Tampilkan vignette jika ada --}}
                                @if (!empty($question->vignette))
                                    <p class="mb-3 text-dark" style="color: #000;">
                                        {!! $question->vignette !!}
                                    </p>
                                @endif

                                {{-- Tampilkan gambar soal kalau ada --}}
                                {{-- @if (!empty($question->image))
                                    <div class="text-center mb-3">
                                        <img src="{{ asset($question->image) }}" 
                                            alt="Gambar Soal" 
                                            class="img-fluid rounded shadow-sm" 
                                            style="max-width: 80%; height: auto;">
                                    </div>
                                @endif --}}
                                @if (!empty($question->image))
                                    <div class="text-center mb-3">
                                        <img src="{{ asset($question->image) }}" 
                                            alt="Gambar Soal" 
                                            class="img-fluid rounded shadow-sm"
                                            style="max-width: 100%; height: auto; object-fit: contain;">
                                    </div>
                                @endif

                                {{-- Tampilkan teks pertanyaan utama --}}
                                <p class="mb-4 text-dark" style="color: #000; font-weight:700; font-size: 1.05rem;">
                                    {!! $question->question_text !!}
                                </p>

                                {{-- Opsi jawaban --}}
                                @foreach (['A', 'B', 'C', 'D', 'E'] as $option)
                                    @php 
                                        $optionField = 'option_' . strtolower($option);
                                    @endphp
                                    @if (!empty($question->$optionField))
                                        <div class="form-check mb-2">
                                            <input type="radio"
                                                name="answers[{{ $question->id }}]"
                                                class="form-check-input answer-radio"
                                                id="q{{ $question->id }}_{{ $option }}"
                                                data-question="{{ $question->id }}"
                                                value="{{ $option }}">
                                            <label for="q{{ $question->id }}_{{ $option }}" 
                                                class="form-check-label text-dark" 
                                                style="color: #000;">
                                                {{ $option }}. {{ $question->$optionField }}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            

                                
                                {{-- <p class="mb-3">{{ $question->question_text }}</p>

                                @if($question->image)
                                    <div class="mb-3 text-center">
                                        <img src="{{ asset('upload/question/'.$question->image) }}" 
                                            alt="Gambar Soal" 
                                            class="img-fluid rounded" 
                                            style="max-width: 400px;">
                                    </div>
                                @endif

                                @foreach (['A', 'B', 'C', 'D', 'E'] as $option)
                                    @php 
                                        $optionField = 'option_' . strtolower($option);
                                    @endphp
                                    @if(!empty($question->$optionField))
                                        <div class="form-check mb-2">
                                            <input type="radio"
                                                name="answers[{{ $question->id }}]"
                                                class="form-check-input answer-radio"
                                                id="q{{ $question->id }}_{{ $option }}"
                                                data-question="{{ $question->id }}"
                                                value="{{ $option }}">
                                            <label for="q{{ $question->id }}_{{ $option }}" class="form-check-label">
                                                {{ $option }}. {{ $question->$optionField }}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach --}}

                                <div class="d-flex justify-content-between mt-4">
                                    @if ($index > 0)
                                        <button type="button" class="btn btn-outline-secondary prev-btn">← Sebelumnya</button>
                                    @else
                                        <div></div>
                                    @endif

                                    @if ($index < count($tryout->questions) - 1)
                                        <button type="button" class="btn btn-primary next-btn">Selanjutnya →</button>
                                    @else
                                        {{-- kirim ID tryout agar controller tahu tryout mana yang disubmit --}}
                                        <input type="hidden" name="tryout_package_id" value="{{ $tryout->id }}">

                                        <button type="submit" class="btn btn-success">
                                            <i class="la la-check-circle me-1"></i> Selesai & Kirim Jawaban
                                        </button>
                                    @endif

                                </div>
                            </div>
                            
                        </div>

                    </div>
                @endforeach
            </form>
        </div>
    </div>
</div>

<!-- ============================
         SCRIPT INTERAKTIF
============================= -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const totalQuestions = {{ count($tryout->questions) }};
    let currentIndex = 0;

    const questionCards = document.querySelectorAll('.question-card');
    const questionButtons = document.querySelectorAll('.question-btn');

    // 🎨 Fungsi ubah warna tombol
    function setButtonColor(btn, colorClass) {
        btn.classList.remove('btn-outline-danger', 'btn-success', 'btn-primary', 'btn-secondary', 'text-white');
        btn.classList.add(colorClass);
    }

    // 🔵 Soal aktif, abu default
    function highlightActiveButton() {
        questionButtons.forEach((btn, idx) => {
            if (idx === currentIndex) {
                setButtonColor(btn, 'btn-primary');
            } else if (!btn.classList.contains('btn-success')) {
                setButtonColor(btn, 'btn-secondary');
            }
        });
    }
    highlightActiveButton();

    // Navigasi tombol
    document.querySelectorAll('.next-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            questionCards[currentIndex].style.display = 'none';
            currentIndex++;
            questionCards[currentIndex].style.display = 'block';
            highlightActiveButton();
            window.scrollTo(0, 0);
        });
    });

    document.querySelectorAll('.prev-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            questionCards[currentIndex].style.display = 'none';
            currentIndex--;
            questionCards[currentIndex].style.display = 'block';
            highlightActiveButton();
            window.scrollTo(0, 0);
        });
    });

    // 🟢 Jika dijawab → ubah warna tombol jadi hijau
    document.querySelectorAll('.answer-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            const qid = this.dataset.question;
            const btn = document.getElementById('btn-' + qid);
            if (btn) setButtonColor(btn, 'btn-success');
        });
    });

    // Klik tombol nomor soal
    questionButtons.forEach((btn, idx) => {
        btn.addEventListener('click', function() {
            questionCards[currentIndex].style.display = 'none';
            currentIndex = idx;
            questionCards[currentIndex].style.display = 'block';
            highlightActiveButton();
            window.scrollTo(0, 0);
        });
    });

    // ⏰ Timer (format jam:menit:detik)
    let duration = {{ $tryout->duration * 60 }};
    const timerDisplay = document.getElementById('timer');

    const timerInterval = setInterval(() => {
        const hours = Math.floor(duration / 3600);
        const minutes = Math.floor((duration % 3600) / 60);
        const seconds = duration % 60;

        timerDisplay.textContent =
            `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

        duration--;

        if (duration < 0) {
            clearInterval(timerInterval);
            alert('Waktu habis! Jawaban akan dikirim otomatis.');
            document.getElementById('tryoutForm').submit();
        }
    }, 1000);
});
</script>

@endsection








