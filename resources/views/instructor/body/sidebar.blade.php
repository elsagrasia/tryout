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
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i></div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <div class="parent-icon"><i class='bx bx-home-alt'></i></div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>

        @if ($status === '1')

        <li class="menu-label">Manage</li>
            
        <li>
            <a href="{{route('all.tryout.packages')}}">
                <div class="parent-icon"><i class="bx bx-rocket"></i></div>
                <div class="menu-title">Paket Tryout</div>
            </a>
        </li>
        <li>
            <a href="{{route('all.category')}}">
                <div class="parent-icon"><i class='bx bx-collection'></i></div>
                <div class="menu-title">Kategori</div>
            </a>
        </li>
        <li>
            <a href="{{route('all.question')}}">
                <div class="parent-icon"><i class='bx bx-question-mark'></i></div>
                <div class="menu-title">Daftar Pertanyaan</div>
            </a>
        </li>
        <li>
            <a href="{{route('all.question')}}">
                <div class="parent-icon"><i class='bx bxs-report'></i></div>
                <div class="menu-title">Hasil</div>
            </a>
        </li>
        @else
        @endif
    </ul>
    <!--end navigation-->
</div>