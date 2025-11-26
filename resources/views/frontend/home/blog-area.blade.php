@php
    $blog = App\Models\BlogPost::latest()->get();
@endphp

<section class="blog-area pb-0 section--padding overflow-hidden">
    <div class="container">
        <div class="section-heading text-center">
            <h5 class="ribbon ribbon-lg mb-2">Tahukah Kamu?</h5>
            <h2 class="section__title">Fakta, Pengetahuan, dan Kasus Menarik untuk Memperkuat Pemahamanmu</h2>
            <span class="section-divider"></span>
        </div><!-- end section-heading -->

        <div class="blog-post-carousel owl-action-styled half-shape mt-30px">

            @foreach ($blog as $item)
            <div class="card card-item">
                <div class="card-image">
                    <a href="{{ url('blog/details/'.$item->post_slug) }}" class="d-block">
                        <img class="card-img-top" src="{{ url($item->post_image) }}" alt="Gambar artikel {{ $item->post_title }}">
                    </a>

                    <div class="course-badge-labels">
                        <div class="course-badge">
                            {{ $item->created_at->format('d M Y') }}
                        </div>
                    </div>
                </div><!-- end card-image -->

                <div class="card-body">
                    <h5 class="card-title">
                        <a href="{{ url('blog/details/'.$item->post_slug) }}">
                            {{ $item->post_title }}
                        </a>
                    </h5>

                    <ul class="generic-list-item generic-list-item-bullet generic-list-item--bullet d-flex align-items-center flex-wrap fs-14 pt-2">
                        <li class="d-flex align-items-center">Oleh Admin</li>
                    </ul>
                </div><!-- end card-body -->
            </div><!-- end card -->
            @endforeach

        </div><!-- end blog-post-carousel -->
    </div><!-- end container -->
</section><!-- end blog-area -->
