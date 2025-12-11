@extends('admin.admin_dashboard')
@section('admin')
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3"> 
       
        <div class="ms-auto">
            <div class="btn-group">
           <a href="{{ route('badges.create') }}" class="btn btn-primary px-5">Tambah Aturan Lencana</a>  
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
                            <th>Gambar</th>
                            <th>Nama</th> 
                            <th>Deskripsi</th>
                            <th>Kriteria</th>
                            <th>Ambang</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                        @foreach ($badges as $key=> $item) 
                        <tr>
                            <td>{{ $key+1 }}</td>                           
                            <td><img src="{{ asset($item->icon) }}" alt="{{ $item->name }}" style="width: 60px; height:60px;"></td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ ucfirst($item->type) }}</td>
                            <td>{{ $item->threshold }}</td>
                            <td>
                            		<label class="form-check-label ms-2" for="flexSwitchCheckSuccess status-{{ $item->id }}">
										<span class="badge rounded-pill {{ $item->status === 'active' ? 'bg-success' : 'bg-danger' }} status-badge-{{ $item->id }}">
										{{ $item->status === 'active' ? 'Active' : 'Inactive' }}
										</span>
									</label>
                            </td>
                            <td>
       <a href="{{ route('badges.edit',$item->id) }}" class="btn btn-info" title="Edit"><i class="lni lni-eraser"></i></a>   
       <a href="{{ route('badges.delete',$item->id) }}" class="btn btn-danger delete-btn" title="Delete"><i class="lni lni-trash"></i></a>                    
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