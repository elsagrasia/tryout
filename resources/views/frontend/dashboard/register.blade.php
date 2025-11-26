@extends('frontend.master')
@section('home')
@section('title')
Register Page | Easy Learning
@endsection

<!-- ================================
    START BREADCRUMB AREA
================================= -->
<section class="breadcrumb-area section-padding img-bg-2">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content d-flex flex-wrap align-items-center justify-content-between">
            <div class="section-heading">
                <h2 class="section__title text-white">Daftar</h2>
                <ul class="generic-list-item generic-list-item-white generic-list-item-arrow d-flex flex-wrap align-items-center">
                    <li><a href="{{ route('index') }}">Beranda</a></li>                
                    <li>Daftar</li>
                </ul>
            </div>
        </div><!-- end breadcrumb-content -->
    </div><!-- end container -->
</section><!-- end breadcrumb-area -->
<!-- ================================
    END BREADCRUMB AREA
================================= -->

<!-- ================================
       START CONTACT AREA
================================= -->
<section class="contact-area section--padding position-relative">
    <span class="ring-shape ring-shape-1"></span>
    <span class="ring-shape ring-shape-2"></span>
    <span class="ring-shape ring-shape-3"></span>
    <span class="ring-shape ring-shape-4"></span>
    <span class="ring-shape ring-shape-5"></span>
    <span class="ring-shape ring-shape-6"></span>
    <span class="ring-shape ring-shape-7"></span>
    <div class="container">
        <div class="row">
            <div class="col-lg-7 mx-auto">
                <div class="card card-item">
                    <div class="card-body">
                        <h3 class="card-title text-center fs-24 lh-35 pb-4">Daftar Akun Anda dan<br> Mulai Tryout!</h3>
                        <div class="section-block"></div>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        
            <form method="POST" class="pt-4" action="{{ route('register') }}">
            @csrf
            
                <div class="input-box">
                    <label class="label-text">Nama</label>
                    <div class="form-group">
                        <input class="form-control form--control" id="name" type="text" name="name" placeholder="Nama">
                        <span class="la la-user input-icon"></span>
                    </div>
                </div><!-- end input-box -->
                <div class="input-box">
                    <label class="label-text">Email</label>
                    <div class="form-group">
                        <input class="form-control form--control" id="email" type="email" name="email" placeholder="email">
                        <span class="la la-envelope input-icon"></span>
                    </div>
                </div><!-- end input-box -->
                <div class="input-box">
                    <label class="label-text">Kata Sandi</label>
                    <div class="form-group">
                        <input class="form-control form--control" id="password" type="password" name="password" placeholder="Kata Sandi">
                        <span class="la la-lock input-icon"></span>
                    </div>
                </div><!-- end input-box -->
                <div class="input-box">
                    <label class="label-text">Konfirmasi Kata Sandi</label>
                    <div class="form-group">
                        <input class="form-control form--control" id="password_confirmation" type="password" name="password_confirmation" placeholder="Konfirmasi Kata Sandi">
                        <span class="la la-lock input-icon"></span>
                    </div>
                </div><!-- end input-box -->
               
                <div class="btn-box">                    
                    <button class="btn theme-btn" type="submit">Daftar <i class="la la-arrow-right icon ml-1"></i></button>
                    <p class="fs-14 pt-2">Sudah punya akun? <a href="{{ route('login') }}" class="text-color hover-underline">Masuk</a></p>
                </div><!-- end btn-box -->
            </form>
                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div><!-- end col-lg-7 -->
        </div><!-- end row -->
    </div><!-- end container -->
</section><!-- end contact-area -->
<!-- ================================
       END CONTACT AREA
================================= -->


@endsection