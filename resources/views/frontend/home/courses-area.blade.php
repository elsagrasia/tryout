@php
    $tryoutPackages = App\Models\TryoutPackage::where('status',1)->orderBy('id','ASC')->limit(6)->get();
    $categories = App\Models\Category::orderBy('category_name','ASC')->get();
    $tryoutPackages = App\Models\TryoutPackage::where('status','published')->orderBy('id','DESC')->limit(6)->get();
@endphp


<section class="course-area pb-0" style="margin-bottom:0; padding-bottom:0;">

    <div class="container" style="margin-top: 40px;">
        <div class="section-heading text-center ">
            <h5 class="ribbon ribbon-lg mb-2">Pilih Tryout yang Ingin Diikuti</h5>
            <h2 class="section__title">Tingkatkan Persiapan Uji Kompetensi Dokter</h2>
            <span class="section-divider"></span>
        </div><!-- end section-heading -->

        {{-- Tabs kategori --}}
        <ul class="nav nav-tabs generic-tab justify-content-center pb-4" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab">Semua</a>
            </li>

            @foreach ($categories as $category)
                <li class="nav-item">
                    <a class="nav-link" id="cat{{ $category->id }}-tab" data-toggle="tab" href="#cat{{ $category->id }}" role="tab">
                        {{ $category->category_name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div><!-- end container -->

    {{-- Isi tab --}}
    <div class="card-content-wrapper bg-gradient pt-30px pb-40px">

        <div class="container">
            <div class="tab-content" id="myTabContent">

                {{-- Semua --}}
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                    <div class="row gx-4 gy-5">
                        @php
                            $tryoutPackages = App\Models\TryoutPackage::where('status','published')
                                ->orderBy('id','DESC')->get();
                        @endphp

                        @forelse ($tryoutPackages as $package)
                            <div class="col-lg-4 col-md-6 mb-5">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title mb-2">{{ $package->tryout_name }}</h5>
                                        <div class="small text-muted mb-3">
                                            <span class="mr-1"><i class="las la-clock"></i> {{ $package->duration }} min</span>
                                            <span><i class="las la-question-circle"></i> {{ $package->questions()->count() }} soal</span>
                                        </div>
                                        <div class="mt-auto d-flex justify-content-between align-items-center">
                                            <a href="#" class="btn theme-btn theme-btn-white w-100">Detail</a>
                                            &nbsp;
                                            <a href="{{ route('user.join.tryout', $package->id) }}" class="btn theme-btn w-100">Ikuti Tryout <i class="la la-arrow-right icon ml-1"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted py-5">Belum ada tryout yang tersedia.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Berdasarkan kategori --}}
                @foreach ($categories as $category)
                    <div class="tab-pane fade" id="cat{{ $category->id }}" role="tabpanel" aria-labelledby="cat{{ $category->id }}-tab">
                        <div class="row gx-4 gy-5">
                            @php
                                $catTryouts = App\Models\TryoutPackage::where('status', 'published')
                                ->whereHas('questions', function($q) use ($category) {
                                    $q->where('category_id', $category->id);
                                })
                                ->orderBy('id', 'DESC')
                                ->get();
                            @endphp

                            @forelse ($catTryouts as $package)
                                <div class="col-lg-4 col-md-6 mb-5">
                                    <div class="card h-100 shadow-sm border-0">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title mb-2">{{ $package->tryout_name }}<i class="las la-clock"></i></h5>
                                            <div class="small text-muted mb-3">
                                                <i class="las la-clock"></i>
                                                <span class="mr-3"><i class="la la-clock-o"></i> {{ $package->duration }} min</span>
                                                <span><i class="bi bi-question-circle"></i> {{ $package->questions()->count() }} soal</span>
                                            </div>

                                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                               <div class="tryout-btn-group">
                                                    <a href="#" class="btn-white-tryout w-100">Detail</a>
                                                    <a href="{{ route('user.join.tryout', $package->id) }}" class="btn-primary-tryout w-100">
                                                        Ikuti Tryout →
                                                    </a>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5">
                                   <img src="{{ asset('frontend/images/no-tryout.webp') }}" alt="Tidak ada Tryout" class="img-fluid mb-3" style="max-width: 260px;">
                                    <h4 class="fw-semibold text-dark">Tryout Tidak Ditemukan</h4>
                                    <p class="text-muted mb-0">Belum ada tryout di kategori ini. Silakan cek kembali nanti.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
