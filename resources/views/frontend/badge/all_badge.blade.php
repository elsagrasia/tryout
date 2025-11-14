@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')

<div class="container mb-5">
    <h4 class="fw-bold mb-4">Badges Kamu</h4>
    <div class="row g-4">
        
        @foreach ($badges as $badge)
            @if ($badge->status === 'active')
                @php
                    $owned = in_array($badge->id, $ownedBadges);
                @endphp

                <div class="col-md-3 col-sm-6">
                    <div class="card text-center border-0 shadow-sm 
                        {{ $owned ? 'badge-owned' : 'badge-locked' }}"
                        style="border-radius: 15px;">
                        
                        <div class="card-body">

                            {{-- Icon --}}
                            <img src="{{ asset($badge->icon ?? 'upload/badges/default.png') }}"
                                 alt="{{ $badge->name }}"
                                 class="img-fluid mb-3"
                                 style="width: 80px; height: 80px;
                                        filter: {{ $owned ? 'none' : 'grayscale(100%) brightness(0.4)' }};">

                            {{-- Nama --}}
                            <h6 class="fw-bold mb-2 {{ $owned ? 'text-dark' : 'text-muted' }}">
                                {{ $badge->name }}
                            </h6>

                            {{-- Deskripsi --}}
                            <p class="small text-secondary text-center mb-0" style="line-height:1.3;">
                                {{ $badge->description ?? '-' }}
                            </p>

                        </div>
                    </div>
                </div>
            @endif
        @endforeach

    </div>
</div>

<style>
/* Badge dimiliki: outline biru muda sangat tipis */
.badge-owned {
    background-color: #f6fbffff;  /* biru sangat muda */
    border: 1px solid #d8efffff !important;  /* biru pastel lembut */
    border-radius: 15px;
    transition: 0.2s;
}



/* Badge belum dimiliki */
.badge-locked {
    opacity: 50;
    border-radius: 15px;
}
</style>

@endsection
