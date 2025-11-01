@extends('instructor.instructor_dashboard')
@section('instructor')

<div class="page-content">
	<!--breadcrumb-->
	<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
		
		<div class="ps-3">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0 p-0">
					<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
					</li>
					<li class="breadcrumb-item active" aria-current="page">Hasil Tryout</li>
					<li class="breadcrumb-item active" aria-current="page">Peserta</li>
				</ol>
			</nav>
		</div>		
	</div>
	<!--end breadcrumb-->
				
	<div class="card">
		<div class="card-body">
            @foreach ($results as $result )
            <h5 class="mb-3 fw-bold">{{ $result->tryoutPackage->tryout_name }}</h5>
			<div class="table-responsive">
                @endforeach
				<table id="example" class="table table-striped table-bordered" style="width:100%">
					<thead>
						<tr>
							<th>No</th>								
							<th>Username</th>
							<th>Email</th>
							<th>Skor</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody>
@foreach ($results as $key => $item )
					<tr>
						<td>{{ $key+1 }}</td>
						<td>{{ $item->user->name }}</td>
						<td>{{ $item->user->email }}</td>
						<td>Total Soal: {{ $item->total_questions }}<br>
                            Jawaban Benar: {{ $item->correct_answers }}<br>
                            Skor: {{ $item->score }}
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
		</div>
	</div>
</div>

	<style>
		td.question-column {
		max-width: 300px;
		white-space: normal;
		word-wrap: break-word;
		}

	</style>


@endsection