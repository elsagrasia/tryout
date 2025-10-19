@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')

<div class="container py-4">
    <h4 class="mb-3">Riwayat Tryout</h4>
    
    @if($histories->isEmpty())
        <div class="text-center text-muted mt-5">
            <i class="la la-folder-open fs-40 mb-3 d-block"></i>
            <p>Belum ada riwayat tryout.</p>
        </div>
    @else
    @foreach ($histories as $history)
    @php
    $tryout = $history->tryoutPackage;
    $tanggal = $history->updated_at->format('d M Y'); // contoh: 13 Okt 2025
    
    @endphp
    <a href="{{ route('tryout.explanation', $tryout->id) }}" class="card card-item hover-s mb-3">
                <div class="card-body d-flex flex-wrap align-items-center justify-content-between">
                    <div class="pr-4">
                        <h5 class="card-title">{{ $tryout->tryout_name }}</h5>
                        <small>
                            <i class="la la-calendar mr-1"></i> {{ $tanggal }}
                        </small>
                        <p class="card-text text-gray">{{ $tryout->total_questions }} Soal • {{ $tryout->duration }} Menit</p>
                    </div>
                    <div class="btn-box">
                        <span class="fs-18 text-black">Lihat Hasil<i class="la la-arrow-right ml-1"></i></span>
                    </div><!-- end btn-box -->
                </div><!-- end card-body -->
            </a><!-- end card -->
   
            @endforeach
        </div>
    @endif
</div>

@endsection
