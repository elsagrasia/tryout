@extends('instructor.instructor_dashboard')
@section('instructor')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add Tryout Package</li>
                </ol>
            </nav>
        </div>
            
    </div>
    <!--end breadcrumb-->
    
    <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">Add Tryout Package</h5>
                <form id="myForm" action="{{ route('store.tryout.package') }}" method="post" class="row g-3" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">Tryout Name</label>
                        <input type="text" name="tryout_name" class="form-control" id="input1" >
                    </div>
                    <div class="form-group col-md-3">
                        <label class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                        <input type="number" name="duration" min="1" class="form-control" value="{{ old('duration', 200) }}" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="form-label">Total Questions</label>
                        <input type="number" name="total_questions" min="0" class="form-control" value="{{ old('total_questions', 150) }}">
                    </div>
             
                    <div class="form-group col-md-12">
                        <label for="input1" class="form-label">Tryout Description</label>
                        <textarea name="description" class="form-control" id="input11" rows="3"></textarea>
                    </div>
              
                </div>
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



<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                tryout_name: {
                    required : true,
                }, 
              
                
            },
            messages :{
                tryout_name: {
                    required : 'Please Enter Tryout Name',
                }, 
              

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

@endsection