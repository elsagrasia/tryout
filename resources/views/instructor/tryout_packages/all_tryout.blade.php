@extends('instructor.instructor_dashboard')
@section('instructor')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src></script>
<style>
    .large-checkbox{
        transform: scale(1.5);
    }
</style>
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
								<th>Status</th>
								<th>Action</th>
								</tr>
							</thead>
							<tbody>
							@foreach ($tryoutPackages as $key => $item)
								<tr>
									<td>{{ $key+1 }}</td>
									<td>{{ $item->tryout_name }}</td>
									<td>{{ $item->duration }}</td>
									<td>{{ $item->total_questions }}</td>
									<td>
										<div class="form-check form-switch">
										<input
											class="form-check-input status-switch large-checkbox"
											type="checkbox"
											role="switch"
											id="flexSwitchCheckSuccess status-{{ $item->id }}"
											data-id="{{ $item->id }}"
											{{ $item->status === 'published' ? 'checked' : '' }}>
										<label class="form-check-label ms-2" for="flexSwitchCheckSuccess status-{{ $item->id }}">
											<span class="badge rounded-pill {{ $item->status === 'published' ? 'bg-success' : 'bg-danger' }} status-badge-{{ $item->id }}">
											{{ $item->status === 'published' ? 'Published' : 'Draft' }}
											</span>
										</label>
										</div>
									</td>
									<td>
										<a href="{{ route('edit.tryout.package', $item->id) }}" class="btn btn-info"  title="Edit"><i class="lni lni-eraser"></i></a>
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
					<div class="row row-cols-1 row-cols-md-3 row-cols-xl-5">
					<div class="col">
						<div class="card radius-10">
							<div class="card-body">
								<div class="text-center">
									<div class="widgets-icons rounded-circle mx-auto bg-light-primary text-primary mb-3"><i class='bx bxl-facebook-square'></i>
									</div>
									<h4 class="my-1">84K</h4>
									<p class="mb-0 text-secondary">Facebook Users</p>
								</div>
							</div>
						</div>
					</div>
 <script>
$(function () {
  $(document).on('change', '.status-switch', function () {
    const $el = $(this);
    const id = $el.data('id');
    const checked = $el.is(':checked'); // true -> published
    const original = checked;

    $el.prop('disabled', true);

    $.ajax({
      url: "{{ route('update.tryout.package.status') }}",
      type: "POST",
      data: {
        tryout_package_id: id,
        status: checked ? 'published' : 'draft',
        _token: "{{ csrf_token() }}"
      },
      success(res){
        toastr.success(res.message || 'Status updated');

        // update badge kecil (opsional)
        const $badge = $('.status-badge-' + id);
        if ($badge.length) {
          $badge
            .removeClass('bg-success bg-danger')
            .addClass(checked ? 'bg-success' : 'bg-danger')
            .text(checked ? 'Published' : 'Draft');
        }
      },
      error(xhr){
        toastr.error(xhr.responseJSON?.message || 'Gagal mengubah status');
        $el.prop('checked', !original); // rollback kalau gagal
      },
      complete(){
        $el.prop('disabled', false);
      }
    });
  });
});
</script>



@endsection