@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
   
 
    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Tambahkan Postingan Blog</h5>
            <form id="myForm" action="{{ route('store.blog.post') }}" method="post" class="row g-3" enctype="multipart/form-data">
                @csrf

                <div class="form-group col-md-6">
                    <label for="input1" class="form-label">Kategori Blog</label>
                    <select name="blogcat_id" class="form-select mb-3" aria-label="Default select example">
                        <option selected="">Pilih kategori</option>
                        @foreach ($blogcat as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->category_name }}</option> 
                        @endforeach
                        
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="input1" class="form-label">Judul Postingan</label>
                    <input type="text" name="post_title" class="form-control" id="input1"  >
                </div>


                
                <div class="form-group col-md-12">
                    <label for="input1" class="form-label">Deskripsi Postingan</label>
                    <textarea name="long_descp" class="form-control" id="myeditorinstance"></textarea>
                </div>


              
                <div class="form-group col-md-6">
                    <label for="input2" class="form-label">Gambar Postingan</label>
                    <input class="form-control" name="post_image" type="file" id="image">
                </div>

                <div class="col-md-6"> 
                    <img id="showImage" src="{{ url('upload/no_image.jpg')}}" alt="Admin" class="rounded-circle p-1 bg-primary" width="80"> 

                </div>


             
                <div class="col-md-12">
                    <div class="d-md-flex d-grid align-items-center gap-2">
                        <button type="submit" class="btn btn-primary px-4">Simpan</button> 
                        <a href="{{ route('blog.post') }}" class="btn btn-secondary px-4">Kembali</a>
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