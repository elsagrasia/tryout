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
                <a href="#" class="btn theme-btn theme-btn-white">Detail</a>
                <form action="{{ route('user.join.tryout', $package->id) }}" method="POST" class="m-0">
                  @csrf
                  <button type="submit" class="btn theme-btn" onclick="joinTryout({{ $package->id }})">
                    Ikuti Tryout <i class="la la-arrow-right icon ms-1"></i>
                  </button>
                </form>
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


{{-- @php
    $courseData = App\Models\Course::get();
@endphp

<!-- tooltip_templates -->
@foreach ($courseData as $item) --}}
     
{{-- <div class="tooltip_templates">
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
</div><!-- end tooltip_templates --> --}}

{{-- @endforeach --}}