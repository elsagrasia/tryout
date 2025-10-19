@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3"> 
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah Badge</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Tambah Badge Baru</h5>
            <form action="{{ route('badges.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="name" class="form-label">Nama Badge</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label for="threshold" class="form-label">Ambang (Threshold)</label>
                        <input type="number" name="threshold" id="threshold" class="form-control" placeholder="cth: 100" required>
                    </div>

                    <div class="col-md-6">
                        <label for="type" class="form-label">Kriteria Badge</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="">-- Pilih Kriteria --</option>
                            <option value="points">Berdasarkan Poin</option>
                            <option value="tryout">Berdasarkan Tryout</option>
                            <option value="blog">Berdasarkan Aktivitas Blog</option>
                        </select>
                    </div>
            
                    <div class="form-group col-md-6">
                        <label for="icon" class="form-label">Icon (Gambar)</label>
                        <input class="form-control" name="icon" type="file" id="icon">
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" rows="3" class="form-control"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
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
