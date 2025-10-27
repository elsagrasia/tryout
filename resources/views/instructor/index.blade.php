@extends('instructor.instructor_dashboard')
@section('instructor')

@php
	$id = Auth::user()->id;
	$instructorId = App\Models\User::find($id);
	$status = $instructorId->status;
@endphp

<div class="page-content">
	@if ($status == '1')
	<h4>Instructor Account Is <span class="text-success">Active</span></h4>
	@else
	<h4>Instructor Account Is <span class="text-danger">InActive</span></h4>
	<p class="text-danger">
		<b>Please wait admin will check and approve your account</b>
	</p>
	@endif
	
	<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
		<div class="col">
			<div class="card radius-10 border-start border-0 border-4 border-warning">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div>
							<p class="mb-0 text-secondary">Total Peserta</p>
							<h4 class="my-1 text-warning">{{ $totalPeserta }}</h4>
						</div>
						<div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i class='bx bxs-group'></i></div>
					</div>
				</div>
			</div>
		</div> 
		<div class="col">
			<div class="card radius-10 border-start border-0 border-4 border-info">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div>
							<p class="mb-0 text-secondary">Total Tryout</p>
							<h4 class="my-1 text-info">{{ $totalTryout }}</h4>
						</div>
						<div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class='fadeIn animated bx bx-notepad'></i></div>
					</div>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card radius-10 border-start border-0 border-4 border-danger">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div>
							<p class="mb-0 text-secondary">Total Soal</p>
							<h4 class="my-1 text-danger">{{ $totalSoal }}</h4>
						</div>
						<div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto"><i class="bx bx-question-mark"></i></div>
					</div>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card radius-10 border-start border-0 border-4 border-success">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div>
							<p class="mb-0 text-secondary">Rata-rata Nilai Peserta</p>
							<h4 class="my-1 text-success">{{ $rataRataNilai }}</h4>
						</div>
						<div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class='bx bxs-bar-chart-alt-2' ></i></div>
					</div>
				</div>
			</div>
		</div>
		
	</div><!--end row-->

	<div class="row">
		<div class="col-12 col-lg-12 d-flex">
			<div class="card radius-10 w-100">
			<div class="card-header">
				<div class="d-flex align-items-center">
				<div>
					<h6 class="mb-0">Tryout Overview</h6>
				</div>
				<div class="dropdown ms-auto">
					<a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
						<i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
					</a>
					<ul class="dropdown-menu">
						<li><a class="dropdown-item" href="javascript:;">Lihat Detail</a></li>
						<li><a class="dropdown-item" href="javascript:;">Export Data</a></li>
						<li>
							<hr class="dropdown-divider">
						</li>
						<li><a class="dropdown-item" href="javascript:;">Pengaturan Tampilan</a></li>
					</ul>
				</div>
				</div>
			</div>

			<div class="card-body">
				<div class="d-flex align-items-center ms-auto font-13 gap-2 mb-3">
					<span class="border px-1 rounded cursor-pointer">
						<i class="bx bxs-circle me-1" style="color: #14abef"></i>Peserta Aktif
					</span>
					<span class="border px-1 rounded cursor-pointer">
						<i class="bx bxs-circle me-1" style="color: #ffc107"></i>Tryout Dikerjakan
					</span>
				</div>

				<div class="chart-container-1">
					<canvas id="chart1"></canvas>
				</div>
			</div>

			<div class="row row-cols-1 row-cols-md-3 row-cols-xl-3 g-0 row-group text-center border-top">
				<div class="col">
					<div class="p-3">
						<h5 class="mb-0">50</h5>
						<small class="mb-0">Total Tryout <span> <i class="bx bx-up-arrow-alt align-middle"></i> 5%</span></small>
					</div>
				</div>
				<div class="col">
					<div class="p-3">
						<h5 class="mb-0">120</h5>
						<small class="mb-0">Total Peserta <span> <i class="bx bx-up-arrow-alt align-middle"></i> 3%</span></small>
					</div>
				</div>
				<div class="col">
					<div class="p-3">
						<h5 class="mb-0">85%</h5>
						<small class="mb-0">Rata-rata Penyelesaian <span> <i class="bx bx-up-arrow-alt align-middle"></i> 2%</span></small>
					</div>
				</div>
			</div>
			</div>
		</div>
	</div><!--end row-->
</div>

@endsection