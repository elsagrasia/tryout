@extends('instructor.instructor_dashboard')

@section('instructor')
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item active" aria-current="page">Hasil Tryout</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        @foreach($tryoutPackages as $package)
        <div class="col-md-4">
            <div class="card border-primary border-bottom border-3 border-0">
                <div class="card-body">              
                    <h5 class="card-title text-primary">
                       
                            {{ $package->tryout_name }}
                       
                    </h5>                 
                    <hr>

                    <!-- Display additional info about the tryout -->
                    <div >
                        <p>Total Peserta: {{ $package->participants_count }}</p>
                        <p>Rata-rata Nilai: {{ $package->average_score }}</p>
                    </div>
                    
               

                    <!-- Optional: Button for viewing more specific results -->
                    <a href="{{ route('packages.view.results', $package->id) }}" class="btn btn-primary mt-2">Lihat Hasil Tryout</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
