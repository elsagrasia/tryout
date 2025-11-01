@extends('instructor.instructor_dashboard')
@section('instructor')
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li><li class="breadcrumb-item active" aria-current="page">Hasil Tryout</li>
                </ol>
            </nav>
        </div>
    </div>
<div class="row">
    @foreach($tryoutPackages as $package)
    <div class="col-md-4 ">
        <div class="card border-primary border-bottom border-3 border-0">
            <div class="card-body">              
                <h5 class="card-title text-primary"><a href="{{ route('packages.view.results', $package->id) }}">{{ $package->tryout_name }}</a></h5>
                <p class="card-text">{{ $package->description }}</p>
                <hr>
                <div class="d-flex align-items-center gap-2">
                    <small>Dibuat oleh: {{ $package->instructor->name }}</small>
                    
                </div>
            </div>
        </div>
        
    </div>
    @endforeach
</div>  
</div>
@endsection
