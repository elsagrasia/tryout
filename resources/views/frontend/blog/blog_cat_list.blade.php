@extends('frontend.master')
@section('home')

<!-- ================================
    START BREADCRUMB AREA
================================= -->
<section class="breadcrumb-area section-padding img-bg-2">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content d-flex flex-wrap align-items-center justify-content-between">
            <div class="section-heading">
                <h2 class="section__title text-white">{{ $breadcat->category_name }}</h2>
            </div>
            <ul class="generic-list-item generic-list-item-white generic-list-item-arrow d-flex flex-wrap align-items-center">
                <li><a href="index.html">Home</a></li>
                <li>Blog</li>
                <li>{{ $breadcat->category_name }}</li>
            </ul>
        </div><!-- end breadcrumb-content -->
    </div><!-- end container -->
</section><!-- end breadcrumb-area -->
<!-- ================================
    END BREADCRUMB AREA
================================= -->

<!-- ================================
       START BLOG AREA
================================= -->
<section class="blog-area section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mb-5">
                <div class="row">
                  
                  
   @foreach ($blog as $item)
    <div class="col-lg-6">
        <div class="card card-item">
            <div class="card-image">
                <a href="blog-single.html" class="d-block">
                    <img class="card-img-top lazy" src="{{ asset($item->post_image)  }}" data-src="images/img8.jpg" alt="Card image cap">
                </a>
                <div class="course-badge-labels">
                    <div class="course-badge">{{ $item->created_at->format('M d Y') }}</div>
                </div>
            </div><!-- end card-image -->
            <div class="card-body">
                <h5 class="card-title"><a href="{{ url('blog/details/'.$item->post_slug) }}">{{ $item->post_title }}</a></h5>
                <ul class="generic-list-item generic-list-item-bullet generic-list-item--bullet d-flex align-items-center flex-wrap fs-14 pt-2">
                    <li class="d-flex align-items-center">ByAdmin </a></li>                  
                </ul>
              
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div><!-- end col-lg-6 --> 
    @endforeach       


                     
                </div><!-- end row -->
                <div class="text-center pt-3">
                    <nav aria-label="Page navigation example" class="pagination-box">
                        <ul class="pagination justify-content-center">
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Previous">
                                    <span aria-hidden="true"><i class="la la-arrow-left"></i></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Next">
                                    <span aria-hidden="true"><i class="la la-arrow-right"></i></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <p class="fs-14 pt-2">Showing 1-10 of 56 results</p>
                </div>
            </div><!-- end col-lg-8 -->
            <div class="col-lg-4">
                <div class="sidebar">
                     
                    <div class="card card-item">
                        <div class="card-body">
                            <h3 class="card-title fs-18 pb-2">Blog Category</h3>
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
                            <h3 class="card-title fs-18 pb-2">Recent Posts</h3>
                            <div class="divider"><span></span></div>
                           
                           @foreach ($post as $dpost)
                            <div class="media media-card border-bottom border-bottom-gray pb-4 mb-4">
                                <a href="{{ url('blog/details/'.$dpost->post_slug) }}" class="media-img">
                                    <img class="mr-3" src="{{ asset($dpost->post_image) }}" alt="Related course image">
                                </a>
                                <div class="media-body">
                                    <h5 class="fs-15"><a href="{{ url('blog/details/'.$dpost->post_slug) }}">{{ $dpost->post_title }}</a></h5>
                                    <span class="d-block lh-18 py-1 fs-14">Admin </span> 
                                </div>
                            </div><!-- end media --> 
                               
                            @endforeach

                            <div class="view-all-course-btn-box">
                                <a href="{{route('blog')}}" class="btn theme-btn w-100">View All Posts <i class="la la-arrow-right icon ml-1"></i></a>
                            </div>
                        </div>
                    </div><!-- end card -->
          
                </div><!-- end sidebar -->
            </div><!-- end col-lg-4 -->
        </div><!-- end row -->
    </div><!-- end container -->
</section><!-- end blog-area -->
<!-- ================================
       START BLOG AREA
================================= -->





@endsection