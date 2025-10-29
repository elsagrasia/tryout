@extends('frontend.mytryout.header_only')
@section('content')

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
                        data-question="{{ $question->id }}" {{-- Tambahkan ini --}}
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
                        data-question="{{ $question->id }}" {{-- Tambahkan ini --}}
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
                                    <p class="mb-3 text-dark">
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
                                <p class="mb-4 text-dark" style="font-weight:700; font-size: 1.05rem;">
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
                                                class="form-check-label text-dark">
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
<button type="button" id="confirmButton" class="btn btn-primary">
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
  const questionCards = document.querySelectorAll('.question-card');
  if (questionCards.length === 0) return;

  const totalQuestions = questionCards.length;
  let currentIndex = parseInt(localStorage.getItem('tryout_index') || '0', 10);
  if (isNaN(currentIndex) || currentIndex < 0 || currentIndex >= totalQuestions) currentIndex = 0;

  let answers = {};
  try { answers = JSON.parse(localStorage.getItem('tryout_answers') || '{}'); } catch {}

  const questionButtons = document.querySelectorAll('.question-btn');
  const timerDisplay = document.getElementById('timer');
  const form = document.getElementById('tryoutForm');

  function setButtonColor(btn, color) {
    btn.classList.remove('btn-outline-danger','btn-success','btn-primary','btn-secondary','btn-outline-secondary');
    btn.classList.add(color);
  }
  function highlightActiveButton() {
    questionButtons.forEach((btn, idx) => {
      const qid = btn.dataset.question;
      if (idx === currentIndex) setButtonColor(btn, 'btn-primary');
      else if (answers[qid]) setButtonColor(btn, 'btn-success');
      else setButtonColor(btn, 'btn-secondary');
    });
  }

  function showQuestion(index) {
    index = Math.max(0, Math.min(index, totalQuestions - 1));
    currentIndex = index;
    questionCards.forEach((card, i) => card.style.display = (i === index) ? 'block' : 'none');
    const qid = questionCards[index].dataset.question;
    if (answers[qid]) {
      const selected = document.querySelector(`input[name="answers[${qid}]"][value="${answers[qid]}"]`);
      if (selected) selected.checked = true;
    }
    localStorage.setItem('tryout_index', index);
    highlightActiveButton();
    window.scrollTo(0, 0);
  }

  showQuestion(currentIndex);
  document.querySelectorAll('.next-btn').forEach(btn => btn.addEventListener('click', () => showQuestion(currentIndex + 1)));
  document.querySelectorAll('.prev-btn').forEach(btn => btn.addEventListener('click', () => showQuestion(currentIndex - 1)));
  questionButtons.forEach(btn => btn.addEventListener('click', () => showQuestion(parseInt(btn.dataset.index))));

  document.querySelectorAll('.answer-radio').forEach(radio => {
    radio.addEventListener('change', function () {
      const qid = this.dataset.question;
      answers[qid] = this.value;
      localStorage.setItem('tryout_answers', JSON.stringify(answers));
      const btn = document.querySelector(`.question-btn[data-question="${qid}"]`);
      if (btn) setButtonColor(btn, 'btn-success');
    });
  });

  let duration = {{ $tryout->duration * 60 }};
  let elapsed = 0;
  if (localStorage.getItem('tryout_remaining_time')) duration = parseInt(localStorage.getItem('tryout_remaining_time'));
  if (localStorage.getItem('tryout_elapsed_time')) elapsed = parseInt(localStorage.getItem('tryout_elapsed_time'));

  if (timerDisplay) {
    setInterval(() => {
      const h = Math.floor(duration / 3600);
      const m = Math.floor((duration % 3600) / 60);
      const s = duration % 60;
      timerDisplay.textContent = `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
      duration--; elapsed++;
      if (duration % 5 === 0) {
        localStorage.setItem('tryout_remaining_time', duration);
        localStorage.setItem('tryout_elapsed_time', elapsed);
      }
      if (duration < 0) form.submit();
    }, 1000);
  }

  document.getElementById('confirmButton').addEventListener('click', function () {
    localStorage.setItem('tryout_answers', JSON.stringify(answers));
    localStorage.setItem('tryout_index', currentIndex);
    localStorage.setItem('tryout_remaining_time', duration);
    localStorage.setItem('tryout_elapsed_time', elapsed);
    window.location.href = "{{ route('tryout.confirm', $tryout->id) }}";
  });
});
</script>



@endsection
