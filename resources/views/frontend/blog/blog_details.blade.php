@extends('frontend.master')
@section('home')
@section('title')
{{ $blog->post_title  }} | ukom.aja
@endsection

<!-- ================================
    AREA JEJAK HALAMAN (BREADCRUMB)
================================= -->
<section class="breadcrumb-area pt-80px pb-80px pattern-bg">
    <div class="container">
        <div class="breadcrumb-content">
            <div class="section-heading pb-3">
                <h2 class="section__title">{{ $blog->post_title }}</h2>
            </div>
            <ul class="generic-list-item generic-list-item-arrow d-flex flex-wrap align-items-center">
                <li><a href="{{ route('index') }}">Beranda</a></li>
                <li><a href="{{ route('blog') }}">Blog</a></li>
                <li>{{ $blog->post_title }}</li>
            </ul>
            <ul class="generic-list-item generic-list-item-bullet generic-list-item--bullet d-flex align-items-center flex-wrap fs-14 pt-2">
                <li class="d-flex align-items-center">Oleh Admin</li>
                <li class="d-flex align-items-center">{{ $blog->created_at->format('d M Y') }}</li>
            </ul>
        </div><!-- end breadcrumb-content -->
    </div><!-- end container -->
</section><!-- end breadcrumb-area -->
<!-- ================================
    AKHIR AREA JEJAK HALAMAN
================================= -->

<!-- ================================
       AREA BLOG
================================= -->
<section class="blog-area pt-100px pb-100px">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mb-5">
                <div class="card card-item">
                    <div class="card-body">
                        <img src="{{ asset($blog->post_image) }}" alt="Gambar artikel blog" class="img-fluid w-90">
                        <p class="card-text pb-3">{!! $blog->long_descp !!}</p>
                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div><!-- end col-lg-8 -->

            <div class="col-lg-4">
                <div class="sidebar">

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
                    </div><!-- end card -->

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
                                </div><!-- end media -->
                            @endforeach

                            <div class="view-all-course-btn-box">
                                <a href="{{ route('blog') }}" class="btn theme-btn w-100">
                                    Lihat Semua Artikel<i class="la la-arrow-right icon ml-1"></i>
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
       AKHIR AREA BLOG
================================= -->

{{-- TOAST NOTIFIKASI POIN --}}
<div id="point-toast" class="point-toast d-none">
    <div class="sparkle-container"></div>

    <div class="point-toast-icon">
        <i class="la la-coins"></i>
    </div>
    <div class="point-toast-body">
        <div class="point-toast-title">Poin Bertambah!</div>
        <div class="point-toast-text">
            Kamu mendapatkan <span id="point-toast-amount">+0</span> poin karena membaca artikel ini 🎉
        </div>
    </div>

    <button type="button" class="point-toast-close">&times;</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ⭐️ GANTI ID INI jika elemen HTML total poin Anda berbeda
    const TOTAL_POINTS_ELEMENT_ID = 'user-total-points-display'; 
    
    // Kalau user belum login, tidak usah kirim apa-apa
    @if(!Auth::check())
        return;
    @endif

    const blogId   = {{ $blog->id }};
    const delayMs  = 9000; // 9 detik
    let hasSent    = false;

    // ========== SPARKLE ==========
    function spawnSparkles() {
        const container = document.querySelector('.sparkle-container');
        if (!container) return;

        for (let i = 0; i < 4; i++) {
            const s = document.createElement('div');
            s.classList.add('sparkle');

            s.style.left = (10 + Math.random() * 200) + 'px';
            s.style.top  = (5 + Math.random() * 40) + 'px';
            s.style.animationDelay = (Math.random() * 0.3) + 's';

            container.appendChild(s);

            setTimeout(() => s.remove(), 1500);
        }
    }

    let sparkleInterval = null;

    function startSparkleLoop() {
        if (sparkleInterval) return;
        sparkleInterval = setInterval(() => {
            const toast = document.getElementById('point-toast');
            if (!toast || toast.classList.contains('d-none')) {
                clearInterval(sparkleInterval);
                sparkleInterval = null;
                return;
            }
            spawnSparkles();
        }, 1200);
    }

    function stopSparkleLoop() {
        if (sparkleInterval) {
            clearInterval(sparkleInterval);
            sparkleInterval = null;
        }
    }

    // ========== TOAST ==========
    function showPointToast(points) {
        const toast      = document.getElementById('point-toast');
        const amountSpan = document.getElementById('point-toast-amount');
        const closeBtn   = toast.querySelector('.point-toast-close');

        if (!toast || !amountSpan) return;

        amountSpan.textContent = `+${points}`;

        toast.classList.remove('d-none');

        requestAnimationFrame(() => {
            toast.classList.add('show');
        });

        spawnSparkles();
        startSparkleLoop();

        const hideTimer = setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.classList.add('d-none');
                stopSparkleLoop();
            }, 250);
        }, 8000);

        if (closeBtn) {
            closeBtn.onclick = function () {
                clearTimeout(hideTimer);
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.classList.add('d-none');
                    stopSparkleLoop();
                }, 250);
            };
        }
    }

    // ========== KIRIM PING "SUDAH BACA" ==========
    function sendReadPing() {
        if (hasSent) return;
        hasSent = true;

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch("{{ route('blog.markRead', ':id') }}".replace(':id', blogId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({})
        })
        .then(res => res.json())
        .then(data => {
            if (data && data.success) {
                
                // ⭐️ LOGIKA BARU UNTUK MEMPERBARUI POIN DI HEADER
                if (data.total_points) {
                    const totalPointsDisplay = document.getElementById(TOTAL_POINTS_ELEMENT_ID);
                    if (totalPointsDisplay) {
                        // Memperbarui nilai poin di header/dashboard
                        totalPointsDisplay.textContent = data.total_points;
                    }
                }
                
                if (data.points_added && data.points_added > 0) {
                    showPointToast(data.points_added);
                }
            }
        })
        .catch(err => {
            console.error(err);
        });
    }

    // Kirim setelah X detik berada di halaman
    setTimeout(sendReadPing, delayMs);
});
</script>

@endsection
