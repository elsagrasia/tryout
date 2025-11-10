<!doctype html>
<html lang="en">

<head> 
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('backend/assets/images/favicon-32x32.png') }}" type="image/png"/>

    @vite(['resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Plugins CSS -->
    <link href="{{ asset('backend/assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet"/>
    <link href="{{ asset('backend/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet"/>

    <!-- Loader -->
    <link href="{{ asset('backend/assets/css/pace.min.css') }}" rel="stylesheet"/>
    <script src="{{ asset('backend/assets/js/pace.min.js') }}"></script>

    <!-- Bootstrap & Theme -->
    <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ asset('backend/assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/css/icons.css') }}" rel="stylesheet">

    <!-- Theme Variants -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/dark-theme.css') }}"/>
    <link rel="stylesheet" href="{{ asset('backend/assets/css/semi-dark.css') }}"/>
    <link rel="stylesheet" href="{{ asset('backend/assets/css/header-colors.css') }}"/>

    <!-- Datatable -->
    <link href="{{ asset('backend/assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />

    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" >

    <title>Instructor Dashboard</title>
</head>

<body>
    <div class="wrapper">
        @include('instructor.body.sidebar')
        @include('instructor.body.header')

        <div class="page-wrapper">
            @yield('instructor')
        </div>

        <div class="overlay toggle-icon"></div>
        <a href="javascript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>

        @include('instructor.body.footer')
    </div>

    <!-- ========================================================= -->
    <!--  JAVASCRIPT ORDER — DO NOT CHANGE ORDER BELOW -->
    <!-- ========================================================= -->

    <!-- ✅ jQuery HARUS lebih dulu -->
    <script src="{{ asset('backend/assets/js/jquery.min.js') }}"></script>

    <!-- Bootstrap -->
    <script src="{{ asset('backend/assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- Plugin JS -->
    <script src="{{ asset('backend/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/chartjs/js/chart.js') }}"></script>
    {{-- <script src="{{ asset('backend/assets/js/index.js') }}"></script> --}}

    <!-- App Core -->
    <script src="{{ asset('backend/assets/js/app.js') }}"></script>
    <script src="{{ asset('backend/assets/js/validate.min.js') }}"></script>

    <!-- ✅ SweetAlert Delete Confirm -->
    <script src="{{ asset('backend/assets/js/code.js') }}"></script>

    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- ✅ DataTables -->
    <script src="{{ asset('backend/assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>

    <!-- ✅ Toastr Session Alerts -->
    <script>
        @if(Session::has('message'))
        var type = "{{ Session::get('alert-type','info') }}";
        switch(type){
            case 'info': toastr.info("{{ Session::get('message') }}"); break;
            case 'success': toastr.success("{{ Session::get('message') }}"); break;
            case 'warning': toastr.warning("{{ Session::get('message') }}"); break;
            case 'error': toastr.error("{{ Session::get('message') }}"); break;
        }
        @endif 
    </script>

    <!-- ✅ TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/m9qdurebm1xyvjllf2x5uq6cszrnv0jdxwyfvrj4sxxkebuv/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea#myeditorinstance',
            plugins: 'powerpaste advcode table lists checklist',
            toolbar: 'undo redo | blocks | bold italic | bullist numlist checklist | code | table'
        });
    </script>

</body>
</html>
