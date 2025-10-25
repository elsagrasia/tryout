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
                <input type="hidden" name="tryout_package_id" value="{{ $tryout->id }}">
                <input type="hidden" name="elapsed_time" id="elapsedTime" value="0">
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
                                @if (!empty($question->category))
                                    <p class="mb-3 text-muted">
                                        {{ $question->category->category_name }}
                                    </p>
                                @endif

                                {{-- Tampilkan vignette jika ada --}}
                                @if (!empty($question->vignette))
                                    <p class="mb-3 text-dark" style="color: #000;">
                                        {!! $question->vignette !!}
                                    </p>
                                @endif

                                {{-- Tampilkan gambar soal kalau ada --}}
                               
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
                                <input type="hidden" name="answers[{{ $question->id }}]" value="">
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
                            

                             

                                <div class="d-flex justify-content-between mt-4">
                                    @if ($index > 0)
                                    
                                        <button type="button" class="btn btn-outline-secondary prev-btn">← Sebelumnya</button>
                                    @else
                                        <div></div>
                                    @endif

                                    @if ($index < count($tryout->questions) - 1)
                                        <button type="button" class="btn btn-primary next-btn">Selanjutnya →</button>
                                    @else
                                      

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

    // =========================
    // Inisialisasi variabel awal
    // =========================
    let duration = {{ $tryout->duration * 60 }}; // total waktu (detik)
    let elapsed = 0; // waktu yang sudah digunakan (detik)

    const timerDisplay = document.getElementById('timer');
    const form = document.getElementById('tryoutForm');

    // =========================
    // Jalankan timer
    // =========================
    const timerInterval = setInterval(() => {
        const hours = Math.floor(duration / 3600);
        const minutes = Math.floor((duration % 3600) / 60);
        const seconds = duration % 60;

        // tampilkan waktu tersisa
        timerDisplay.textContent =
            `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

        // hitung waktu digunakan
        elapsed++;

        duration--;

        if (duration < 0) {
            clearInterval(timerInterval);
            alert('Waktu habis! Jawaban akan dikirim otomatis.');
            form.submit();
        }
    }, 1000);

    // =========================
    // Simpan waktu digunakan ke input hidden saat submit
    // =========================
    form.addEventListener('submit', () => {
        document.getElementById('elapsedTime').value = elapsed;
    });
});

</script>

@endsection








