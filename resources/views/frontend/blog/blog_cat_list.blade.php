@extends('frontend.master')
@section('home')

<!-- ================================
    AREA JEJAK HALAMAN (BREADCRUMB)
================================= -->
<section class="breadcrumb-area section-padding img-bg-2">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content d-flex flex-wrap align-items-center justify-content-between">
            <div class="section-heading">
                <h2 class="section__title text-white">{{ $breadcat->category_name }}</h2>
                <ul class="generic-list-item generic-list-item-white generic-list-item-arrow d-flex flex-wrap align-items-center">
                    <li><a href="{{ route('index') }}">Beranda</a></li>
                    <li>Blog</li>
                    <li>{{ $breadcat->category_name }}</li>
                </ul>
            </div>
        </div><!-- end breadcrumb-content -->
    </div><!-- end container -->
</section><!-- end breadcrumb-area -->
<!-- ================================
    AKHIR BREADCRUMB
================================= -->

<!-- ================================
       AREA BLOG
================================= -->
<section class="blog-area section--padding">
    <div class="container">
        <div class="row">

            <!-- =======================
                LIST ARTIKEL BLOG
            ======================= -->
            <div class="col-lg-8 mb-5">
                <div class="row">

                    @foreach ($blog as $item)
                    <div class="col-lg-6">
                        <div class="card card-item">
                            <div class="card-image">
                                <a href="{{ url('blog/details/'.$item->post_slug) }}" class="d-block">
                                    <img class="card-img-top lazy"
                                         src="{{ asset($item->post_image) }}"
                                         alt="Gambar artikel {{ $item->post_title }}">
                                </a>
                                <div class="course-badge-labels">
                                    <div class="course-badge">
                                        {{ $item->created_at->format('d M Y') }}
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="{{ url('blog/details/'.$item->post_slug) }}">
                                        {{ $item->post_title }}
                                    </a>
                                </h5>
                                <ul class="generic-list-item generic-list-item-bullet generic-list-item--bullet d-flex align-items-center flex-wrap fs-14 pt-2">
                                    <li class="d-flex align-items-center">Oleh Admin</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div><!-- end row -->

                
            </div><!-- end col-lg-8 -->

            <!-- =======================
                SIDEBAR
            ======================= -->
            <div class="col-lg-4">
                <div class="sidebar">

                    <!-- Kategori Blog -->
                    <div class="card card-item">
                        <div class="card-body">
                            <h3 class="card-title fs-18 pb-2">Kategori Blog</h3>
                            <div class="divider"><span></span></div>

                            <ul class="generic-list-item">
                                @foreach ($bcategory as $cat)
                                    <li><a href="{{ url('blog/cat/list/'.$cat->id) }}">{{ $cat->category_name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Artikel Terbaru -->
                    <div class="card card-item">
                        <div class="card-body">
                            <h3 class="card-title fs-18 pb-2">Artikel Terbaru</h3>
                            <div class="divider"><span></span></div>

                            @foreach ($post as $dpost)
                                <div class="media media-card border-bottom border-bottom-gray pb-4 mb-4">
                                    <a href="{{ url('blog/details/'.$dpost->post_slug) }}" class="media-img">
                                        <img class="mr-3" src="{{ asset($dpost->post_image) }}" alt="Gambar artikel terkait">
                                    </a>
                                    <div class="media-body">
                                        <h5 class="fs-15">
                                            <a href="{{ url('blog/details/'.$dpost->post_slug) }}">
                                                {{ $dpost->post_title }}
                                            </a>
                                        </h5>
                                        <span class="d-block lh-18 py-1 fs-14">Admin</span>
                                    </div>
                                </div>
                            @endforeach

                            <div class="view-all-course-btn-box">
                                <a href="{{ route('blog') }}" class="btn theme-btn w-100">
                                    Lihat Semua Artikel <i class="la la-arrow-right icon ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div><!-- end card -->

                </div><!-- end sidebar -->
            </div><!-- end col-lg-4 -->
        </div><!-- end row -->
    </div><!-- end container -->
</section><!-- end blog-area -->
<!-- ================================
       AKHIR BLOG AREA
================================= -->

@endsection
