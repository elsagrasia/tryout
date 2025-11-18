@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')

<div class="container-fluid   mb-5">
    <div class="row">
        <div class="col-12">

            <div class="card border-0 shadow-sm  px-4 py-4" style="background-color: #f8fcff;">
                
                <div class=" mb-4">
                    <h3 class="fs-22 fw-semibold">Tryout</h3>
                </div>
                    {{-- Tabs kategori --}}
        <ul class="nav nav-tabs generic-tab justify-content-center pb-4" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab">Semua</a>
            </li>

            @foreach ($tryoutsByBidang as $categoryName => $tryouts)
                <li class="nav-item">
                    <a class="nav-link" id="cat{{ $categoryName }}-tab" data-toggle="tab" href="#cat{{ $categoryName }}" role="tab">
                        {{ $categoryName }}
                    </a>
                </li>
            @endforeach
        </ul>
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
                                $result = $results[$tryout->id] ?? null;
                                $isDone = $result ? true : false;
                            @endphp
                    
                            <div class="list-group-item mb-2 d-flex justify-content-between 
                                        align-items-center border rounded shadow-sm">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex 
                                                align-items-center justify-content-center" 
                                         style="width:45px; height:45px;">
                                        <i class="la la-file-alt fs-5"></i>
                                    </div>
                                    <div style="margin-left: 15px;">
                                        <h6 class="mb-1 fw-bold">{{ $tryout->tryout_name }}</h6>
                                        <small class="text-muted">
                                            <i class="las la-clock"></i>
                                            {{ $tryout->total_questions }} Soal • 
                                            <i class="las la-question-circle"></i>
                                            {{ $tryout->duration }} Menit
                                        </small>
                                    </div>
                                </div>

                                <div class="text-end">
                                    @if($isDone)
                                        <a href="{{ route('tryout.start', $tryout->id) }}" 
                                            class="btn btn-outline-warning btn-sm rounded-pill px-3">
                                            <i class="la la-redo-alt me-1"></i> Ulangi
                                        </a>
                                    @else
                                        <a href="{{ route('tryout.start', $tryout->id) }}" 
                                            class="btn btn-success btn-sm rounded-pill px-3">
                                            <i class="la la-play-circle me-1"></i> Kerjakan
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

@endsection
