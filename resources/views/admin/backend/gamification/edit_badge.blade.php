@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3"> 
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Badge</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Edit Badge</h5>
            <form id="myForm" action="{{ route('badges.update', $badge->id) }}" method="post" class="row g-3" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $badge->id }}">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nama Badge</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $badge->name }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="threshold" class="form-label">Ambang (Threshold)</label>
                        <input type="number" name="threshold" id="threshold" class="form-control" placeholder="cth: 100" value="{{ $badge->threshold }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="type" class="form-label">Kriteria Badge</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="">-- Pilih Kriteria --</option>
                            <option value="points" {{ $badge->type == 'points' ? 'selected' : '' }}>Berdasarkan Poin</option>
                            <option value="tryout" {{ $badge->type == 'tryout' ? 'selected' : '' }}>Berdasarkan Tryout</option>
                            <option value="blog" {{ $badge->type == 'blog' ? 'selected' : '' }}>Berdasarkan Aktivitas Blog</option>
                        </select>
                    </div>
            
                    <div class="form-group col-md-4"> 
                        <label for="icon" class="form-label">Icon (Gambar)</label>
                        <input class="form-control" name="icon" type="file" id="icon">                    
                    </div>         
                    <div class="col-md-2">
                        <img id="showImage" src="{{ asset($badge->icon) }}" alt="Admin" class="rounded-circle p-1 bg-primary" width="80"> 
                    </div>           
           
                        <div class="col-12">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" rows="3" class="form-control">{{ $badge->description }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ $badge->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $badge->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-12 mt-3">
                        <button type="submit" class="btn btn-primary px-4">Simpan Badge</button>
                        <a href="{{ route('badges') }}" class="btn btn-secondary px-4">Kembali</a>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function(){
        $('#image').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#showImage').attr('src',e.target.result);
            }
            reader.readAsDataURL(e.target.files['0']);
        });
    });

</script>

@endsection
