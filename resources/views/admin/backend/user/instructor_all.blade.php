@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
  
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gambar Instructor</th>
                            <th>Nama</th> 
                            <th>Email</th> 
                            <th>Telepon</th> 
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                        @foreach ($users as $key=> $item) 
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td> <img src="{{ (!empty($item->photo)) ? url('upload/instructor_images/'.$item->photo) : url('upload/no_image.jpg')}}" alt="" style="width: 70px; height:40px;"> </td>
                            <td>{{ $item->name }}</td> 
                            <td>{{ $item->email }}</td> 
                            <td>{{ $item->phone }}</td> 
                            <td>
                                @if ($item->UserOnline())
                                <span class="badge badge-pill bg-success">Aktif Sekarang</span>
                                @else 
                                <span class="badge badge-pill bg-danger">{{ Carbon\Carbon::parse($item->last_seen)->diffForHumans() }} </span>  
                                     
                                @endif    
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