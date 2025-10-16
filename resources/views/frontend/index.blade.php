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
@include('frontend.home.courses-area') 
<!--======================================
        END COURSE AREA
======================================-->

<div class="section-block"></div>

<!--======================================
        START REGISTER AREA
======================================-->
@include('frontend.home.register-area')
<!--======================================
        END REGISTER AREA
======================================-->

<div class="section-block"></div>

<!-- ================================
       START BLOG AREA
================================= -->
@include('frontend.home.blog-area')
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