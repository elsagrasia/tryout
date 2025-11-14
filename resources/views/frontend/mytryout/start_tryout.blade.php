@extends('frontend.mytryout.header_only')
@section('content')

<div class="container-fluid mt-4 mb-5">
    <div class="row gx-4">

        <!-- CARD KIRI: DAFTAR SOAL -->
        <div class="col-md-3">
            <div class="card shadow-sm p-3" style="border-radius:12px;">
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    @foreach ($tryout->questions as $index => $question)
                        <div class="d-flex justify-content-center" style="width:50px; margin-bottom:8px;">
                            <button type="button"
                                    class="btn btn-secondary btn-sm question-btn"
                                    data-index="{{ $index }}"
                                    data-question="{{ $question->id }}"
                                    id="btn-{{ $question->id }}"
                                    style="width:45px; height:45px; border-radius:10px; border:1px solid #ccc;">
                                {{ $index + 1 }}
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- CARD KANAN: SOAL -->
        <div class="col-md-9">
            <div class="card shadow-sm p-4" style="border-radius: 12px;">
                <form id="tryoutForm" action="{{ route('tryout.submit', $tryout->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="tryout_package_id" value="{{ $tryout->id }}">
                    <input type="hidden" name="elapsed_time" id="elapsedTime" value="0">

                    <!-- HEADER -->
                    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap">
                        <div class="d-flex flex-column align-items-start">
                            <span class="badge bg-info text-white fs-5 px-3 py-2">
                                {{ $tryout->tryout_name }}
                            </span>
                            <small id="doubt-text" class="small mt-2 fw-semibold" style="display:none; color:#ff9800;">
                                ⚠️ Soal ini ditandai <strong>ragu-ragu</strong>.
                            </small>
                        </div>

                        <div id="timer-container" class="text-end">
                            <h6 class="fw-bold mb-1">Sisa Waktu:</h6>
                            <div id="timer" class="fw-bold text-danger fs-5"></div>
                        </div>
                    </div>

                    <!-- SOAL -->
                    @foreach ($tryout->questions as $index => $question)
                        <div class="question-card mb-4 shadow-sm"
                             data-question="{{ $question->id }}"
                             style="background:#f8f8f8; border-radius:12px; padding:25px; {{ $index == 0 ? '' : 'display:none;' }}">
                            <h6 class="fw-bold mb-3">Soal Nomor {{ $index + 1 }}</h6>

                            @if (!empty($question->category))
                                <p class="mb-3 text-muted">{{ $question->category->category_name }}</p>
                            @endif

                            @if (!empty($question->vignette))
                                <p class="mb-3 text-dark">{!! $question->vignette !!}</p>
                            @endif

                            @if (!empty($question->image))
                                <div class="text-center mb-3">
                                    <img src="{{ asset($question->image) }}" alt="Gambar Soal"
                                        class="img-fluid rounded shadow-sm"
                                        style="max-width: 100%; height: auto;">
                                </div>
                            @endif

                            <p class="mb-4 fw-bold text-dark" style="font-size: 1.05rem;">
                                {!! $question->question_text !!}
                            </p>

                            <!-- PILIHAN JAWABAN -->
                            <div class="ms-2">
                                <input type="hidden" name="answers[{{ $question->id }}]" value="">
                                @foreach (['A','B','C','D','E'] as $option)
                                    @php $optionField = 'option_' . strtolower($option); @endphp
                                    @if (!empty($question->$optionField))
                                        <div class="form-check mb-2">
                                            <input type="radio"
                                                name="answers[{{ $question->id }}]"
                                                class="form-check-input answer-radio me-2"
                                                id="q{{ $question->id }}_{{ $option }}"
                                                data-question="{{ $question->id }}"
                                                value="{{ $option }}">
                                            <label for="q{{ $question->id }}_{{ $option }}" class="form-check-label text-dark fw-normal">
                                                {{ $option }}. {{ $question->$optionField }}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach

                                <!-- TANDAI RAGU-RAGU -->
                                <div class="form-check mt-3">
                                    <input type="checkbox"
                                        class="form-check-input mark-doubt-checkbox me-2"
                                        id="doubt-{{ $question->id }}"
                                        data-question="{{ $question->id }}">
                                    <label for="doubt-{{ $question->id }}" class="form-check-label text-warning fw-semibold">
                                        Tandai Ragu-ragu
                                    </label>
                                </div>
                            </div>

                            <!-- NAVIGASI -->
                            <div class="d-flex justify-content-between mt-4">
                                @if ($index > 0)
                                    <button type="button" class="btn btn-outline-secondary prev-btn">← Sebelumnya</button>
                                @else
                                    <div></div>
                                @endif

                                @if ($index < count($tryout->questions) - 1)
                                    <button type="button" class="btn btn-primary next-btn">Selanjutnya →</button>
                                @else
                                    <button type="button" class="btn btn-primary"
                                            onclick="window.location='{{ route('tryout.confirm', $tryout->id) }}'">
                                        <i class="la la-check-circle me-1"></i> Selesai & Konfirmasi
                                    </button>

                                @endif
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>
        </div>

    </div>
</div>

<script>
// document.addEventListener('DOMContentLoaded', function () {

//     const totalDuration = {{ $tryout->duration * 60 }};

//     const questionCards    = Array.from(document.querySelectorAll('.question-card'));
//     const questionButtons  = Array.from(document.querySelectorAll('.question-btn'));
//     const timerDisplay     = document.getElementById('timer');
//     const form             = document.getElementById('tryoutForm');
//     const confirmButton    = document.getElementById('confirmButton');
//     const doubtTextEl      = document.getElementById('doubt-text');

//     if (questionCards.length === 0) return;

//     let answers = {};
//     let doubts  = {};
//     try {
//         answers = JSON.parse(localStorage.getItem('tryout_answers') || '{}');
//     } catch {}
//     try {
//         doubts = JSON.parse(localStorage.getItem('tryout_doubts') || '{}');
//     } catch {}

//     let currentIndex = parseInt(localStorage.getItem('tryout_index') || '0', 10);
//     if (isNaN(currentIndex) || currentIndex < 0 || currentIndex >= questionCards.length) {
//         currentIndex = 0;
//     }

//     let remainingTime;
//     let elapsed;

//     if (localStorage.getItem('tryout_remaining_time')) {
//         remainingTime = parseInt(localStorage.getItem('tryout_remaining_time'), 10);
//         if (isNaN(remainingTime) || remainingTime < 0) remainingTime = totalDuration;
//     } else {
//         remainingTime = totalDuration;
//     }

//     if (localStorage.getItem('tryout_elapsed_time')) {
//         elapsed = parseInt(localStorage.getItem('tryout_elapsed_time'), 10);
//         if (isNaN(elapsed) || elapsed < 0) elapsed = 0;
//     } else {
//         elapsed = 0;
//     }


//     function updateTimerDisplay() {
//         const h = Math.floor(remainingTime / 3600);
//         const m = Math.floor((remainingTime % 3600) / 60);
//         const s = remainingTime % 60;
//         timerDisplay.textContent = `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
//     }

//     const timerInterval = setInterval(() => {
//         remainingTime--;
//         elapsed++;

//         updateTimerDisplay();

//         // Simpan periodik ke localStorage biar bisa resume
//         if (remainingTime % 5 === 0) {
//             localStorage.setItem('tryout_remaining_time', remainingTime);
//             localStorage.setItem('tryout_elapsed_time', elapsed);
//             localStorage.setItem('tryout_answers', JSON.stringify(answers));
//             localStorage.setItem('tryout_doubts', JSON.stringify(doubts));
//             localStorage.setItem('tryout_index', currentIndex);
//         }

//         if (remainingTime < 0) {
//             clearInterval(timerInterval);
//             form.submit();
//         }
//     }, 1000);

//     // ====== WARNA TOMBOL ======

//     function highlightButtons() {
//         questionButtons.forEach((btn, idx) => {
//             const qid = btn.dataset.question;

//             let colorClass = 'btn-secondary';
//             if (answers[qid]) colorClass = 'btn-success';
//             if (doubts[qid])  colorClass = 'btn-warning';
//             if (idx === currentIndex) colorClass = 'btn-primary';

//             btn.classList.remove('btn-secondary','btn-success','btn-primary','btn-warning');
//             btn.classList.add(colorClass);
//         });
//     }


//     function updateGlobalStatusForActive() {
//         if (!doubtTextEl) return;
//         const qid = questionCards[currentIndex].dataset.question;
//         doubtTextEl.style.display = doubts[qid] ? 'block' : 'none';
//     }

//     // ====== TAMPILKAN SOAL SESUAI INDEX ======
//     function showQuestion(index) {
//         if (index < 0) index = 0;
//         if (index >= questionCards.length) index = questionCards.length - 1;

//         questionCards.forEach((card, i) => {
//             card.style.display = (i === index) ? 'block' : 'none';
//         });

//         // Set jawaban radio berdasarkan data 'answers'
//         const activeCard = questionCards[currentIndex];
//         const qid        = activeCard.dataset.question;

//         if (answers[qid]) {
//             const selected = activeCard.querySelector(
//                 `input.answer-radio[data-question="${qid}"][value="${answers[qid]}"]`
//             );
//             if (selected) selected.checked = true;
//         } else {
//             // kalau belum ada jawaban, clear centang
//             const radios = activeCard.querySelectorAll(`input.answer-radio[data-question="${qid}"]`);
//             radios.forEach(r => r.checked = false);
//         }

//         // Set checkbox ragu dari data 'doubts'
//         const doubtCheckbox = activeCard.querySelector(`.mark-doubt-checkbox[data-question="${qid}"]`);
//         if (doubtCheckbox) {
//             doubtCheckbox.checked = !!doubts[qid];
//         }

//         updateGlobalStatusForActive();
//         highlightButtons();

//         // Simpan index aktif
//         localStorage.setItem('tryout_index', currentIndex);

//         window.scrollTo({
//             top: questionCards[currentIndex].offsetTop - 20,
//             behavior: 'smooth'
//         });
//     }


//     // Pertama kali load halaman → tampilkan soal terakhir yang dikerjakan
//     showQuestion(currentIndex);

//     // ====== EVENT PILIHAN JAWABAN ======

//     document.querySelectorAll('.answer-radio').forEach(radio => {
//         radio.addEventListener('change', function () {
//             const qid = this.dataset.question;
//             answers[qid] = this.value;
//             highlightButtons();
//             saveProgress();
//         });
//     });


//     document.querySelectorAll('.mark-doubt-checkbox').forEach(cb => {
//         cb.addEventListener('change', function () {
//             const qid = this.dataset.question;
//             doubts[qid] = this.checked;
//             updateGlobalStatusForActive();
//             highlightButtons();
//             saveProgress();
//         });
//     });


//     questionButtons.forEach(btn => {
//         btn.addEventListener('click', () => {
//             const idx = parseInt(btn.dataset.index, 10);
//             showQuestion(idx);
//         });
//     });


//     document.querySelectorAll('.next-btn').forEach(btn => {
//         btn.addEventListener('click', () => showQuestion(currentIndex + 1));
//     });

//     document.querySelectorAll('.prev-btn').forEach(btn => {
//         btn.addEventListener('click', () => showQuestion(currentIndex - 1));
//     });

//     // ====== KONFIRMASI ======
//     if (confirmButton) {
//         confirmButton.addEventListener('click', function () {
//             // Simpan terakhir kali sebelum pindah ke halaman konfirmasi
//             localStorage.setItem('tryout_answers', JSON.stringify(answers));
//             localStorage.setItem('tryout_doubts', JSON.stringify(doubts));
//             localStorage.setItem('tryout_index', currentIndex);
//             localStorage.setItem('tryout_remaining_time', remainingTime);
//             localStorage.setItem('tryout_elapsed_time', elapsed);

//             window.location.href = "{{ route('tryout.confirm', $tryout->id) }}";
//         });
//     }

// });

document.addEventListener('DOMContentLoaded', function () {
    // ======= KONFIGURASI =======
    const totalDuration   = {{ $tryout->duration * 60 }};
    const questionCards    = Array.from(document.querySelectorAll('.question-card'));
    const questionButtons  = Array.from(document.querySelectorAll('.question-btn'));
    const timerDisplay     = document.getElementById('timer');
    const form             = document.getElementById('tryoutForm');
    const confirmButton    = document.getElementById('confirmButton');
    const doubtTextEl      = document.getElementById('doubt-text');

    if (questionCards.length === 0) return;

    // ======= INISIALISASI DATA =======
    let answers = {};
    let doubts  = {};
    let currentIndex = 0;
    let remainingTime = totalDuration;
    let elapsed = 0;

    // ======= LOAD DARI LOCALSTORAGE (RESUME JIKA ADA) =======
    try { answers = JSON.parse(localStorage.getItem('tryout_answers') || '{}'); } catch {}
    try { doubts  = JSON.parse(localStorage.getItem('tryout_doubts') || '{}'); } catch {}
    currentIndex = parseInt(localStorage.getItem('tryout_index') || '0', 10);
    if (isNaN(currentIndex) || currentIndex < 0 || currentIndex >= questionCards.length) currentIndex = 0;
    remainingTime = parseInt(localStorage.getItem('tryout_remaining_time') || totalDuration, 10);
    if (isNaN(remainingTime) || remainingTime < 0) remainingTime = totalDuration;
    elapsed = parseInt(localStorage.getItem('tryout_elapsed_time') || 0, 10);
    if (isNaN(elapsed) || elapsed < 0) elapsed = 0;

    // ======= TIMER =======
    function updateTimerDisplay() {
        const h = Math.floor(remainingTime / 3600);
        const m = Math.floor((remainingTime % 3600) / 60);
        const s = remainingTime % 60;
        timerDisplay.textContent = `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
    }
    updateTimerDisplay();

    const timerInterval = setInterval(() => {
        remainingTime--;
        elapsed++;
        updateTimerDisplay();

        // Simpan periodik agar bisa resume
        if (remainingTime % 5 === 0) saveProgress();

        if (remainingTime < 0) {
            clearInterval(timerInterval);
            form.submit();
        }
    }, 1000);

    function saveProgress() {
        localStorage.setItem('tryout_remaining_time', remainingTime);
        localStorage.setItem('tryout_elapsed_time', elapsed);
        localStorage.setItem('tryout_answers', JSON.stringify(answers));
        localStorage.setItem('tryout_doubts', JSON.stringify(doubts));
        localStorage.setItem('tryout_index', currentIndex);
    }

    // ======= WARNA TOMBOL =======
    function highlightButtons() {
        questionButtons.forEach((btn, idx) => {
            const qid = btn.dataset.question;
            let colorClass = 'btn-secondary';
            if (answers[qid]) colorClass = 'btn-success';
            if (doubts[qid])  colorClass = 'btn-warning';
            if (idx === currentIndex) colorClass = 'btn-primary';
            btn.classList.remove('btn-secondary','btn-success','btn-primary','btn-warning');
            btn.classList.add(colorClass);
        });
    }

    // ======= STATUS RAGU =======
    function updateGlobalStatusForActive() {
        if (!doubtTextEl) return;
        const qid = questionCards[currentIndex].dataset.question;
        doubtTextEl.style.display = doubts[qid] ? 'block' : 'none';
    }

    // ======= TAMPILKAN SOAL =======
    function showQuestion(index) {
        if (index < 0) index = 0;
        if (index >= questionCards.length) index = questionCards.length - 1;
        currentIndex = index;

        questionCards.forEach((card, i) => card.style.display = (i === index) ? 'block' : 'none');

        const activeCard = questionCards[currentIndex];
        const qid = activeCard.dataset.question;

        // Atur jawaban radio
        const selectedRadio = activeCard.querySelector(`input.answer-radio[data-question="${qid}"][value="${answers[qid]}"]`);
        if (selectedRadio) selectedRadio.checked = true;
        else activeCard.querySelectorAll(`input.answer-radio[data-question="${qid}"]`).forEach(r => r.checked = false);

        // Atur checkbox ragu
        const doubtCheckbox = activeCard.querySelector(`.mark-doubt-checkbox[data-question="${qid}"]`);
        if (doubtCheckbox) doubtCheckbox.checked = !!doubts[qid];

        updateGlobalStatusForActive();
        highlightButtons();
        saveProgress();

        window.scrollTo({ top: questionCards[currentIndex].offsetTop - 20, behavior: 'smooth' });
    }
    showQuestion(currentIndex);

    // ======= EVENT PILIHAN JAWABAN =======
    document.querySelectorAll('.answer-radio').forEach(radio => {
        radio.addEventListener('change', function () {
            answers[this.dataset.question] = this.value;
            highlightButtons();
            saveProgress();
        });
    });

    // ======= EVENT RAGU =======
    document.querySelectorAll('.mark-doubt-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {
            doubts[this.dataset.question] = this.checked;
            updateGlobalStatusForActive();
            highlightButtons();
            saveProgress();
        });
    });

    // ======= NAVIGASI TOMBOL SOAL =======
    questionButtons.forEach(btn => btn.addEventListener('click', () => showQuestion(parseInt(btn.dataset.index, 10))));
    document.querySelectorAll('.next-btn').forEach(btn => btn.addEventListener('click', () => showQuestion(currentIndex + 1)));
    document.querySelectorAll('.prev-btn').forEach(btn => btn.addEventListener('click', () => showQuestion(currentIndex - 1)));

    // ======= KONFIRMASI =======
    if (confirmButton) {
        confirmButton.addEventListener('click', function () {
            saveProgress();
            window.location.href = "{{ route('tryout.confirm', $tryout->id) }}";
        });
    }
});

</script>


@endsection
