@extends('instructor.instructor_dashboard')
@section('instructor')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Ubah Password</div>
        
      
    </div>
    <!--end breadcrumb-->
    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <img src="{{ (!empty($profileData->photo)) ? url('upload/instructor_images/'.$profileData->photo) : url('upload/no_image.jpg') }}" alt="Instructor" class="rounded-circle p-1 bg-primary" width="110"> 
                                <div class="mt-3">
                                    <h4>{{ $profileData->name }}</h4>
                                    <p class="text-secondary mb-1">{{ $profileData->username }}</p>
                                    <p class="text-muted font-size-sm">{{ $profileData->email }}</p>                      
                                </div>
                            </div>
                          
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <form action="{{ route('instructor.password.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Password Lama</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="password" name="old_password" class="form-control @error('old_password') is-invalid @enderror" id="old_password"  />
                                        @error('old_password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Password Baru</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" id="new_password"  />
                                        @error('new_password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Konfirmasi Password Baru</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="password" name="new_password_confirmation" class="form-control " id="new_password_confirmation"  />
                                    </div>
                                </div>
                              
                                
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="Simpan" />
                                    </div>
                                </div>
                            </div>       
                        </form>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</div>


@endsection            