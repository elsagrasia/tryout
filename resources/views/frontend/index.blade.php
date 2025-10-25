@extends('frontend.master')
@section('home')

@section('title')
Easy Learning
@endsection

<!--================================
         START HERO AREA
=================================-->
@include('frontend.home.hero-area')
<!--================================
        END HERO AREA
=================================-->

<!--======================================
        START FEATURE AREA
 ======================================-->
 @include('frontend.home.feature-area')
<!--======================================
       END FEATURE AREA
======================================-->

<!--======================================
        START COURSE AREA
======================================-->
<section id="tryout">
        @include('frontend.home.courses-area') 
</section>
<!--======================================
        END COURSE AREA
======================================-->



<!-- ================================
       START BLOG AREA
================================= -->
<section id="blog">
  @include('frontend.home.blog-area')
</section>
<!-- ================================
       START BLOG AREA
================================= -->

<!--======================================
        START GET STARTED AREA
======================================-->
@include('frontend.home.started-area')
<!-- ================================
       START GET STARTED AREA
================================= -->

<!--======================================
        START SUBSCRIBER AREA
======================================-->
@include('frontend.home.subscriber-area')
<!--======================================
        END SUBSCRIBER AREA
======================================-->

@endsection