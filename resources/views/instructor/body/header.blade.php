<header>
	<div class="topbar d-flex align-items-center">
		<nav class="navbar navbar-expand gap-3">
			<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
			</div>

				<div class="position-relative search-bar d-lg-block d-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
				<input class="form-control px-5" disabled type="search" placeholder="Search">
				<span class="position-absolute top-50 search-show ms-3 translate-middle-y start-0 top-50 fs-5"><i class='bx bx-search'></i></span>
				</div>


				<div class="top-menu ms-auto">
				<ul class="navbar-nav align-items-center gap-1">
					<li class="nav-item mobile-search-icon d-flex d-lg-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
						<a class="nav-link" href="avascript:;"><i class='bx bx-search'></i>
						</a>
					</li>
					<li class="nav-item dropdown dropdown-laungauge d-none d-sm-flex">
						<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="avascript:;" data-bs-toggle="dropdown"><img src="assets/images/county/02.png" width="22" alt="">
						</a>
					
					</li>
					
					@php
						$ncount = Auth::user()->unreadNotifications()->count();
					@endphp							

				</ul>
			</div>
			@php
				$id = Auth::user()->id;
				$profileData = App\Models\User::find($id);
			@endphp
			
			<div class="user-box dropdown px-3">
				<a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
					<img src="{{ (!empty($profileData->photo)) ? url('upload/instructor_images/'.$profileData->photo) : url('upload/no_image.jpg') }}" alt="Instructor" class="user-img"> 
					
					<div class="user-info">
						<p class="user-name mb-0">{{ $profileData->name }} </p>
						<p class="designattion mb-0">{{ $profileData->email }}</p>
					</div>
				</a>
				<ul class="dropdown-menu dropdown-menu-end">
					<li><a class="dropdown-item d-flex align-items-center" href="{{ route('instructor.profile') }}"><i class="bx bx-user fs-5"></i><span>Profile</span></a>
					</li>
					<li><a class="dropdown-item d-flex align-items-center" href="{{ route('instructor.change.password') }}"><i class="bx bx-cog fs-5"></i><span>Change Password</span></a>
					</li>
					<li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i class="bx bx-home-circle fs-5"></i><span>Dashboard</span></a>
					</li>
					<li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i class="bx bx-dollar-circle fs-5"></i><span>Earnings</span></a>
					</li>
					<li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i class="bx bx-download fs-5"></i><span>Downloads</span></a>
					</li>
					<li>
						<div class="dropdown-divider mb-0"></div>
					</li>
					<li><a class="dropdown-item d-flex align-items-center" href="{{ route('instructor.logout') }}"><i class="bx bx-log-out-circle"></i><span>Logout</span></a>
					</li>
				</ul>
			</div>
		</nav>
	</div>
</header>

<script>
    function markNotificationRead(notificationId){

        fetch('/mark-notification-as-read/'+notificationId,{
            method: 'POST',
            headers: {
                'Content-Type' : 'application/josn',
                'X-CSRF-TOKEN' : '{{ csrf_token() }}'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('notification-count').textContent = data.count;
        })
        .catch(error => {
            console.log('Error',error)
        });
    }
</script>