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
        <div class="list-group">
            @foreach ($histories as $history)
                @php
                    $tryout = $history->tryoutPackage;
                    $tanggal = $history->updated_at->format('d M Y'); // contoh: 13 Okt 2025
             
                @endphp

                <div class="list-group-item mb-2 d-flex justify-content-between align-items-center border rounded shadow-sm">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                            style="width:45px; height:45px;">
                            <i class="la la-check fs-5"></i>
                        </div>
                        <div style="margin-left: 15px;">
                            <h6 class="mb-1 fw-bold">{{ $tryout->tryout_name }}</h6>
                            <small class="d-block text-muted">
                                {{ $tanggal }}
                            </small>
                      
                            <small class="text-muted">
                                {{ $tryout->total_questions }} Soal • {{ $tryout->duration }} Menit
                            </small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <a href="{{ route('tryout.explanation', $tryout->id) }}" 
                           class="btn btn-sm btn-info" 
                           style="margin-right: 8px;">
                            Lihat Hasil
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection
