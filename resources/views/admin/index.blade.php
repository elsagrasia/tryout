@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <!-- Welcome Section -->
    <div class="card border-0 shadow-sm mb-4" style="background-color: #e8f3ff;"> <!-- biru muda -->
        <div class="card-body text-center py-5">
            <h2 class="fw-bold text-primary mb-2">Selamat Datang, Admin!</h2>
            <p class="text-secondary mb-0">Anda berada di Dashboard Sistem E-Learning ukom.</p>
        </div>
    </div>

    <!-- Statistik Section -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-2 g-4">
        <!-- Total Pengguna -->
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-primary shadow-sm" style="box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary ">Total Pengguna</p>
                            <h4 class="my-1 text-primary">{{ $totalUsers ?? 0 }}</h4>
                        </div>
						<div class="widgets-icons-2 rounded-circle bg-gradient-primary text-primary ms-auto"><i class='bx bxs-group'></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Instructor -->
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-primary shadow-sm" style="box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Instructor</p>
                            <h4 class="my-1 text-primary">{{ $totalInstructors ?? 0 }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-primary text-primary ms-auto"><i class='bx bxs-graduation'></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end row -->
</div>

@endsection
