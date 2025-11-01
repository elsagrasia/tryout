@extends('instructor.instructor_dashboard')

@section('instructor')
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:;">Hasil Tryout</a>
                    </li>                    
                    <li class="breadcrumb-item">
                        <a href="">Peserta</a>
                    </li>                    
					<li class="breadcrumb-item active" aria-current="page">Detail</li>
                </ol>
            </nav>
        </div>		
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
                                    <span class="badge bg-success"><i class="fadeIn animated bx bx-check"></i></span>
                                @else
                                    <span class="badge bg-danger"><i class="fadeIn animated bx bx-x"></i></span>
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
     <div class="row">
        <div class="col-md-6">
            <div class="alert alert-info">
                <div>
                    <h5>Detail Tryout</h5><hr>
                    <strong>Total Soal:</strong> {{ $result->total_questions }} <br>
                    <strong>Benar:</strong> 
                    {{ $result->correct_answers }} <br>
                    <strong>Salah:</strong> 
                    {{ $result->correct_answers }}
                    <div class="text-end">
                        <strong>Skor Akhir:</strong> {{ $result->score }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="alert alert-info">
                <div>
                    <h5>Detail Peserta</h5><hr>
                    <strong>Nama:</strong> {{ $result->user->name }} <br>
                    <strong>Email:</strong> {{ $result->user->email }} <br>
                    <strong>No. Telp.:</strong> {{ $result->user->phone }} <br>                 
                    <div class="text-end">
                        <strong>Total Poin:</strong> {{ $result->user->total_points }}
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
</style>
@endsection
