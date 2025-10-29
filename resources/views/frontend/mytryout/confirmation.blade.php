@extends('frontend.mytryout.header_only')

@section('content')



<div class="container">
    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body text-center">
                        <div class="mb-4">
                <i class="la la-check-circle text-primary display-3 mb-3"></i>
                <h3 class="fw-bold text-dark">Konfirmasi Pengiriman Tryout</h3>
                <p class="text-muted mb-4 fs-6">
                    Pastikan semua jawabanmu sudah benar sebelum dikirim. Kamu bisa kembali ke halaman tryout jika ingin memeriksa ulang.
                </p>
            </div>

         
<div class="pt-60px pb-60px  mb-4" style="background-color: #e8f3ff">
  <div class="row">
    <div class="col-md-3 text-center">
      <p>Total Soal</p>
      <h2 class="text-dark" id="total-questions">0</h2>
    </div>
    <div class="col-md-3 text-center">
      <p>Soal Terjawab</p>
      <h2 class="text-dark" id="answered-count">0</h2>
    </div>
    <div class="col-md-3 text-center">
      <p>Belum Dijawab</p>
      <h2 class="text-dark" id="unanswered-count">0</h2>
    </div>
    <div class="col-md-3 text-center">
      <p>Waktu Terpakai</p>
      <h2 class="text-dark" id="elapsed-time">00:00:00</h2>
    </div>
  </div>
</div>
           
        
    

            <form id="confirmForm" method="POST" action="{{ route('tryout.submit', $tryout->id) }}">
                @csrf
                <input type="hidden" name="tryout_package_id" value="{{ $tryout->id }}">
                <input type="hidden" name="elapsed_time" id="elapsedTimeInput">
                <div id="answers-container"></div>

                <a href="{{ route('tryout.start', $tryout->id) }}" class="btn btn-outline-secondary">
                    <i class="la la-arrow-left me-1"></i> Kembali ke Tryout
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="la la-check-circle"></i> Kirim Jawaban Sekarang
                </button>

               
               
            </form>
        </div>
    </div>
</div>

{{-- =========================
    SCRIPT KONFIRMASI
========================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const totalQuestions = {{ count($tryout->questions) }};
    const savedAnswers = localStorage.getItem('tryout_answers');
    const savedElapsed = localStorage.getItem('tryout_elapsed_time');
    const answers = savedAnswers ? JSON.parse(savedAnswers) : {};
    const elapsed = savedElapsed ? parseInt(savedElapsed) : 0;

    // Hitung dan tampilkan data
    const answeredCount = Object.keys(answers).length;
    const unansweredCount = totalQuestions - answeredCount;

    const hours = Math.floor(elapsed / 3600);
    const minutes = Math.floor((elapsed % 3600) / 60);
    const seconds = elapsed % 60;
    const formattedTime = 
        `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

    document.getElementById('total-questions').textContent = totalQuestions;
    document.getElementById('answered-count').textContent = answeredCount;
    document.getElementById('unanswered-count').textContent = unansweredCount;
    document.getElementById('elapsed-time').textContent = formattedTime;

    // Saat kirim
    const form = document.getElementById('confirmForm');
    form.addEventListener('submit', function() {
        document.getElementById('elapsedTimeInput').value = elapsed;
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'answers';
        input.value = JSON.stringify(answers);
        form.appendChild(input);

        // Bersihkan storage
        localStorage.removeItem('tryout_answers');
        localStorage.removeItem('tryout_index');
        localStorage.removeItem('tryout_remaining_time');
        localStorage.removeItem('tryout_elapsed_time');
    });
});
</script>
@endsection
