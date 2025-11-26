@extends('frontend.mytryout.header_only')
@section('content')

<div class="container my-4">
    <div class="card border-0 shadow-lg ">
        <div class="card-body text-center pb-5">

            {{-- HEADER --}}
            <div class="mb-4">
                <i class="la la-check-circle text-primary display-3 mb-3"></i>
                <h3 class="fw-bold text-dark">Konfirmasi Pengiriman Tryout</h3>
                <p class="text-muted fs-6">
                    Pastikan semua jawabanmu sudah benar sebelum dikirim. Klik pada baris pertanyaan di bawah untuk memeriksa ulang.
                </p>
            </div>

            {{-- RINGKASAN STATISTIK --}}
<div class="pt-3 pb-3 mb-4 rounded" style="background-color: #e8f3ff">
    <div class="row justify-content-center text-center">

        <div class="col-6 col-md-2 mb-3">
            <p class="text-muted mb-1 small">Total Soal</p>
            <h4 class="text-dark" id="total-questions">0</h4>
        </div>

        <div class="col-6 col-md-2 mb-3">
            <p class="text-muted mb-1 small">Terjawab</p>
            <h4 class="text-dark" id="answered-count">0</h4>
        </div>

        <div class="col-6 col-md-2 mb-3">
            <p class="text-muted mb-1 small">Belum Dijawab</p>
            <h4 class="text-dark" id="unanswered-count">0</h4>
        </div>

        <div class="col-6 col-md-2 mb-3">
            <p class="text-muted mb-1 small">Ragu-ragu</p>
            <h4 class="text-dark" id="doubt-count">0</h4>
        </div>

        <div class="col-6 col-md-2 mb-3">
            <p class="text-muted mb-1 small">Waktu Terpakai</p>
            <h4 class="text-dark" id="elapsed-time">00:00:00</h4>
        </div>

    </div>
</div>


            {{-- DAFTAR PERTANYAAN DALAM TABEL --}}
            <div class="table-responsive mb-4 text-start">         
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            {{-- Kolom "No" dihapus, hanya pertanyaan & status --}}
                            <th scope="col">Pertanyaan</th>
                            <th scope="col" style="width: 25%;">Status</th>
                        </tr>
                    </thead>
                    <tbody id="questions-table-body">
                        {{-- Isi tabel akan diisi oleh JavaScript --}}
                    </tbody>
                </table>
            </div>

            {{-- FORM --}}
            <form id="confirmForm" method="POST" action="{{ route('tryout.submit', $tryout->id) }}">
                @csrf
                <input type="hidden" name="tryout_package_id" value="{{ $tryout->id }}">
                <input type="hidden" name="elapsed_time" id="elapsedTimeInput">
                <div id="answers-container"></div> {{-- Tempat input hidden answers & doubts --}}

                {{-- TOMBOL --}}
                <div class="d-flex flex-column flex-md-row justify-content-center gap-3 mt-4">

                    <a href="{{ route('tryout.start', $tryout->id) }}"
                       class="btn btn-outline-secondary  py-2 mr-3">
                        <i class="la la-arrow-left "></i> Kembali ke Tryout
                    </a>

                    <button type="submit" class="btn btn-primary py-2">
                        <i class="la la-check-circle "></i> Kirim Jawaban Sekarang
                    </button>

                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tryoutId = {{ $tryout->id }};
    const questions = @json($tryout->questions);
    const totalQuestions = questions.length;

    // === Ambil data dari localStorage ===
    const savedAnswers = localStorage.getItem('tryout_answers');
    const savedDoubts  = localStorage.getItem('tryout_doubts');
    const savedElapsed = localStorage.getItem('tryout_elapsed_time');

    const answers = savedAnswers ? JSON.parse(savedAnswers) : {};
    const doubts  = savedDoubts ? JSON.parse(savedDoubts) : {};
    const elapsed = savedElapsed ? parseInt(savedElapsed) : 0;

    // --- Statistik ---
    let answeredCount = 0;
    let unansweredCount = 0;
    let doubtCount = 0;

    const tableBody = document.getElementById('questions-table-body');
    const tryoutStartUrl = @json(route('tryout.start', $tryout->id));

    questions.forEach((question, index) => {
        const questionId = question.id;
        const questionNumber = index + 1;

        let statusText = '';
        let statusClass = 'text-muted';

        const hasAnswer = Object.prototype.hasOwnProperty.call(answers, questionId);
        const isDoubt   = Object.prototype.hasOwnProperty.call(doubts, questionId) && doubts[questionId] === true;

        // ✅ RAGU-RAGU: terhitung TERJAWAB & juga RAGU-RAGU
        if (hasAnswer) {
            answeredCount++; // semua yang punya jawaban

            if (isDoubt) {
                statusText = 'Ragu-ragu';
                statusClass = 'text-warning fw-bold';
                doubtCount++;
            } else {
                statusText = 'Terjawab';
                statusClass = 'text-success fw-bold';
            }
        } else {
            statusText = 'Belum Dijawab';
            statusClass = 'text-danger fw-bold';
            unansweredCount++;
        }

        const row = tableBody.insertRow();
        row.style.cursor = 'pointer';

        const questionLink = `${tryoutStartUrl}?q=${questionNumber}`;

        // Pastikan lompat ke soal yang benar:
        // 1) set index di localStorage (kalau script start_tryout pakai ini)
        // 2) tambah query ?q= ke URL (dipakai di controller StartTryout)
        row.onclick = () => {
            localStorage.setItem('tryout_index', index); // index 0-based
            window.location.href = questionLink;
        };

        row.innerHTML = `
            <td><a href="">Soal No. ${questionNumber}</a></td>
            <td class="${statusClass}">${statusText}</td>
        `;
    });

    // --- Hitung dan Tampilkan Waktu ---
    const hours = Math.floor(elapsed / 3600);
    const minutes = Math.floor((elapsed % 3600) / 60);
    const seconds = elapsed % 60;
    const formattedTime =
        `${String(hours).padStart(2,'0')}:${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}`;

    // === Tampilkan Statistik ===
    document.getElementById('total-questions').textContent   = totalQuestions;
    document.getElementById('answered-count').textContent    = answeredCount;
    document.getElementById('unanswered-count').textContent  = unansweredCount;
    document.getElementById('doubt-count').textContent       = doubtCount;
    document.getElementById('elapsed-time').textContent      = formattedTime;

    // === Saat submit kirim semua data ke server ===
    const form = document.getElementById('confirmForm');
    form.addEventListener('submit', function() {
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
