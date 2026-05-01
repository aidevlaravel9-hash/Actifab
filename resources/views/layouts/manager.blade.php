<!doctype html>
<html lang="en" data-layout="horizontal" data-layout-style="default" data-layout-position="fixed" data-topbar="light"
    data-sidebar="dark" data-sidebar-size="sm-hover" data-layout-width="fluid">

@include('common.manager.head')

<body>
    <div id="layout-wrapper">
        @include('common.manager.header')
        @include('common.manager.sidebar')
        @yield('content')
        @include('common.manager.footer')
    </div>

    @include('common.manager.footerjs')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    @yield('scripts')
</body>

</html>
