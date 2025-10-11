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
								<li class="breadcrumb-item active" aria-current="page">All Tryout</li>
							</ol>
						</nav>
					</div>
					<div class="ms-auto">
						<div class="btn-group">
							<a href="{{ route('add.tryout') }}" class="btn btn-primary px-5">New Package</a>
						</div>
					</div>
				</div>
				<!--end breadcrumb-->
				
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example" class="table table-striped table-bordered" style="width:100%">
								<thead>
									<tr>
										<th>No</th>								
										<th>Tryout Package</th>
										<th>Duration</th>
										<th>Total Questions</th>
										<th>Action</th>
							
									</tr>
								</thead>
								<tbody>

								@foreach ($tryoutPackages as $key=> $item)
								<tr>
									<td>{{ $key+1 }}</td>
									<td>{{ $item->tryout_name }}</td>
									<td>{{ $item->duration }}</td>
									<td>{{ $item->total_questions }}</td>									
									{{-- <td>{{ $item->questions_count }}</td> --}}

									<td>
									<a href="{{ route('edit.tryout.package', $item->id) }}" class="btn btn-info" title="Edit"><i class="lni lni-eraser"></i></a>
									<a href="{{ route('delete.tryout.package', $item->id) }}" class="btn btn-danger delete-btn" title="Delete"><i class="lni lni-trash"></i></a>
									<a href="{{ route('packages.manage', $item->id) }}" class="btn btn-warning" title="Soal"><i class="lni lni-list"></i></a>
									</td>
									
								</tr>
								@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
				
				
			</div>


@endsection