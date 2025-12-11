<div class="sidebar-wrapper" data-simplebar="true">
	<div class="sidebar-header">
		<div>
			<h4 class="logo-text">Admin</h4>
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
				<hr>

			<li>
				<a class="has-arrow" href="javascript:;">
					<div class="parent-icon"><i class='bx bx-user-circle'></i>
					</div>
					<div class="menu-title">Kelola Semua Pengguna</div>
				</a>
				<ul>
					<li> <a href="{{ route('all.user') }}"><i class='bx bx-radio-circle'></i>Semua Pengguna</a>
					</li>
					<li> <a href="{{ route('all.instructor') }}"><i class='bx bx-radio-circle'></i>Semua Instructor</a>
					</li>        
				</ul>
			</li>        
			<li>
				<a href="{{ route('points.rules') }}">
					<div class="parent-icon"><i class='bx bx-diamond'></i>
					</div>
					<div class="menu-title">Kelola Poin</div>
				</a>
			</li>        
			<li>
				<a href="{{ route('badges') }}">
					<div class="parent-icon"><i class='bx bx-award'></i>
					</div>
					<div class="menu-title">Kelola Lencana</div>
				</a>
			</li>        

			<li>
				<a class="has-arrow" href="javascript:;">
					<div class="parent-icon"><i class="fadeIn animated bx bx-news"></i></div>
					<div class="menu-title">Kelola Blog</div>
				</a>
				<ul>
					<li> <a href="{{ route('blog.category') }}"><i class='bx bx-radio-circle'></i>Kategori Blog</a></li>
					<li> <a href="{{ route('blog.post') }}"><i class='bx bx-radio-circle'></i>Blog Post</a></li>
				</ul>
			</li>	
		</ul>	
	<!--end navigation-->
</div>