@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3"> 
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">All Blog Post</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
           <a href="{{ route('points.rules.create') }}" class="btn btn-primary px-5">Tambah Aturan Poin</a>  
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
                            <th>Aktivitas</th>
                            <th>Poin</th> 
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                        @foreach ($points as $key=> $item) 
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $item->activity }}</td>
                            <td>{{ $item->points }}</td>
                            <td>{{ $item->description }}</td>
                            <td>
                            		<label class="form-check-label ms-2" for="flexSwitchCheckSuccess status-{{ $item->id }}">
										<span class="badge rounded-pill {{ $item->status === 'active' ? 'bg-success' : 'bg-danger' }} status-badge-{{ $item->id }}">
										{{ $item->status === 'active' ? 'Active' : 'Inactive' }}
										</span>
									</label>
                            </td>
                            <td>
       <a href="{{ route('edit.post',$item->id) }}" class="btn btn-info px-5">Edit </a>   
       <a href="{{ route('delete.post',$item->id) }}" class="btn btn-danger px-5 delete-btn">Delete </a>                    
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