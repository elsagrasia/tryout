@extends('instructor.instructor_dashboard')
@section('instructor')

<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">							
								<li class="breadcrumb-item active" aria-current="page">Semua Kategori</li>
							</ol>
						</nav>
					</div>
					<div class="ms-auto">
						<div class="btn-group">										
							<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambah Kategori</button>	
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
										<th style="width:5px;">No</th>								
										<th>Nama Kategori</th>
										<th>Kelola</th>
							
									</tr>
								</thead>
								<tbody>

								@foreach ($category as $key=> $item)
								<tr>
									<td>{{ $key+1 }}</td>
									<td>{{ $item->category_name }}</td>
																		
									<td>
									<button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#category" id="{{ $item->id }}" onclick="categoryEdit(this.id)" title="Ubah"><i class="lni lni-eraser"></i></button>
                                    <a href="{{ route('delete.category',$item->id) }}" class="btn btn-danger delete-btn" title="Hapus"><i class="lni lni-trash"></i></a>
									</td>
									
								</tr>
								@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
				
				
			</div>



	<!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Kategori</h5>  
                </div>
                <div class="modal-body"> 
                    <form action="{{ route('store.category') }}" method="post">
                    @csrf

                    <div class="form-group col-md-12">
                        <label for="input1" class="form-label">Kategori</label>
                        <input type="text" name="category_name" class="form-control" id="input1"  >
                    </div>
                            
                </div>
                    <div class="modal-footer"> 
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="category" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Edit Kategori</h5>
                  
                </div>
                <div class="modal-body"> 
                <form action="{{ route('update.category') }}" method="post">
                    @csrf

                    <input type="hidden" name="cat_id" id="cat_id">

                    <div class="form-group col-md-12">
                        <label for="input1" class="form-label">Nama Kategori</label>
                        <input type="text" name="category_name" class="form-control" id="cat"  >
                    </div>
                </div>
                <div class="modal-footer"> 
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function categoryEdit(id){
            $.ajax({
                type: 'GET',
                url: '/edit/category/'+id,
                dataType: 'json',

                success:function(data){
                    // console.log(data)
                    $('#cat').val(data.category_name);
                    $('#cat_id').val(data.id);

                }
            })

        }
    </script>			
@endsection