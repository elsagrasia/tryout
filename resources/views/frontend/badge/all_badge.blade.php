@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')

<div class="containe-fluid">
    <div class="card border-0 shadow-sm p-4" style="background-color: #f8fcff;">
    <h4 class="fw-bold mb-4">Badges Kamu</h4>
        <div class="row">
            
            @foreach ($badges as $badge)
                @if ($badge->status === 'active')
                    @php
                        $owned = in_array($badge->id, $ownedBadges);
                    @endphp

                    <div class="col-md-4 mb-4">
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
</div>

<style>
/* Badge dimiliki: outline biru muda sangat tipis */
.badge-owned {
background: #cbe7f5;
background: radial-gradient(circle, rgba(203, 231, 245, 1) 0%, rgba(245, 251, 255, 1) 100%);
    border: 1px solid #b3dfffff !important;  /* biru pastel lembut */
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
