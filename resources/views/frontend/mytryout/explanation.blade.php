@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')

<div class="container py-4">
    <h4 class="mb-3">{{ $tryout->tryout_name }}</h4>

    {{-- Ringkasan Nilai --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h1>{{ $tryout->score ?? '-' }}</h1>
                <small>Nilai</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5>✔️ Jawaban Benar</h5>
                <p>{{ $tryout->correct_count ?? 0 }} / {{ $tryout->total_questions ?? 0 }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5>❌ Jawaban Salah</h5>
                <p>{{ $tryout->wrong_count ?? 0 }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5>⏱️ Waktu</h5>
                <p>{{ $tryout->duration ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="filterType" class="form-label">Tipe Jawaban</label>
            <select id="filterType" class="form-select" onchange="filterQuestions()">
                <option value="all">Semua</option>
                <option value="correct">Jawaban Benar</option>
                <option value="wrong">Jawaban Salah</option>
                <option value="unanswered">Tidak Dijawab</option>
                <option value="doubt">Ragu</option>
            </select>
        </div>
    </div>

    {{-- Soal dan Pembahasan --}}
    <div id="questionList">
        @foreach($tryout->questions as $index => $question)
            <div class="card mb-4 question-item" data-type="{{ $question->user_answer_status }}">
                <div class="card-body">
                    <h5>Soal {{ $index + 1 }}</h5>
                    <p>{!! $question->question_text !!}</p>

                    @if($question->image)
                        <img src="{{ asset('upload/question/' . $question->image) }}" class="img-fluid my-3 rounded">
                    @endif

                    <p><strong>Jawaban kamu:</strong> {{ $question->user_answer ?? '-' }}</p>
                    <p><strong>Jawaban benar:</strong> {{ $question->correct_answer }}</p>

                    <div class="mt-3 p-3 bg-light rounded">
                        <strong>Pembahasan:</strong>
                        <p>{!! $question->explanation !!}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
function filterQuestions() {
    const type = document.getElementById('filterType').value;
    const questions = document.querySelectorAll('.question-item');
    questions.forEach(q => {
        if (type === 'all' || q.dataset.type === type) {
            q.style.display = '';
        } else {
            q.style.display = 'none';
        }
    });
}
</script>

@endsection
