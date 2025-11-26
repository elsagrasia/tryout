@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')

<div class="row justify-content-center text-center pt-50px">
    {{-- Peringkat 2 --}}
    @if(isset($topThree[1]))
    <div class="col-lg-4 col-md-6" >
        <div class="info-box info--box info--box-2 hover-s border-blue">
            <img src="{{ !empty($topThree[1]->photo) ? url('upload/user_images/' . $topThree[1]->photo) : url('upload/no_image.jpg') }}"
                 class="rounded-circle" width="80" height="80" alt="{{ $topThree[1]->name }}">
            <h3 class="info__title theme-font-2 font-weight-bold fs-20 lh-28">{{ $topThree[1]->name }}</h3>
                            @foreach($topThree[1]->badges as $badge)
                                <img src="{{ asset($badge->icon ?? 'upload/badges/default.png') }}"
                                     alt="{{ $badge->name }}"
                                     title="{{ $badge->name }}"
                                     style="width:30px;height:30px;">
                            @endforeach
            <p class="info__text">Peringkat #{{ $topThree[1]->rank }}</p>
            <p class="info__text text-primary font-weight-bold">{{ $topThree[1]->total_points }} Poin</p>
        </div>
    </div>
    @endif

    {{-- Peringkat 1 (ditengah dan lebih tinggi sedikit) --}}
    @if(isset($topThree[0]))
    <div class="col-lg-4 col-md-6" style="margin-top:-20px;"> 
        <div class="info-box info--box info--box-2 hover-s border-blue" style="position: relative;">
            <div style="position: relative; display: inline-block; text-align: center;">
                
                <img src="data:image/png;base64,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" alt="crown-emoji"
                    style="
                        position: absolute;
                        top: -32px; /* Sesuaikan jarak ke atas */
                        left: 50%;
                        transform: translateX(-50%);
                        z-index: 10;
                        width: 50px; /* Ukuran Mahkota */
                        height: 50px; /* Ukuran Mahkota */
                    ">
                
                <img src="{{ !empty($topThree[0]->photo) ? url('upload/user_images/' . $topThree[0]->photo) : url('upload/no_image.jpg') }}"
                    class="rounded-circle" width="90" height="90" alt="{{ $topThree[0]->name }}" style="display: block;">
            </div>
            
            <h3 class="info__title theme-font-2 font-weight-bold fs-22 lh-28">{{ $topThree[0]->name }}</h3>
            
            @foreach($topThree[0]->badges as $badge)
                <img src="{{ asset($badge->icon ?? 'upload/badges/default.png') }}"
                        alt="{{ $badge->name }}"
                        title="{{ $badge->name }}"
                        style="width:30px;height:30px;">
            @endforeach
            
            <p class="info__text">Peringkat #{{ $topThree[0]->rank }}</p>
            <p class="info__text text-primary font-weight-bold">{{ $topThree[0]->total_points }} Poin</p>
        </div>
    </div>
    @endif

    {{-- Peringkat 3 --}}
    @if(isset($topThree[2]))
    <div class="col-lg-4 col-md-6">
        <div class="info-box info--box info--box-2 hover-s border-blue">
            <img src="{{ !empty($topThree[2]->photo) ? url('upload/user_images/' . $topThree[2]->photo) : url('upload/no_image.jpg') }}"
                 class="rounded-circle" width="80" height="80" alt="{{ $topThree[2]->name }}">
            <h3 class="info__title theme-font-2 font-weight-bold fs-20 lh-28">{{ $topThree[2]->name }}</h3>
            @foreach($topThree[2]->badges as $badge)
                <img src="{{ asset($badge->icon ?? 'upload/badges/default.png') }}"
                        alt="{{ $badge->name }}"
                        title="{{ $badge->name }}"
                        style="width:30px;height:30px;">
            @endforeach
            <p class="info__text">Peringkat #{{ $topThree[2]->rank }}</p>
            <p class="info__text text-primary font-weight-bold">{{ $topThree[2]->total_points }} Poin</p>
        </div>
    </div>
    @endif
</div>

    {{-- CURRENT USER --}}
    <div class="alert alert-info text-center shadow-sm mt-4" 
        style="border-radius: 15px !important;">
        <strong>Posisi Kamu:</strong> #{{ $currentRank }} dengan {{ $currentUser->total_points }} poin
    </div>

    {{-- TABLE LEADERBOARD --}}
    <div class="table-responsive mt-4">
        <table class="table generic-table">
            <thead>
                <tr>
                    <th class="text-center">Rank</th>
                    <th>Nama</th>
                    <th>Statistik</th>
                    <th class="text-center">Poin</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($leaderboard as $user)
                <tr @if($user->id == auth()->id()) class="table-success" @endif>
                    <td class="text-center font-weight-semi-bold">#{{ $user->rank }}</td>

                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ !empty($user->photo) ? url('upload/user_images/' . $user->photo) : url('upload/no_image.jpg') }}"
                                 width="40" height="40" class="rounded-circle mr-2">
                            <span class="font-weight-semi-bold mr-2">{{ $user->name }}</span>

                            @foreach($user->badges as $badge)
                                <img src="{{ asset($badge->icon ?? 'upload/badges/default.png') }}"
                                     alt="{{ $badge->name }}"
                                     title="{{ $badge->name }}"
                                     style="width:30px;height:30px;">
                            @endforeach
                        </div>
                    </td>

                    <td>
                        <p>{{ $user->total_tryouts }}
                            <span class="text-gray"> total Tryout dengan rata-rata nilai</span>
                            {{ $user->average_score }}
                        </p>
                    </td>

                    <td class="text-center font-weight-semi-bold text-warning">
                        {{ $user->total_points }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>


@endsection
