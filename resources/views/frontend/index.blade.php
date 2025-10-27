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


@endsection