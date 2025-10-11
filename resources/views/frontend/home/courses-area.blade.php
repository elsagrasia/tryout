@php
    $tryoutPackages = App\Models\TryoutPackage::where('status',1)->orderBy('id','ASC')->limit(6)->get();
    $categories = App\Models\Category::orderBy('category_name','ASC')->get();
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

    {{-- <div class="card-content-wrapper bg-gray pt-50px pb-120px">
        <div class="container">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="business" role="tabpanel" aria-labelledby="business-tab">
                    <div class="row">
                        @foreach ($tryoutPackages as $TryoutPackage)   
                        <div class="col-lg-4 responsive-column-half">
                            <div class="card card-item card-preview" data-tooltip-content="#tooltip_content_1{{ $TryoutPackage->id }}">
                                <div class="card-image">
                                    
                                </div><!-- end card-image -->
            

                                <div class="card-body">
                                    

                                    <h5 class="card-title"><a href="{{ url('tryout/details/'.$TryoutPackage->id) }}">{{ $TryoutPackage->tryout_name }}</a></h5>

                                    {{-- <p class="card-text"><a href="{{ route('instructor.details',$TryoutPackage->instructor_id) }}">{{ $TryoutPackage['user']['name']}}</a></p> --}}
                                    {{-- <div class="rating-wrap d-flex align-items-center py-2">
                                        
                                    </div><!-- end rating-wrap -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                            
                                        <form action="{{ route('user.join.tryout', $TryoutPackage->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary shadow-sm">
                                                Ikuti Tryout
                                            </button>
                                        </form> --}}
                                        
                                        {{-- <div class="icon-element icon-element-sm shadow-sm cursor-pointer" title="Add to Wishlist" id="{{ $TryoutPackage->id }}" onclick="addToWishList(this.id)" ><i class="la la-heart-o"></i></div> --}}
                                    {{-- </div>
                                </div><!-- end card-body -->
                            </div><!-- end card -->
                        </div><!-- end col-lg-4 -->
                        @endforeach   
                    </div><!-- end row -->
                </div><!-- end tab-pane --> --}}

                {{-- @foreach ($categories as $category)
                <div class="tab-pane fade" id="business{{ $category->id }}" role="tabpanel" aria-labelledby="business-tab">
                    <div class="row">
                        @php
                        $catwiseCourse = App\Models\Course::where('category_id',$category->id)->where('status',1)->orderBy('id','DESC')->get();
                        @endphp                      
          
                            @forelse ($catwiseCourse as $course)
                            <div class="col-lg-4 responsive-column-half">
                                <div class="card card-item card-preview" data-tooltip-content="#tooltip_content_2">
                                    <div class="card-image">
                                        <img class="card-img-top lazy" src="{{ asset($course->course_image) }}" data-src="images/img8.jpg" alt="Card image cap">
                                    </div><!-- end card-image -->
                                    <div class="card-body">
                            <h6 class="ribbon ribbon-blue-bg fs-14 mb-3">{{ $course->label }}</h6>
                            <h5 class="card-title"><a href="course-details.html">{{ $course->course_name }}</a></h5>
                            <p class="card-text"><a href=" ">{{ $course['user']['name'] }}</a></p>
                                        
                                        <div class="d-flex justify-content-between align-items-center">

                                            <div class="icon-element icon-element-sm shadow-sm cursor-pointer" title="Add to Wishlist"><i class="la la-heart-o"></i></div>
                                        </div>
                                    </div><!-- end card-body -->
                                </div><!-- end card -->
                            </div><!-- end col-lg-4 --> 
                                
                            @empty

                            <h5 class="text-danger"> No Course Found </h5>
                                
                            @endforelse
                    </div><!-- end row -->
                </div><!-- end tab-pane -->
                @endforeach
            </div><!-- end tab-content -->
            <div class="more-btn-box mt-4 text-center">
                <a href="course-grid.html" class="btn theme-btn">Browse all Courses <i class="la la-arrow-right icon ml-1"></i></a>
            </div><!-- end more-btn-box -->
        </div><!-- end container -->
    </div><!-- end card-content-wrapper --> --}} 

    <div class="card-content-wrapper bg-gray pt-50px pb-120px">
        <div class="container">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="business" role="tabpanel" aria-labelledby="business-tab">
                    <div class="row">
                        @foreach ($tryoutPackages as $TryoutPackage)   
                        <div class="col-lg-4 responsive-column-half">
                            <div class="card card-item card-preview" data-tooltip-content="#tooltip_content_1{{ $TryoutPackage->id }}">
                                <div class="card-image">
                                    {{-- Jika ada gambar tryout, bisa taruh di sini --}}
                                    {{-- <img src="{{ asset('uploads/tryout/'.$TryoutPackage->image) }}" alt="{{ $TryoutPackage->tryout_name }}" class="card-img-top"> --}}
                                </div><!-- end card-image -->

                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="{{ url('tryout/details/'.$TryoutPackage->id) }}">
                                            {{ $TryoutPackage->tryout_name }}
                                        </a>
                                    </h5>

                                    {{-- <p class="card-text"><a href="{{ route('instructor.details',$TryoutPackage->instructor_id) }}">{{ $TryoutPackage['user']['name']}}</a></p> --}}

                                    {{-- ✅ Tambahan info tryout --}}
                                    <ul class="list-unstyled text-muted mb-3">
                                        <li><i class="la la-clock me-1"></i> Durasi: {{ $TryoutPackage->duration }} menit</li>
                                        <li><i class="la la-list me-1"></i> Total Soal: {{ $TryoutPackage->total_questions }}</li>
                                        {{-- <li><i class="la la-bullhorn me-1"></i> Status: 
                                            <span class="{{ $TryoutPackage->status == 'published' ? 'text-success' : 'text-warning' }}">
                                                {{ ucfirst($TryoutPackage->status) }}
                                            </span>
                                        </li> --}}
                                    </ul>

                                    <div class="d-flex justify-content-between align-items-center">
                                        {{-- <form action="{{ route('user.join.tryout', $TryoutPackage->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary shadow-sm">
                                                Ikuti Tryout
                                            </button>
                                        </form> --}}

                                        <form action="{{ route('user.join.tryout', $TryoutPackage->id) }}" method="POST">
                                            @csrf
                                            <button type="button" class="btn btn-sm btn-primary shadow-sm" onclick="joinTryout({{ $TryoutPackage->id }})">
                                            Ikuti Tryout
                                            </button>

                                        </form>


                                    </div>
                                </div><!-- end card-body -->
                            </div><!-- end card -->
                        </div><!-- end col-lg-4 -->
                        @endforeach   
                    </div><!-- end row -->
                </div><!-- end tab-pane -->
            </div><!-- end tab-content -->
        </div><!-- end container -->
    </div><!-- end card-content-wrapper -->

</section><!-- end courses-area -->


@php
    $courseData = App\Models\Course::get();
@endphp

<!-- tooltip_templates -->
@foreach ($courseData as $item)
     
<div class="tooltip_templates">
    <div id="tooltip_content_1{{ $item->id }}">
        <div class="card card-item">
            <div class="card-body">
                <p class="card-text pb-2">By <a href="teacher-detail.html">{{ $item['user']['name'] }}</a></p>
                <h5 class="card-title pb-1"><a href="course-details.html"> {{ $item->course_name }}</a></h5>
                <div class="d-flex align-items-center pb-1">
                    @if ($item->bestseller == 1)
                    <h6 class="ribbon fs-14 mr-2">Bestseller</h6>
                    @else
                    <h6 class="ribbon fs-14 mr-2">New</h6> 
                    @endif
                   
                    <p class="text-success fs-14 font-weight-medium">Updated<span class="font-weight-bold pl-1">{{ $item->created_at->format('M d Y') }}</span></p>
                </div>
                <ul class="generic-list-item generic-list-item-bullet generic-list-item--bullet d-flex align-items-center fs-14">
                    <li>{{ $item->duration }} total hours</li>
                    <li>{{ $item->label }}</li>
                </ul>
                <p class="card-text pt-1 fs-14 lh-22">{{ $item->prerequisites }}</p>

    @php
       $goals = App\Models\Course_goal::where('course_id',$item->id)->orderBy('id','DESC')->get(); 
    @endphp
                <ul class="generic-list-item fs-14 py-3">
                    @foreach ($goals as $goal)
                    <li><i class="la la-check mr-1 text-black"></i> {{ $goal->goal_name }}</li> 
                    @endforeach
                </ul>
                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn theme-btn flex-grow-1 mr-3" onclick="addToCart({{ $item->id }}, '{{ $item->course_name }}','{{ $item->instructor_id }}','{{ $item->course_name_slug }}' )" ><i class="la la-shopping-cart mr-1 fs-18"></i>Add to Cart</button>
                    <div class="icon-element icon-element-sm shadow-sm cursor-pointer" title="Add to Wishlist"><i class="la la-heart-o"></i></div>
                </div>
            </div>
        </div><!-- end card -->
    </div>
</div><!-- end tooltip_templates -->
@endforeach