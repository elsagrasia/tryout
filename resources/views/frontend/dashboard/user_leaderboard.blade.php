@extends('frontend.dashboard.user_dashboard')

@section('userdashboard')
<div class="row justify-content-center text-center pt-50px">
    {{-- Peringkat 2 --}}
    @if(isset($topThree[1]))
    <div class="col-lg-4 col-md-6">
        <div class="info-box info--box info--box-2 hover-s border-blue">
            <img src="{{ !empty($topThree[1]->photo) ? url('upload/user_images/' . $topThree[1]->photo) : url('upload/no_image.jpg') }}"
                 class="rounded-circle" width="80" height="80" alt="{{ $topThree[1]->name }}">
            <h3 class="info__title theme-font-2 font-weight-bold fs-20 lh-28">{{ $topThree[1]->name }}</h3>
            <p class="info__text">Peringkat #{{ $topThree[1]->rank }}</p>
            <p class="info__text text-primary font-weight-bold">{{ $topThree[1]->total_points }} Poin</p>
        </div>
    </div>
    @endif

    {{-- Peringkat 1 (ditengah dan lebih tinggi sedikit) --}}
    @if(isset($topThree[0]))
    <div class="col-lg-4 col-md-6" style="margin-top:-20px;">
        <div class="info-box info--box info--box-2 hover-s border-blue">
            <img src="{{ !empty($topThree[0]->photo) ? url('upload/user_images/' . $topThree[0]->photo) : url('upload/no_image.jpg') }}"
                 class="rounded-circle" width="90" height="90" alt="{{ $topThree[0]->name }}">
            <h3 class="info__title theme-font-2 font-weight-bold fs-22 lh-28">{{ $topThree[0]->name }}</h3>
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
            <p class="info__text">Peringkat #{{ $topThree[2]->rank }}</p>
            <p class="info__text text-primary font-weight-bold">{{ $topThree[2]->total_points }} Poin</p>
        </div>
    </div>
    @endif
</div>

    {{-- ======= CURRENT USER INFO ======= --}}
    <div class="alert alert-info text-center shadow-sm">
        <strong>Posisi Kamu:</strong> #{{ $currentRank }} dengan {{ $currentUser->total_points }} poin
    </div>

    {{-- ======= TABLE LEADERBOARD ======= --}}
    <div class="table-responsive mt-4">
        <table class="table generic-table">
            <thead>
                <tr>
                    <th scope="col" class="text-center">Rank</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Statistik</th>
                    <th scope="col" class="text-center">Poin</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($leaderboard as $user)
                <tr @if($user->id == auth()->id()) class="table-success" @endif>
                    <td class="text-center font-weight-semi-bold">#{{ $user->rank }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ !empty($user->photo) ? url('upload/user_images/' . $user->photo) : url('upload/no_image.jpg') }}"
                                 alt="Avatar" width="40" height="40" class="rounded-circle mr-2">
                            <span class="font-weight-semi-bold">{{ $user->name }}</span>
                            @foreach($mybadges as $userbadge)
                                @if($userbadge->badge && $userbadge->user_id == $user->id)
                                    <img src="{{ asset($userbadge->badge->icon ?? 'upload/badges/default.png') }}"
                                         alt="{{ $userbadge->badge->name }}"
                                         title="{{ $userbadge->badge->name }}"
                                         class="ml-2"
                                         style="width: 30px; height: 30px;">
                                @endif
                            @endforeach
                      
                        </div>
                    </td>
                    <td>
                        <p>{{ $user->total_tryouts }}
                            <span class="text-gray"> total Tryout dengan rata-rata nilai</span>
                            {{ $user->average_score }}
                        </p>
                    </td>
                    <td class="text-center font-weight-semi-bold text-warning">{{ $user->total_points }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
