<header class="header-menu-area">
    <div class="header-menu-content  pr-30px pl-30px bg-white shadow-sm">
        <div class="container-fluid">
            <div class="main-menu-content">
                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <div class="logo-box logo--box">
                            <a href="index.html" class="logo"><img src="{{ asset('frontend/images/logo.png') }}" alt="logo"></a>
                            <div class="user-btn-action">
                                
                                <div class="search-menu-toggle icon-element icon-element-sm shadow-sm mr-2" data-toggle="tooltip" data-placement="top" title="Search">
                                    <i class="la la-search"></i>
                                </div>
                                <div class="off-canvas-menu-toggle cat-menu-toggle icon-element icon-element-sm shadow-sm mr-2" data-toggle="tooltip" data-placement="top" title="Category menu">
                                    <i class="la la-th-large"></i>
                                </div>
                                <div class="off-canvas-menu-toggle main-menu-toggle icon-element icon-element-sm shadow-sm" data-toggle="tooltip" data-placement="top" title="Main menu">
                                    <i class="la la-bars"></i>
                                </div>
                            </div>  
                        </div><!-- end logo-box -->
                        <div class="menu-wrapper">                      
                            <div class="nav-right-button d-flex align-items-center">
                                @php
                                    $id = Auth::user()->id;
                                    $profileData = App\Models\User::find($id);
                                @endphp 
                                    <div class="user-points d-flex align-items-center mr-3 border rounded px-3 py-1 bg-light "data-toggle="tooltip"  title="total poin" style="gap:3px;">
                                        <i class="la la-gem text-warning fs-23"></i>
                                        <span class="fw-bold text-dark" style="font-size: 15px;">
                                            {{ Auth::user()->total_points ?? 0 }}
                                        </span>
                                    </div>
                                    <div class="shop-cart user-profile-cart">
                                        <ul>
                                            <li>
                                                <div class="shop-cart-btn">
                                                    <div class="avatar-sm">
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
                                                        <a href="teacher-detail.html" class="avatar-sm flex-shrink-0 d-block">
                                                            <img class="img-fluid" 
                                                            src="{{ !empty($profileData->photo)
                                                                ? url('upload/user_images/' . $profileData->photo)
                                                                : url('upload/no_image.jpg') }}"
                                                            alt="Avatar image"
                                                            style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                                        </a>
                                                        <div class="ml-2">
                                                            <h4><a href="teacher-detail.html" class="text-black">{{ $profileData->name }}</a></h4>
                                                            <span class="d-block fs-14 lh-20">{{ $profileData->email }}</span>
                                                        </div>
                                                    </li>

                                                    <li>
                                                        <ul class="generic-list-item">
                                                    
                                                            <li><div class="section-block"></div></li>
                                                            <li>
                                                                <a href="{{route('user.profile')}}">
                                                                    <i class="la la-user mr-1"></i> Profil Saya
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="{{route('user.change.password')}}">
                                                                    <i class="la la-edit mr-1"></i> Ubah Password
                                                                </a>
                                                            </li>
                                                            <li><div class="section-block"></div></li>
                                                            <li>
                                                                <a href="{{route('user.logout')}}">
                                                                    <i class="la la-power-off mr-1"></i> Logout
                                                                </a>
                                                            </li>
                                                        
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div><!-- end shop-cart -->
                            </div><!-- end nav-right-button -->
                        </div><!-- end menu-wrapper -->
                    </div><!-- end col-lg-10 -->
                </div><!-- end row -->
            </div>
        </div><!-- end container-fluid -->
    </div><!-- end header-menu-content -->

 
</header><!-- end header-menu-area -->
