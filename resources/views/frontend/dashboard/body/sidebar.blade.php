<div class="off-canvas-menu-close dashboard-menu-close icon-element icon-element-sm shadow-sm" data-toggle="tooltip" data-placement="left" title="Close menu">
    <i class="la la-times"></i>
</div><!-- end off-canvas-menu-close -->

<div class="logo-box px-4">
    <a href="index.html" class="logo"><img src="images/logo.png" alt="logo"></a>
</div>

<ul class="generic-list-item off-canvas-menu-list off--canvas-menu-list pt-35px">

    {{-- Dashboard --}}
    <li class="{{ request()->routeIs('dashboard') ? 'page-active' : '' }}">
        <a href="{{ route('dashboard') }}">
            <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px">
                <path d="M0 0h24v24H0V0z" fill="none"/>
                <path d="M19 5v2h-4V5h4M9 5v6H5V5h4m10 8v6h-4v-6h4M9 17v2H5v-2h4M21 3h-8v6h8V3zM11 3H3v10h8V3zm10 8h-8v10h8V11zm-10 4H3v6h8v-6z"/>
            </svg>
            Dashboard
        </a>
    </li>

 
    {{-- My Tryout --}}
    <li class="{{ request()->routeIs('my.tryout') ? 'page-active' : '' }}">
        <a href="{{ route('my.tryout') }}">
            <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px">
                <path d="M0 0h24v24H0V0z" fill="none"/>
                <path d="M8 16h8v2H8zm0-4h8v2H8zm6-10H6
                c-1.1 0-2 .9-2 2v16
                c0 1.1.89 2 1.99 2H18
                c1.1 0 2-.9 2-2V8l-6-6zm4 18H6V4h7v5h5v11z"/>
            </svg>
            Tryout
        </a>
    </li>

    {{-- History Tryout --}}
    <li class="{{ request()->routeIs('tryout.history') ? 'page-active' : '' }}">
        <a href="{{ route('tryout.history') }}">
            <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px">
                <path d="M0 0h24v24H0V0z" fill="none"/>
                <path d="M11,21h-1l1-7H7.5
                c-0.88,0-0.33-0.75-0.31-0.78C8.48,10.94,10.42,7.54,13.01,3h1l-1,7h3.51
                c0.4,0,0.62,0.19,0.4,0.66C12.97,17.55,11,21,11,21z"/>
            </svg>
            Riwayat
        </a>
    </li>

    {{-- Gamifikasi --}}
    <li class="{{ request()->is('user.badges*') ? 'page-active' : '' }}">
        <a href="{{ route('user.badges') }}">
            <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px">
                <path d="M0 0h24v24H0V0z" fill="none"/>
                <path d="M22 9.24l-7.19-.62L12 2
                9.19 8.63 2 9.24l5.46 4.73L5.82 21
                12 17.27 18.18 21l-1.63-7.03L22 9.24z
                M12 15.4l-3.76 2.27 1-4.28-3.32-2.88
                4.38-.38L12 6.1l1.71 4.04 4.38.38
                -3.32 2.88 1 4.28L12 15.4z"/>
            </svg>
            Badge
        </a>
    </li>

{{-- Leaderboard --}}
<li class="{{ request()->routeIs('user.leaderboard') ? 'page-active' : '' }}">
    <a href="{{ route('user.leaderboard') }}">
        <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
            <path d="M7 17V9h10v8h5v2H2v-2h5zM9 11v6h6v-6H9zm3-8c1.1 0 2 .9 2 2h2a4 4 0 0 0-8 0h2c0-1.1.9-2 2-2z"/>
        </svg>
        Peringkat
    </a>
</li>

</ul>


