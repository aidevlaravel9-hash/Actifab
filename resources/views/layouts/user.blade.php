<!doctype html>
<html lang="en" data-layout="horizontal" data-layout-style="default" data-layout-position="fixed" data-topbar="light"
    data-sidebar="dark" data-sidebar-size="sm-hover" data-layout-width="fluid">

{{-- Include Head --}}
@include('common.user.head')

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- Topbar -->
        @include('common.user.header')
        <!-- End of Topbar -->

        <!-- Sidebar -->
        @include('common.user.sidebar')
        <!-- End of Sidebar -->

        @yield('content')

        @include('common.user.footer')

    </div>

    @include('common.user.footerjs')
    
        
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    @yield('scripts')

</body>

</html>
