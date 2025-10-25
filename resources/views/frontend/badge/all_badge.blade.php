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
                    <div class="card text-center border-0 shadow-sm {{ $owned ? 'bg-light' : 'opacity-50' }}"
                         style="border-radius: 15px;">
                        <div class="card-body">
                            <img src="{{ asset($badge->icon ?? 'upload/badges/default.png') }}"
                                 alt="{{ $badge->name }}"
                                 class="img-fluid mb-3"
                                 style="width: 80px; height: 80px; filter: {{ $owned ? 'none' : 'grayscale(100%) brightness(0.4)' }};">
                                    
                            <h6 class="fw-bold mb-2{{ $owned ? 'text-success' : 'text-muted' }}">
                                {{ $badge->name }}
                            </h6>
                            <p class="small text-secondary text-center mb-0" 
                            style="line-height: 1.3; margin-bottom: 4px;">
                            {{ $badge->description ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>

@endsection
