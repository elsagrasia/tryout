@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')

<div class="dashboard-container container-fluid mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <!-- LAPIS 1 -->
            <div class="card border-0 rounded-4 p-4" style="background-color:#e8f2ff; ">
                <h4 class="fw-bold mb-4">Badges Kamu</h4>

                <!-- LAPIS 2 -->
                <div class="card border-0 rounded-4 p-4" 
                     style="background:rgba(255,255,255,0.85); box-shadow:0 2px 8px rgba(0,0,0,0.06);">

                    <div class="row g-4 justify-content-center">

                        @foreach ($badges as $badge)
                            @if ($badge->status === 'active')
                                @php $owned = in_array($badge->id, $ownedBadges); @endphp

                                <!-- PENTING: ganti card-body menjadi col-auto -->
                                <div class="col-auto d-flex justify-content-center">

                                    <!-- LAPIS 3 (card badge) -->
                                    <div class="card text-center border-0 shadow-sm 
                                        {{ $owned ? 'badge-owned' : 'badge-locked' }}"
                                        style="border-radius:15px; width:210px; padding:20px;">

                                        <img src="{{ asset($badge->icon ?? 'upload/badges/default.png') }}"
                                        alt="{{ $badge->name }}"
                                        class="badge-icon"
                                        style="
                                            width: 80px;
                                            height: 80px;
                                            display: block;
                                            margin: 0 auto 12px;
                                            /* object-fit: contain; */
                                            filter: {{ $owned ? 'none' : 'grayscale(100%) brightness(0.4)' }};
                                        ">


                                        <h6 class="fw-bold mb-2 {{ $owned ? 'text-dark' : 'text-muted' }}">
                                            {{ $badge->name }}
                                        </h6>

                                        <p class="small text-secondary mb-0">
                                            {{ $badge->description ?? '-' }}
                                        </p>

                                    </div>

                                </div>

                            @endif
                        @endforeach

                    </div>


                </div>
                <!-- END LAPIS 2 -->

            </div>
            <!-- END LAPIS 1 -->

        </div>
    </div>
</div>


<style>
.badge-owned {
    background:#f0f7ff;
    border:2px solid #cde6ff !important;
    border-radius:15px;
}

.badge-locked {
    background:#f3f4f6; 
    border:1px solid #e1e1e1;
    border-radius:15px;
    box-shadow: inset 0 0 10px rgba(0,0,0,0.05);
}
</style>



@endsection
