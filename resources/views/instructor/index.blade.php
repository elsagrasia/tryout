@extends('instructor.instructor_dashboard')
@section('instructor')

@php
	$id = Auth::user()->id;
	$instructorId = App\Models\User::find($id);
	$status = $instructorId->status;
@endphp

<div class="page-content">
	<!-- Welcome Section -->
    <div class="card border-0 shadow-sm mb-4" style="background-color: #e8f3ff;"> <!-- biru muda -->
        <div class="card-body text-center py-5">
            <h2 class="fw-bold text-primary mb-2">Selamat Datang, Instructor!</h2>
            <p class="text-secondary mb-0">Anda berada di Dashboard Sistem E-Learning ukom.aja</p>
        </div>
    </div>
	
	<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
		<div class="col">
			<div class="card radius-10 border-start border-0 border-4 border-primary">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div>
							<p class="mb-0 text-secondary">Total Peserta</p>
							<h4 class="my-1 text-primary">{{ $totalPeserta }}</h4>
						</div>
						<div class="widgets-icons-2 rounded-circle bg-gradient-primary text-primary ms-auto"><i class='bx bxs-group'></i></div>
					</div>
				</div>
			</div>
		</div> 
		<div class="col">
			<div class="card radius-10 border-start border-0 border-4 border-primary">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div>
							<p class="mb-0 text-secondary">Total Tryout</p>
							<h4 class="my-1 text-primary">{{ $totalTryout }}</h4>
						</div>
						<div class="widgets-icons-2 rounded-circle bg-gradient-primary text-primary ms-auto"><i class='fadeIn animated bx bx-notepad'></i></div>
					</div>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card radius-10 border-start border-0 border-4 border-primary">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div>
							<p class="mb-0 text-secondary">Total Soal</p>
							<h4 class="my-1 text-primary">{{ $totalSoal }}</h4>
						</div>
						<div class="widgets-icons-2 rounded-circle bg-gradient-primary text-primary ms-auto"><i class="bx bx-question-mark"></i></div>
					</div>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card radius-10 border-start border-0 border-4 border-primary">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div>
							<p class="mb-0 text-secondary">Rata-rata Nilai Peserta</p>
							<h4 class="my-1 text-primary">{{ $rataRataNilai }}</h4>
						</div>
						<div class="widgets-icons-2 rounded-circle bg-gradient-primary text-primary ms-auto"><i class='bx bxs-bar-chart-alt-2' ></i></div>
					</div>
				</div>
			</div>
		</div>
	</div><!--end row-->
</div>

@endsection