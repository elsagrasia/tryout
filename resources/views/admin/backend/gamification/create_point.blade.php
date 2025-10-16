@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3"> 
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah Aturan Poin</li>
                </ol>
            </nav>
        </div>
         
    </div>
    <!--end breadcrumb-->
 
    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Tambah Aturan Poin</h5>
            <form id="myForm" action="{{ route('points.rules.store') }}" method="post" class="row g-3" enctype="multipart/form-data">
                @csrf

 
                <div class="form-group col-md-6">
                    <label for="input1" class="form-label">Aktivitas</label>
                    <input type="text" name="activity" class="form-control" id="input1" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="input1" class="form-label">Poin</label>
                    <input type="number" name="points" class="form-control" required>
                </div>
                <div>
                    <label>Deskripsi</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>            
                <div class="form-group col-md-6">
                    <label for="input1" class="form-label">Status</label>
                    <select name="status" class="form-select mb-3" aria-label="Default select example">
                        <option selected="">Pilih Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>                
             
                <div class="col-md-12">
                    <div class="d-md-flex d-grid align-items-center gap-3">
          <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                      
                    </div>
                </div>
            </form>
        </div>
    </div>


   
   
</div>

 


@endsection