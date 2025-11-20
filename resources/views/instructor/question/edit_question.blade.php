@extends('instructor.instructor_dashboard')
@section('instructor')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <a href="{{ url()->previous() }}" class="btn btn-outline-primary px-3">Kembali</a>
    </div>
    <!--end breadcrumb-->
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
    </div>
    @endif

    @if(session('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Tambah Pertanyaan</h5>
            <form id="myForm" action="{{ route('update.question') }}" method="post" class="row g-3" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="question_id" value="{{ $question->id }}">

                <div class="form-group col-md-6">
                   <label for="input1" class="form-label">Kategori</label>
                   <select name="category_id" class="form-select mb-3" aria-label="Default select example">
                       <option selected="" disabled>Buka menu pilihan ini</option>
                       @foreach ($categories as $cat)
                       <option value="{{ $cat->id }}" {{ $cat->id == $question->category_id ? 'selected' : '' }}>{{ $cat->category_name }}</option>
                       @endforeach
                   </select>
               </div>
                <div class="form-group col-md-6">
                    <label for="input1" class="form-label">Penyakit</label>
                    <input type="text" name="disease" class="form-control" id="input1" value="{{ $question->disease }}">
                </div>

                <div class="form-group col-md-12">
                    <label for="input1" class="form-label">Vignette</label>
                    <textarea name="vignette" class="form-control" id="input11"  rows="3">{{ $question->vignette }}</textarea>
                </div>
                    
                <div class="form-group col-md-12">
                    <label for="input2" class="form-label">Pertanyaan</label>
                    <textarea name="question_text" class="form-control" id="input11"  rows="3">{{ $question->question_text }}</textarea>
                </div>
                    
                <!-- Options -->
                <div class="form-group col-md-6">
                    <label class="form-label">Pilihan A</label>
                    <input type="text" name="option_a" class="form-control" id="input1" value="{{ $question->option_a }}">
                </div>
                <div class="form-group col-md-6">
                    <label class="form-label">Pilihan B</label>
                    <input type="text" name="option_b" class="form-control" id="input1" value="{{ $question->option_b }}">
                </div>
                <div class="form-group col-md-6">
                    <label class="form-label">Pilihan C</label>
                    <input type="text" name="option_c" class="form-control" id="input1" value="{{ $question->option_c }}">
                </div>
                <div class="form-group col-md-6">
                    <label class="form-label">Pilihan D</label>
                    <input type="text" name="option_d" class="form-control" id="input1" value="{{ $question->option_d }}">
                </div>
                <div class="form-group col-md-6">
                    <label class="form-label">Pilihan E</label>
                    <input type="text" name="option_e" class="form-control" id="input1" value="{{ $question->option_e }}">
                </div>
                                <!-- Correct Answer -->
                <div class="form-group col-md-6">
                    <label class="form-label">Pilihan Benar</label>
                    <select name="correct_option" class="form-select" required>
                        <option disabled selected>-- Pilih Kunci --</option>
                        <option value="A" {{ $question->correct_option == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ $question->correct_option == 'B' ? 'selected' : '' }}>B</option>
                        <option value="C" {{ $question->correct_option == 'C' ? 'selected' : '' }}>C</option>
                        <option value="D" {{ $question->correct_option == 'D' ? 'selected' : '' }}>D</option>
                        <option value="E" {{ $question->correct_option == 'E' ? 'selected' : '' }}>E</option>
                    </select>
                </div>                     
                <div class="form-group col-md-12">
                    <label class="form-label">Pembahasan</label>
                    <textarea name="explanation" class="form-control" id="myeditorinstance" >{{ $question->explanation }}</textarea>
                </div>      
                <div class="page-content">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('update.question') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                <input type="hidden" name="id" value="{{ $question->id }}"> 
                                <input type="hidden" name="old_img" value="{{ $question->image }}">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="input2" class="form-label">Gambar</label>
                                        <input type="file" name="image" class="form-control" id="image" >
                                    </div>
                                    <div class="col-md-6">
                                        <img id="showImage" src="{{ asset($question->image) }}" alt="Image" class="rounded-circle p-1 bg-primary" width="100"> 
                                    </div> 
                                </div>
                                <br><br>
                                    <div class="col-md-12">
                                        <div class="d-md-flex d-grid align-items-center gap-3">
                                            <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>


 
 

<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
            category_id:      { required: true },
            vignette:         { required: true },
            question_text:    { required: true},
            option_a:         { required: true },
            option_b:         { required: true },
            option_c:         { required: true },
            option_d:         { required: true },
            option_e:         { required: true },
            correct_option:   { required: true },
                
            },
            messages :{
                category_id:    { required: "Please choose a category." },
                vignette:       { required: "Vignette is required."},
                question_text:  { required: "Question text is required." },
                option_a:       { required: "Option A is required." },
                option_b:       { required: "Option B is required." },
                option_c:       { required: "Option C is required." },
                option_d:       { required: "Option D is required." },
                correct_option: { required: "Please select the correct option." }, 

            },
            errorElement : 'span', 
            errorPlacement: function (error,element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight : function(element, errorClass, validClass){
                $(element).addClass('is-invalid');
            },
            unhighlight : function(element, errorClass, validClass){
                $(element).removeClass('is-invalid');
            },
        });
    });
    
</script>
<script type="text/javascript">

    $(document).ready(function(){
        $('#image').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#showImage').attr('src', e.target.result);
            }
            reader.readAsDataURL(e.target.files[0]);
        });
    });

</script>
@endsection