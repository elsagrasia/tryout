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

// WAKTU PENGERJAAN 
document.addEventListener('DOMContentLoaded', function () {

    if (window.location.search.includes('reset=1')) {
        history.replaceState({}, '', window.location.pathname);
    }

    // RESET HANYA JIKA URL ADA ?reset=1
    const urlParams = new URLSearchParams(window.location.search);
    const shouldReset = urlParams.get('reset') === '1';

    if (shouldReset) {
        localStorage.removeItem('tryout_remaining_time');
        localStorage.removeItem('tryout_elapsed_time');
        localStorage.removeItem('tryout_answers');
        localStorage.removeItem('tryout_doubts');
        localStorage.removeItem('tryout_index');
    }

    // ======= KONFIGURASI =======
    const totalDuration = {{ $tryout->duration * 60 }};
    const questionCards = Array.from(document.querySelectorAll('.question-card'));
    const questionButtons = Array.from(document.querySelectorAll('.question-btn'));
    const timerDisplay = document.getElementById('timer');
    const form = document.getElementById('tryoutForm');
    const confirmButton = document.getElementById('confirmButton');
    const doubtTextEl = document.getElementById('doubt-text');

    if (questionCards.length === 0) return;

    // ======= INISIALISASI DATA (BENAR) =======
    let answers = {};
    let doubts = {};
    let currentIndex = 0;
    let remainingTime = totalDuration;
    let elapsed = 0;

    // ======= LOAD LOCALSTORAGE =======
    const savedAnswers = JSON.parse(localStorage.getItem('tryout_answers'));
    const savedDoubts = JSON.parse(localStorage.getItem('tryout_doubts'));
    const savedIndex = localStorage.getItem('tryout_index');
    const savedRemaining = localStorage.getItem('tryout_remaining_time');
    const savedElapsed = localStorage.getItem('tryout_elapsed_time');

    if (savedAnswers) answers = savedAnswers;
    if (savedDoubts) doubts = savedDoubts;
    if (savedIndex !== null) currentIndex = parseInt(savedIndex, 10);
    if (savedRemaining !== null) remainingTime = parseInt(savedRemaining, 10);
    if (savedElapsed !== null) elapsed = parseInt(savedElapsed, 10);

    // ======= LOAD BACKEND (OPSIONAL, TAPI TIDAK MENIMPA LOCALSTORAGE) =======
    const TRYOUT_ID = {{ $tryout->id }};

    fetch(`/tryout/${TRYOUT_ID}/get-progress`)
        .then(res => res.json())
        .then(data => {

            if (!savedAnswers && data.answers) answers = data.answers;

            if (savedElapsed === null && data.elapsed_seconds != null) {
                elapsed = data.elapsed_seconds;
                remainingTime = totalDuration - elapsed;
                if (remainingTime < 0) remainingTime = 0;
            }

            showQuestion(currentIndex);
            highlightButtons();
            saveProgress();
        })
        .catch(() => {
            showQuestion(currentIndex);
            highlightButtons();
        });

    // ======= TIMER =======
    function updateTimerDisplay() {
        const h = Math.floor(remainingTime / 3600);
        const m = Math.floor((remainingTime % 3600) / 60);
        const s = remainingTime % 60;
        timerDisplay.textContent =
            `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
    }
    updateTimerDisplay();

    const timerInterval = setInterval(() => {
        remainingTime--;
        elapsed++;

        if (remainingTime <= 0) {
            remainingTime = 0;
            updateTimerDisplay();
            saveProgress();
            clearInterval(timerInterval);
            form.submit();
            return;
        }

        updateTimerDisplay();
        if (remainingTime % 5 === 0) saveProgress();
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
            if (doubts[qid]) colorClass = 'btn-warning';
            if (idx === currentIndex) colorClass = 'btn-primary';
            btn.classList.remove('btn-secondary', 'btn-success', 'btn-primary', 'btn-warning');
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

        questionCards.forEach((card, i) =>
            card.style.display = (i === index) ? 'block' : 'none'
        );

        const activeCard = questionCards[currentIndex];
        const qid = activeCard.dataset.question;

        const selectedRadio = activeCard.querySelector(
            `input.answer-radio[data-question="${qid}"][value="${answers[qid]}"]`
        );
        if (selectedRadio) selectedRadio.checked = true;
        else activeCard.querySelectorAll(`input.answer-radio[data-question="${qid}"]`)
            .forEach(r => r.checked = false);

        const doubtCheckbox = activeCard.querySelector(`.mark-doubt-checkbox[data-question="${qid}"]`);
        if (doubtCheckbox) doubtCheckbox.checked = !!doubts[qid];

        updateGlobalStatusForActive();
        highlightButtons();
        saveProgress();

        window.scrollTo({ top: activeCard.offsetTop - 20, behavior: 'smooth' });
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

    // ======= NAVIGASI =======
    questionButtons.forEach(btn =>
        btn.addEventListener('click', () =>
            showQuestion(parseInt(btn.dataset.index, 10))
        )
    );

    document.querySelectorAll('.next-btn')
        .forEach(btn => btn.addEventListener('click', () => showQuestion(currentIndex + 1)));

    document.querySelectorAll('.prev-btn')
        .forEach(btn => btn.addEventListener('click', () => showQuestion(currentIndex - 1)));

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
