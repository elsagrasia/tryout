@extends('instructor.instructor_dashboard')

@section('instructor')
<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <a href="{{ route('all.packages.result') }}" class="btn btn-outline-primary px-3">Kembali</a>
        </div>
    <div class="card">
        <div class="card-body">
            @if ($results->isNotEmpty())
                <h5 class="mb-3 fw-bold">{{ $results->first()->tryoutPackage->tryout_name }}</h5>
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Hasil</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $key => $item)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $item->user->name }}</td>
                                <td>{{ $item->user->email }}</td>
                                <td>
                                    Total Soal: {{ $item->total_questions }}<br>
                                    Jawaban Benar: {{ $item->correct_answers }}<br>
                                    Nilai: {{ $item->score }}
                                </td>
                                <td>
                                    <a href="{{ route('user.results', [$item->tryout_package_id, $item->user->id]) }}" 
                                        class="btn btn-primary" title="Detail">
                                        <i class="bi bi-eye"></i> Lihat Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else                
                <div class="alert alert-warning text-center" role="alert">
                    <i class="bx bx-info-circle me-2"></i> Maaf, belum ada peserta yang mengikuti tryout ini.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection