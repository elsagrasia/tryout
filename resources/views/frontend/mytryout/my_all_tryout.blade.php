@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')

{{-- <div class="container-fluid">
     
    <div class="section-block mb-5"></div>
    <div class="dashboard-heading mb-5">
        <h3 class="fs-22 font-weight-semi-bold">My tryout</h3>
    </div>
    <div class="dashboard-cards mb-5">
       
       @foreach ($mytryout as $item)
        <div class="card card-item card-item-list-layout">
            <div class="card-image">
                <a href="{{ route('tryout.view',$item->course_id) }}" class="d-block">
                    <img class="card-img-top" src="{{ asset($item->course->course_image) }}" alt="Card image cap">
                </a>
                
            </div><!-- end card-image -->
            <div class="card-body">
                <h6 class="ribbon ribbon-blue-bg fs-14 mb-3">{{ $item->course->label }}</h6>
                <h5 class="card-title"><a href="{{ route('course.view',$item->course_id) }}">{{ $item->course->course_name }}</a></h5>
                <p class="card-text"><a href="teacher-detail.html">{{ $item->course->user->name }}</a></p>
                
                <ul class="card-duration d-flex align-items-center fs-15 pb-2">
                    <li class="mr-2">
                        <span class="text-black">Status:</span>
                        <span class="badge badge-success text-white">Published</span>
                    </li>
                    <li class="mr-2">
                        <span class="text-black">Duration:</span>
                        <span>{{ $item->course->duration }} hours </span>
                    </li>
                    <li class="mr-2">
                        <span class="text-black">Students:</span>
                        <span>30,405</span>
                    </li>
                </ul>
                <div class="d-flex justify-content-between align-items-center">
                    <p class="card-price text-black font-weight-bold">${{ $item->course->selling_price }}</p>
                    <div class="card-action-wrap pl-3">
                        <a href="course-details.html" class="icon-element icon-element-sm shadow-sm cursor-pointer ml-1 text-success" data-toggle="tooltip" data-placement="top" data-title="View"><i class="la la-eye"></i></a>
                        <div class="icon-element icon-element-sm shadow-sm cursor-pointer ml-1 text-secondary" data-toggle="tooltip" data-placement="top" data-title="Edit"><i class="la la-edit"></i></div>
                        <div class="icon-element icon-element-sm shadow-sm cursor-pointer ml-1 text-danger" data-toggle="tooltip" data-placement="top" title="Delete">
                            <span data-toggle="modal" data-target="#itemDeleteModal" class="w-100 h-100 d-inline-block"><i class="la la-trash"></i></span>
                        </div>
                    </div>
                </div>
            </div><!-- end card-body -->
        </div><!-- end card --> 
        @endforeach


       
    </div><!-- end col-lg-12 -->
     
    
</div><!-- end container-fluid --> --}}


<div class="container mt-4 mb-5">
    <div class="dashboard-heading mb-4">
        <h3 class="fs-22 fw-semibold">My Tryout</h3>
    </div>

    @if($myTryouts->isEmpty())
        <div class="text-center text-muted mt-5">
            <i class="la la-folder-open fs-40 mb-3 d-block"></i>
            <p>Kamu belum mengikuti tryout apa pun.</p>
        </div>
    @else
        <div class="list-group">
            @foreach ($myTryouts as $item)
                @php
                    $tryout = $item->tryoutPackage;
                @endphp

                {{-- <div class="list-group-item mb-2 d-flex justify-content-between align-items-center border rounded shadow-sm">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width:45px; height:45px;">
                            <i class="la la-file-alt fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold">{{ $tryout->tryout_name }}</h6>
                            <small class="text-muted">{{ $tryout->total_questions }} Soal • {{ $tryout->duration }} Menit</small>
                        </div>
                    </div>
                    <a href="{{ url('tryout/start/'.$tryout->id) }}" class="btn btn-success btn-sm">
                        <i class="la la-play-circle me-1"></i> Kerjakan
                    </a>
                    
                </div> --}}
                <div class="list-group-item mb-2 d-flex justify-content-between align-items-center border rounded shadow-sm">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                            style="width:45px; height:45px;">
                            <i class="la la-file-alt fs-5"></i>
                        </div>
                        <div style="margin-left: 15px;"> <!-- jarak antar ikon dan teks -->
                            <h6 class="mb-1 fw-bold">{{ $tryout->tryout_name }}</h6>
                            <small class="text-muted">{{ $tryout->total_questions }} Soal • {{ $tryout->duration }} Menit</small>
                        </div>
                    </div>

                    {{-- <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('tryout.start', $tryout->id) }}" class="btn btn-success btn-sm">
                            <i class="la la-play-circle me-1"></i> Kerjakan
                        </a>
                        <a href="{{ route('delete.tryout.package', $tryout->id) }}" class="btn btn-danger btn-sm delete-btn" title="Hapus">
                            <i class="la la-trash"></i>
                        </a>
                    </div> --}}
                    <div class="d-flex align-items-center">
                        <a href="{{ route('tryout.start', $tryout->id) }}" 
                        class="btn btn-success btn-sm" 
                        style="margin-right: 8px;">
                            <i class="la la-play-circle me-1"></i> Kerjakan
                        </a>

                        <a href="{{ route('delete.tryout', $tryout->id) }}" 
                        class="btn btn-danger btn-sm delete-btn" 
                        title="Hapus">
                            <i class="la la-trash"></i>
                        </a>
                    </div>

                </div>


            @endforeach
        </div>
    @endif
</div>







@endsection







