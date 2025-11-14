@extends('frontend.mytryout.header_only')
@section('content')

<div class="container my-4">
  <div class="card border-0 shadow-lg rounded-4">
      <div class="card-body text-center pb-5"> {{-- Tambah padding-bottom --}}

          {{-- HEADER --}}
          <div class="mb-4">
              <i class="la la-check-circle text-primary display-3 mb-3"></i>
              <h3 class="fw-bold text-dark">Konfirmasi Pengiriman Tryout</h3>
              <p class="text-muted fs-6">
                  Pastikan semua jawabanmu sudah benar sebelum dikirim. Kamu bisa kembali ke halaman tryout jika ingin memeriksa ulang.
              </p>
          </div>

          {{-- RINGKASAN --}}
          <div class="pt-60px pb-60px mb-4 rounded-4" style="background-color: #e8f3ff">
            <div class="row">

              <div class="col-md-3 text-center mb-3 mb-md-0">
                <p class="text-muted mb-1">Total Soal</p>
                <h2 class="text-dark" id="total-questions">0</h2>
              </div>

              <div class="col-md-3 text-center mb-3 mb-md-0">
                <p class="text-muted mb-1">Soal Terjawab</p>
                <h2 class="text-dark" id="answered-count">0</h2>
              </div>

              <div class="col-md-3 text-center mb-3 mb-md-0">
                <p class="text-muted mb-1">Belum Dijawab</p>
                <h2 class="text-dark" id="unanswered-count">0</h2>
              </div>

              <div class="col-md-3 text-center">
                <p class="text-muted mb-1">Waktu Terpakai</p>
                <h2 class="text-dark" id="elapsed-time">00:00:00</h2>
              </div>

            </div>
          </div>

          {{-- FORM --}}
          <form id="confirmForm" method="POST" action="{{ route('tryout.submit', $tryout->id) }}">
              @csrf
              <input type="hidden" name="tryout_package_id" value="{{ $tryout->id }}">
              <input type="hidden" name="elapsed_time" id="elapsedTimeInput">
              <div id="answers-container"></div>

              {{-- TOMBOL (ditengah dan ada jarak atas) --}}
              <div class="d-flex flex-column flex-md-row justify-content-center gap-3 mt-4">

                  <a href="{{ route('tryout.start', $tryout->id) }}" 
                     class="btn btn-outline-secondary px-4 py-2 mr-2">
                      <i class="la la-arrow-left me-1"></i> Kembali ke Tryout
                  </a>

                  <button type="submit" class="btn btn-primary px-4 py-2">
                      <i class="la la-check-circle me-1"></i> Kirim Jawaban Sekarang
                  </button>

              </div>

          </form>

      </div>
  </div>
</div>

{{-- =========================
    SCRIPT KONFIRMASI
========================= --}}
{{-- <script>
document.addEventListener('DOMContentLoaded', function () {
    const tryoutId = {{ $tryout->id }};
    const totalQuestions = {{ count($tryout->questions) }};

    // === Ambil progress dari localStorage ===
    const savedAnswers = localStorage.getItem(`tryout_${tryoutId}_answers`);
    const answers = savedAnswers ? JSON.parse(savedAnswers) : {};

    const elapsed = savedElapsed ? parseInt(savedElapsed) : 0;
    const savedDoubts = localStorage.getItem('tryout_doubts');
    const doubts = savedDoubts ? JSON.parse(savedDoubts) : {};


    // Hitung statistik
    const answeredCount = Object.keys(answers).length;
    const unansweredCount = totalQuestions - answeredCount;

    const hours = Math.floor(elapsed / 3600);
    const minutes = Math.floor((elapsed % 3600) / 60);
    const seconds = elapsed % 60;
    const formattedTime =
        `${hours.toString().padStart(2,'0')}:${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}`;

    document.getElementById('total-questions').textContent = totalQuestions;
    document.getElementById('answered-count').textContent  = answeredCount;
    document.getElementById('unanswered-count').textContent = unansweredCount;
    document.getElementById('elapsed-time').textContent     = formattedTime;


    const form = document.getElementById('confirmForm');
    form.addEventListener('submit', function() {
        // Set elapsed time
        document.getElementById('elapsedTimeInput').value = elapsed;


        // kirim answers
        const inputAnswers = document.createElement('input');
        inputAnswers.type = 'hidden';
        inputAnswers.name = 'answers';
        inputAnswers.value = JSON.stringify(answers);
        form.appendChild(inputAnswers);

        // 🔹 kirim doubts juga
        const inputDoubts = document.createElement('input');
        inputDoubts.type = 'hidden';
        inputDoubts.name = 'doubts';
        inputDoubts.value = JSON.stringify(doubts);
        form.appendChild(inputDoubts);

        // Bersihkan storage
        localStorage.removeItem('tryout_answers');
        localStorage.removeItem('tryout_index');
        localStorage.removeItem('tryout_remaining_time');
        localStorage.removeItem('tryout_elapsed_time');
        localStorage.removeItem('tryout_doubts');

    });


});

</script> --}}

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tryoutId = {{ $tryout->id }};
    const totalQuestions = {{ count($tryout->questions) }};

    // === Ambil data dari localStorage seperti yang disimpan di halaman tryout ===
    const savedAnswers = localStorage.getItem('tryout_answers');
    const savedDoubts  = localStorage.getItem('tryout_doubts');
    const savedElapsed = localStorage.getItem('tryout_elapsed_time');

    const answers = savedAnswers ? JSON.parse(savedAnswers) : {};
    const doubts  = savedDoubts ? JSON.parse(savedDoubts) : {};
    const elapsed = savedElapsed ? parseInt(savedElapsed) : 0;

    // === Hitung statistik ===
    const answeredCount = Object.keys(answers).length;
    const unansweredCount = totalQuestions - answeredCount;

    const hours = Math.floor(elapsed / 3600);
    const minutes = Math.floor((elapsed % 3600) / 60);
    const seconds = elapsed % 60;

    const formattedTime =
        `${String(hours).padStart(2,'0')}:${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}`;

    // === Tampilkan ke halaman ===
    document.getElementById('total-questions').textContent = totalQuestions;
    document.getElementById('answered-count').textContent  = answeredCount;
    document.getElementById('unanswered-count').textContent = unansweredCount;
    document.getElementById('elapsed-time').textContent     = formattedTime;

    // === Saat submit kirim semua data ke server ===
    const form = document.getElementById('confirmForm');
    form.addEventListener('submit', function() {

        // elapsed time
        document.getElementById('elapsedTimeInput').value = elapsed;

        // kirim answers
        const inputAnswers = document.createElement('input');
        inputAnswers.type = 'hidden';
        inputAnswers.name = 'answers';
        inputAnswers.value = JSON.stringify(answers);
        form.appendChild(inputAnswers);

        // kirim doubts
        const inputDoubts = document.createElement('input');
        inputDoubts.type = 'hidden';
        inputDoubts.name = 'doubts';
        inputDoubts.value = JSON.stringify(doubts);
        form.appendChild(inputDoubts);

        // Hapus data setelah submit
        localStorage.removeItem('tryout_answers');
        localStorage.removeItem('tryout_doubts');
        localStorage.removeItem('tryout_index');
        localStorage.removeItem('tryout_remaining_time');
        localStorage.removeItem('tryout_elapsed_time');

    });
});
</script>


@endsection
