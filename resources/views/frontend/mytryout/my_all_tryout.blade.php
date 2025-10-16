@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')



<div class="container mt-4 mb-5">
    <div class="dashboard-heading mb-4">
        <h3 class="fs-22 fw-semibold">Tryout</h3>
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

                    <div class="d-flex align-items-center">
                        <a href="{{ route('tryout.start', $tryout->id) }}" 
                        class="btn btn-success btn-sm" 
                        style="margin-right: 8px;">
                            <i class="la la-play-circle me-1"></i> Kerjakan
                        </a>

                    </div>

                </div>


            @endforeach
        </div>
    @endif
</div>







@endsection







