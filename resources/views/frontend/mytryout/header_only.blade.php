<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/js/app.js'])
    
    <!-- Favicon -->
    <link rel="icon" sizes="16x16" href="{{asset('frontend/images/favicon.png')}}">
    <!-- inject:css -->
    <link rel="stylesheet" href="{{asset('frontend/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/line-awesome.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/bootstrap-select.min.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/fancybox.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/style.css')}}">
    <!-- end inject -->

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >
</head>
<body>

    {{-- ✅ Taruh header kamu di sini --}}
    @include('frontend.dashboard.body.header')

    {{-- ✅ Konten halaman tryout --}}
    <div class="container mt-5">
        @yield('content')
    </div>


<!-- template js files -->
<script src="{{asset('frontend/js/jquery-3.4.1.min.js')}}"></script>
<script src="{{asset('frontend/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('frontend/js/bootstrap-select.min.js')}}"></script>
<script src="{{asset('frontend/js/owl.carousel.min.js')}}"></script>
<script src="{{asset('frontend/js/isotope.js')}}"></script>
<script src="{{asset('frontend/js/jquery.counterup.min.js')}}"></script>
<script src="{{asset('frontend/js/fancybox.js')}}"></script>

<script src="{{asset('frontend/js/datedropper.min.js')}}"></script>
<script src="{{asset('frontend/js/emojionearea.min.js')}}"></script>
<script src="{{asset('frontend/js/animated-skills.js')}}"></script>
<script src="{{asset('frontend/js/jquery.MultiFile.min.js')}}"></script>
<script src="{{asset('frontend/js/main.js')}}"></script>
    
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
 @if(Session::has('message'))
 var type = "{{ Session::get('alert-type','info') }}"
 switch(type){
    case 'info':
    toastr.info(" {{ Session::get('message') }} ");
    break;

    case 'success':
    toastr.success(" {{ Session::get('message') }} ");
    break;

    case 'warning':
    toastr.warning(" {{ Session::get('message') }} ");
    break;

    case 'error':
    toastr.error(" {{ Session::get('message') }} ");
    break; 
 }
 @endif 
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
 
@include('frontend.body.script')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.dashboard-off-canvas-menu');
    const content = document.querySelector('.dashboard-content-wrap');
    const openBtn = document.querySelector('.dashboard-menu-toggler');
    const closeBtn = document.querySelector('.dashboard-menu-close');

    function toggleSidebar() {
        sidebar.classList.toggle('collapsed');
        content.classList.toggle('full');
    }

    if (openBtn) openBtn.addEventListener('click', toggleSidebar);
    if (closeBtn) closeBtn.addEventListener('click', toggleSidebar);
});
</script>
</body>
</html>
