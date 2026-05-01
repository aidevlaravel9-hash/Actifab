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

    @yield('scripts')

</body>

</html>
