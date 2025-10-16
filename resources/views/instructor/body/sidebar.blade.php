@php
	$id = Auth::user()->id;
	$instructorId = App\Models\User::find($id);
	$status = $instructorId->status;
@endphp

<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('backend/assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">Instructor</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
        </div>
        </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <div class="parent-icon"><i class='bx bx-home-alt'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>

        @if ($status === '1')

        <li class="menu-label">Manage</li>
            
        <li>
            <a href="{{route('all.tryout.packages')}}">
                <div class="parent-icon"><i class="bx bx-rocket"></i>
                </div>
                <div class="menu-title">Paket Tryout</div>
            </a>
        </li>
        <li>
            <a href="{{route('all.category')}}">
                <div class="parent-icon"><i class='bx bx-collection'></i>
                </div>
                <div class="menu-title">Kategori</div>
            </a>
        </li>
        <li>
            <a href="{{route('all.question')}}">
                <div class="parent-icon"><i class='bx bx-question-mark'></i>
                </div>
                <div class="menu-title">Pertanyaan</div>
            </a>
        </li>
        <li>
            <a href="{{route('all.question')}}">
                <div class="parent-icon"><i class='bx bxs-report'></i>
                </div>
                <div class="menu-title">Hasil</div>
            </a>
        </li>
    
        

              
        <li class="menu-label">Charts & Maps</li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bx bx-line-chart"></i>
                </div>
                <div class="menu-title">Charts</div>
            </a>
        </li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bx bx-map-alt"></i>
                </div>
                <div class="menu-title">Maps</div>
            </a>
            <ul>
                <li> <a href="map-google-maps.html"><i class='bx bx-radio-circle'></i>Google Maps</a>
                </li>
                <li> <a href="map-vector-maps.html"><i class='bx bx-radio-circle'></i>Vector Maps</a>
                </li>
            </ul>
        </li>
        @else
        @endif
        <li>
            <a href="https://themeforest.net/user/codervent" target="_blank">
                <div class="parent-icon"><i class="bx bx-support"></i>
                </div>
                <div class="menu-title">Support</div>
            </a>
        </li>
    </ul>
    <!--end navigation-->
</div>