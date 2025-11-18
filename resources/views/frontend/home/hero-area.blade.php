<section class="hero-area">
    <div class="hero-slider owl-action-styled">
        <div class="hero-slider-item hero-bg-1">
            <div class="container">
                <div class="hero-content">
                    <div class="section-heading">
                        @guest
                            <h2 class="section__title text-white fs-65 lh-70">
                                Persiapkan <br> Uji Kompetensi Dokter
                            </h2>
                        @else
                            <h2 class="section__title text-white fs-65 lh-70">
                                Halo, {{ Auth::user()->name }}! <br>
                                <span class="fs-45">Yuk mulai kerjakan tryout</span>
                            </h2>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
