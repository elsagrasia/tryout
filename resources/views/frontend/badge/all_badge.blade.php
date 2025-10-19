@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')

<div class="container mt-4 mb-5">
    <h4 class="fw-bold mb-4">Badges Kamu</h4>
    <div class="row g-4">
        @foreach ($badges as $badge)
            @php
                $owned = in_array($badge->id, $ownedBadges);
            @endphp

            <div class="col-md-3 col-sm-6">
                <div class="card text-center border-0 shadow-sm {{ $owned ? 'bg-light' : 'opacity-50' }}"
                     style="border-radius: 15px;">
                    <div class="card-body">
                        <img src="{{ asset($badge->icon ?? 'upload/badges/default.png') }}"
                             alt="{{ $badge->name }}"
                             class="img-fluid mb-3"
                             style="width: 80px; height: 80px; filter: {{ $owned ? 'none' : 'grayscale(100%) brightness(0.4)' }};">
                                 
                        <h6 class="fw-bold {{ $owned ? 'text-success' : 'text-muted' }}">
                            {{ $badge->name }}
                        </h6>
                        <p class="small text-secondary mb-0">{{ $badge->description ?? '-' }}</p>

                    
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection
