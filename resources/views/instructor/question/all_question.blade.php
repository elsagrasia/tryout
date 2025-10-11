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
								<li class="breadcrumb-item active" aria-current="page">All Question</li>
							</ol>
						</nav>
					</div>
					<div class="ms-auto">
						<div class="btn-group">										
                            <a href="{{ route('add.question') }}" class="btn btn-primary px-5">Add Question</a>
							&nbsp;&nbsp;
								<a href="{{ route('import.question') }}" class="btn btn-warning ">Import </a>  	
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
										<th>Category Name</th>
										<th>Question</th>
										<th>Correct Option</th>
                                        <th>Action</th>
							
									</tr>
								</thead>
								<tbody>

								@foreach ($question as $key=> $item)
								<tr>
									<td>{{ $key+1 }}</td>
									<td>{{ $item->category->category_name }}</td>
									<td class="question-column">{{ $item->question_text}}</td>
									<td>{{ $item->correct_option}}</td>																	
									<td>
                                    <a href="{{ route('edit.question',$item->id) }}" class="btn btn-info px-5">Edit </a>
                                    <a href="{{ route('delete.question',$item->id) }}" class="btn btn-danger px-5 delete-btn">Delete </a>
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