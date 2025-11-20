
<header class="header-menu-area">
    <div class="header-menu-content  pr-30px pl-30px bg-white shadow-sm">
        <div class="container-fluid">
            <div class="main-menu-content">
                <div class="row align-items-center">
                    <!-- Logo -->
                    <div class="col-lg-2">
                        <div class="logo-box">
                            <a href="{{ url('/') }}" class="logo">
                                <img src="{{ asset('upload/logo/logo.jpg') }}" alt="logo" style="height: 40px; width: auto;">
                            </a>
                            <div class="user-btn-action">
                                <div class="off-canvas-menu-toggle main-menu-toggle icon-element icon-element-sm shadow-sm"
                                    data-toggle="tooltip" data-placement="top" title="Main menu">
                                    <i class="la la-bars"></i>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col-lg-2 -->

                    <!-- Navigation -->
                    <div class="col-lg-10">
                        <div class="menu-wrapper pt-20px pb-20px ">


                            {{-- ==========================================
                                AUTH CHECK (LOGIN / GUEST)
                            =========================================== --}}
                            @php
                                $profileData = Auth::user();
                            @endphp

                            @auth
                                <!-- User Logged In -->
                                 @if(Auth::check())
                                <div class="user-points d-flex align-items-center mr-3 border rounded px-3 py-1 bg-light pr-4" style="gap:6px;">
                                    <i class="la la-coins text-warning fs-18"></i>
                                    <span class="fw-bold text-dark" style="font-size: 15px;">
                                        {{ Auth::user()->total_points ?? 0 }}
                                    </span>
                                </div>
                                @endif
                                    <div class="shop-cart user-profile-cart d-flex align-items-center nav-right-button border-left border-left-gray pl-4 mr-3" style="height:40px;">

                                    <ul>
                                        <li>
                                            <div class="shop-cart-btn">
                                            <div class="avatar-sm" >
                                                <img class="img-fluid" 
                                                    src="{{ !empty($profileData->photo)
                                                        ? url('upload/user_images/' . $profileData->photo)
                                                        : url('upload/no_image.jpg') }}"
                                                    alt="Avatar image"
                                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                            </div>

                                            </div>

                                            <ul class="cart-dropdown-menu after-none p-0 notification-dropdown-menu">
                                                <li class="menu-heading-block d-flex align-items-center">
                                                    <a href="#" class="avatar-sm flex-shrink-0 d-block">
                                                        <img class="img-fluid" 
                                                            src="{{ !empty($profileData->photo)
                                                                ? url('upload/user_images/' . $profileData->photo)
                                                                : url('upload/no_image.jpg') }}"
                                                            alt="Avatar image"
                                                            style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                                    </a>
                                                    <div class="ml-2">
                                                        <h4><a href="#" class="text-black">{{ $profileData->name }}</a></h4>
                                                        <span class="d-block fs-14 lh-20">{{ $profileData->email }}</span>
                                                    </div>
                                                </li>

                                                <li>
                                                    <ul class="generic-list-item">
                                                        <li>
                                                            <a href="{{ route('user.dashboard') }}">
                                                                <i class="la la-sign-in mr-1"></i> Dashboard
                                                            </a>
                                                        </li>
                                                        <li><div class="section-block"></div></li>
                                                        <li>
                                                            <a href="{{ route('user.profile') }}">
                                                                <i class="la la-user mr-1"></i> Profil Saya
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('user.change.password') }}">
                                                                <i class="la la-edit mr-1"></i> Ubah Password
                                                            </a>
                                                        </li>
                                                        <li><div class="section-block"></div></li>
                                                        <li>
                                                            <a href="{{ route('user.logout') }}">
                                                                <i class="la la-power-off mr-1"></i> Logout
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div><!-- end shop-cart -->
                            @else
                                <!-- Guest (Belum Login) -->
                                <div class="nav-right-button border-left border-left-gray pl-4 mr-3">
                                    <ul class="generic-list-item">
                                        <li>
                                            <a href="{{ route('login') }}" class="btn theme-btn theme-btn-sm lh-26 theme-btn-transparent mr-2">
                                                <i class="la la-sign-in mr-1"></i> Login
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('register') }}" class="btn theme-btn theme-btn-sm lh-26 text-white">
                                                <i class="la la-user-plus mr-1"></i> Register
                                            </a>
                                        </li>
                                    </ul>
                                </div><!-- end nav-right-button -->
                            @endauth

                            <!-- Theme Picker -->
                            <div class="theme-picker d-flex align-items-center">
                                <button class="theme-picker-btn light-mode-btn" title="Light mode">
                                    <svg id="sun" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="5"></circle>
                                        <line x1="12" y1="1" x2="12" y2="3"></line>
                                        <line x1="12" y1="21" x2="12" y2="23"></line>
                                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                                        <line x1="1" y1="12" x2="3" y2="12"></line>
                                        <line x1="21" y1="12" x2="23" y2="12"></line>
                                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                                    </svg>
                                </button>
                            </div>
                        </div><!-- end menu-wrapper -->
                    </div><!-- end col-lg-10 -->
                </div><!-- end row -->
            </div>
        </div><!-- end container -->
    </div><!-- end header-menu-content -->
</header><!-- end header-menu-area -->



