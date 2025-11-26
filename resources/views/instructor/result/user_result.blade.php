@extends('instructor.instructor_dashboard')

@section('instructor')
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3"></div>
<a href="{{ route('packages.view.results', ['package' => $result->tryout_package_id]) }}" 
   class="btn btn-outline-primary px-3">
    Kembali
</a>
      
    </div>
    <!--end breadcrumb-->

    <div class="card">
        <div class="card-body">
            <h5 class="mb-3 fw-bold">Detail Jawaban Peserta</h5>

            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kategori</th>
                            <th>Soal</th>
                            <th>Jawaban Dipilih</th>
                            <th>Jawaban Benar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($answers as $index => $answer)
                            @php
                                $isCorrect = $answer->selected_option === $answer->question->correct_option;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $answer->question->category->category_name }}</td>
                                <td class="question-column">{{ $answer->question->question_text }}</td>
                                <td>{{ $answer->selected_option ?? '-' }}</td>
                                <td>{{ $answer->question->correct_option }}</td>
                                <td>
                                    @if($isCorrect)
                                        <span class="badge bg-success">
                                            <i class="fadeIn animated bx bx-check"></i>
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fadeIn animated bx bx-x"></i>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

    <!-- Ringkasan Hasil -->
    <div class="row mt-3">

        {{-- Detail Tryout --}}
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm summary-card">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="bx bx-task"></i> Detail Tryout
                    </h5>
                    <hr>

                    <p class="mb-2">
                        <strong>Total Soal:</strong> {{ $result->total_questions }}
                    </p>
                    <p class="mb-2">
                        <strong>Benar:</strong> {{ $result->correct_answers }}
                    </p>
                    <p class="mb-2">
                        <strong>Salah:</strong> {{ $result->total_questions - $result->correct_answers }}
                    </p>

                    <div class="text-end mt-3">
                        <span class="fw-bold fs-5 text-primary">
                            Skor Akhir: {{ $result->score }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Peserta --}}
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm summary-card">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="bx bx-user"></i> Detail Peserta
                    </h5>
                    <hr>

                    <p class="mb-2">
                        <strong>Nama:</strong> {{ $result->user->name }}
                    </p>
                    <p class="mb-2">
                        <strong>Email:</strong> {{ $result->user->email }}
                    </p>
                    <p class="mb-2">
                        <strong>No. Telp.:</strong> {{ $result->user->phone }}
                    </p>

                    <div class="text-end mt-3">
                        <span class="fw-bold fs-5 text-success">
                            Total Poin: {{ $result->user->total_points }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    td.question-column {
        max-width: 350px;
        white-space: normal;
        word-wrap: break-word;
    }

    .summary-card {
        border-radius: 12px;
    }

    .summary-card h5 {
        font-size: 1.1rem;
    }

    .summary-card hr {
        opacity: 0.2;
        margin-top: 0;
        margin-bottom: 0.75rem;
    }
</style>
@endsection
