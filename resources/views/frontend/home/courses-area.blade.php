@php
    $tryoutPackages = App\Models\TryoutPackage::where('status',1)->orderBy('id','ASC')->limit(6)->get();
    $categories = App\Models\Category::orderBy('category_name','ASC')->get();
    $tryoutPackages = App\Models\TryoutPackage::where('status','published')->orderBy('id','DESC')->limit(6)->get();
@endphp

<section class="course-area pb-120px">
    <div class="container">
        <div class="section-heading text-center">
            <h5 class="ribbon ribbon-lg mb-2">Choose your desired courses</h5>
            <h2 class="section__title">The world's largest selection of courses</h2>
            <span class="section-divider"></span>
        </div><!-- end section-heading -->
        
        <ul class="nav nav-tabs generic-tab justify-content-center pb-4" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link " id="business-tab" data-toggle="tab" href="#business" role="tab" aria-controls="business" aria-selected="true">All</a>
            </li>
            @foreach ($categories as $category)
                <li class="nav-item">
                     <a class="nav-link" id="business-tab" data-toggle="tab" href="#business{{ $category->id }}" role="tab" aria-controls="business" aria-selected="false">{{ $category->category_name }}</a>
                </li>
            @endforeach
        </ul>
    </div><!-- end container -->

  
<div class="card-content-wrapper bg-gray pt-50px pb-120px">
    <div class="container">
        <div class="row g-4">
        @forelse ($tryoutPackages as $package)
            <div class="col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex flex-column">

                {{-- Judul Tryout --}}
                <h5 class="card-title mb-2">
                    <a href="#" class="text-decoration-none">
                    {{ $package->tryout_name }}
                    </a>
                </h5>

                {{-- Info singkat --}}
                <div class="small text-muted mb-3">
                    <span class="me-3"><i class="bi bi-clock"></i> {{ $package->duration }} min</span>
                    <span><i class="bi bi-question-circle"></i>
                    {{ $package->questions_count ?? $package->questions()->count() }} soal
                    </span>
                </div>

                {{-- Aksi sejajar --}}
                <div class="mt-auto d-flex justify-content-between align-items-center">
                    <a href="#" class="btn theme-btn theme-btn-white w-100">Detail</a>
                    &nbsp;                   
                    <a href="{{ route('user.join.tryout', $package->id) }}" class="btn theme-btn w-100">Ikuti Tryout <i class="la la-arrow-right icon ml-1"></i></a>
              
                </div>

                </div>
            </div>
            </div>
        @empty
            <div class="col-12">
            <div class="text-center text-muted py-5">Belum ada tryout yang tersedia.</div>
            </div>
        @endforelse
        </div>
    </div>
</div>
</section><!-- end courses-area -->


