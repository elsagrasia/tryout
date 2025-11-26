@php
    // Ambil semua kategori
    $categories = App\Models\Category::orderBy('category_name','ASC')->get();

    // Ambil semua tryout published (untuk tab "Semua")
    $allTryouts = App\Models\TryoutPackage::where('status','published')
        ->orderBy('id','DESC')
        ->get();

    // Bagi tryout menjadi grup 6 item per slide
    $groupedTryouts = $allTryouts->chunk(6);
@endphp

<section class="course-area pb-0" style="margin-bottom:0; padding-bottom:0;">

    <div class="container" style="margin-top: 40px;">
        <div class="section-heading text-center">
            <h5 class="ribbon ribbon-lg mb-2">Pilih Tryout yang Ingin Diikuti</h5>
            <h2 class="section__title">Tingkatkan Persiapan Uji Kompetensi Dokter</h2>
            <span class="section-divider"></span>
        </div>

        {{-- Tabs kategori --}}
        <ul class="nav nav-tabs generic-tab justify-content-center pb-4" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab">Semua</a>
            </li>

            @foreach ($categories as $category)
                <li class="nav-item">
                    <a class="nav-link" id="cat{{ $category->id }}-tab" data-toggle="tab"
                       href="#cat{{ $category->id }}" role="tab">
                        {{ $category->category_name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Isi tab --}}
    <div class="card-content-wrapper">
        <div class="container">
            <div class="tab-content" id="myTabContent">
            
                {{-- ================== TAB SEMUA (CAROUSEL 6 ITEM / SLIDE) ================== --}}
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">

                    @if ($groupedTryouts->count())
                        <div class="tryout-carousel owl-carousel owl-action-styled half-shape mt-30px">

                            @foreach ($groupedTryouts as $group)
                                <div class="item">
                                    <div class="row gx-4 gy-4">
                                        @foreach ($group as $package)
                                            <div class="col-md-4 mb-4">
                                                <div class="card h-100 shadow-sm border-0">
                                                    <div class="card-body d-flex flex-column">

                                                        <h5 class="card-title mb-2">
                                                            {{ $package->tryout_name }}
                                                        </h5>

                                                        <div class="small text-muted mb-3">
                                                            <span class="mr-1">
                                                                <i class="la la-clock"></i> {{ $package->duration }} min
                                                            </span>
                                                            <span>
                                                                <i class="la la-question-circle"></i>
                                                                {{ $package->questions()->count() }} soal
                                                            </span>
                                                        </div>

                                                        <div class="mt-auto">
                                                            <a href="{{ route('user.join.tryout', $package->id) }}"
                                                               class="btn theme-btn w-100">
                                                                Ikuti Tryout
                                                                <i class="la la-arrow-right icon ml-1"></i>
                                                            </a>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            Belum ada tryout yang tersedia.
                        </div>
                    @endif

                </div>

                {{-- ================== TAB PER KATEGORI (GRID BIASA) ================== --}}
                @foreach ($categories as $category)
                    <div class="tab-pane fade" id="cat{{ $category->id }}" role="tabpanel">

                        <div class="row gx-4 gy-5">

                            @php
                                $catTryouts = App\Models\TryoutPackage::where('status','published')
                                    ->where('category_id', $category->id)
                                    ->orderBy('id','DESC')
                                    ->get();
                            @endphp

                            @forelse ($catTryouts as $package)
                                <div class="col-lg-4 col-md-6 mb-5">
                                    <div class="card h-100 shadow-sm border-0">
                                        <div class="card-body d-flex flex-column">

                                            <h5 class="card-title mb-2">{{ $package->tryout_name }}</h5>

                                            <div class="small text-muted mb-3">
                                                <span class="mr-1">
                                                    <i class="la la-clock"></i> {{ $package->duration }} min
                                                </span>
                                                <span>
                                                    <i class="la la-question-circle"></i>
                                                    {{ $package->questions()->count() }} soal
                                                </span>
                                            </div>

                                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                                <a href="{{ route('user.join.tryout', $package->id) }}"
                                                   class="btn theme-btn w-100">
                                                    Ikuti Tryout
                                                    <i class="la la-arrow-right icon ml-1"></i>
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5">
                                    <img src="{{ asset('frontend/images/no-tryout.webp') }}"
                                         class="img-fluid mb-3" style="max-width: 260px;">
                                    <h4 class="fw-semibold text-dark">Tryout Tidak Ditemukan</h4>
                                    <p class="text-muted mb-0">Belum ada tryout di kategori ini.</p>
                                </div>
                            @endforelse

                        </div>

                    </div>
                @endforeach

            </div>
        </div>
    </div>
</section>


