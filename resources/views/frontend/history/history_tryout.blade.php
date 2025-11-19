@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')

<div class="container-fluid">
    <div class="row justify-content-center">
        <!-- Menggunakan col-12 untuk membuat card luar penuh lebar -->
        <div class="col-12">

            {{-- Card besar agar rapi dan sejajar sidebar --}}
            <div class="card border-0 shadow-sm p-4" style="background-color: #f8fcff;">
                
                {{-- Judul --}}
                <div class="mb-4">
                    <h3 class="fs-22 fw-semibold">Riwayat Tryout</h3>
                </div>

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
                                $tanggal = $history->updated_at->format('d M Y');
                                $score = $history->score ?? 0;

                                $color = $score >= 85 ? 'text-success' 
                                        : ($score >= 75 ? 'text-primary' 
                                        : ($score >= 65 ? 'text-warning' 
                                        : 'text-danger'));
                            @endphp

                            <div class="list-group-item mb-2 d-flex justify-content-between align-items-center border rounded shadow-sm">
                                    
                                {{-- Kiri --}}
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                         style="width:45px; height:45px;">
                                        <i class="la la-file-alt fs-5"></i>
                                    </div>

                                    <div style="margin-left: 15px;">
                                        <h6 class="mb-1 fw-bold text-dark">{{ $tryout->tryout_name }}</h6>

                                        <small class="text-muted">
                                            <i class="la la-calendar me-1"></i>{{ $tanggal }}
                                        </small><br>

                                        <small class="text-secondary">
                                            {{ $tryout->total_questions }} Soal • {{ $tryout->duration }} Menit
                                        </small>
                                    </div>
                                </div>

                                {{-- Kanan --}}
                                <div class="text-end d-flex flex-column align-items-center justify-content-center">
                                    <small class="text-muted mb-1">Skor Akhir</small>

                                    <div class="fw-bold {{ $color }} mb-3" style="font-size: 2rem;">
                                        {{ $score }}
                                    </div>

                                    <a href="{{ route('tryout.explanation', $tryout->id) }}" 
                                        class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        Lihat Hasil <i class="la la-arrow-right ms-1"></i>
                                    </a>
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
