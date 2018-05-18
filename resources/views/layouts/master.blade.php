<!DOCTYPE html>
<!-- saved from url=(0054)https://keenthemes.com/preview/metronic/theme/admin_4/ -->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Metronic Admin Theme #4 | Admin Dashboard 2</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="Preview page of Metronic Admin Theme #4 for statistics, charts, recent events and reports" name="description">
    <meta content="" name="author">
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="{{asset('css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css">
    <link href="{{asset('theme/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('theme/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css">
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{asset('theme/css/daterangepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('theme/css/morris.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('theme/css/fullcalendar.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('theme/css/jqvmap.css')}}" rel="stylesheet" type="text/css">
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="{{asset('theme/css/components.min.css')}}" rel="stylesheet" id="style_components" type="text/css">
    <link href="{{asset('theme/css/plugins.min.css')}}" rel="stylesheet" type="text/css">
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="{{asset('theme/css/layout.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('theme/css/light.min.css')}}" rel="stylesheet" type="text/css" id="style_color">
    <link href="{{asset('theme/css/custom.min.css')}}" rel="stylesheet" type="text/css">
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="https://keenthemes.com/preview/metronic/theme/admin_4/favicon.ico">
    <style type="text/css">
        .jqstooltip {
            position: absolute;
            left: 0px;
            top: 0px;
            visibility: hidden;
            background: rgb(0, 0, 0) transparent;
            background-color: rgba(0, 0, 0, 0.6);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);
            -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";
            color: white;
            font: 10px arial, san serif;
            text-align: left;
            white-space: nowrap;
            padding: 5px;
            border: 1px solid white;
            z-index: 10000;
        }

        .jqsfield {
            color: white;
            font: 10px arial, san serif;
            text-align: left;
        }
    </style>
    @yield('css')
</head>
<!-- END HEAD -->

<body class="page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo">
    <!-- BEGIN HEADER -->
    @include('navbar')
    <!-- END HEADER -->
    <!-- BEGIN HEADER & CONTENT DIVIDER -->
    <div class="clearfix"> </div>
    <!-- END HEADER & CONTENT DIVIDER -->
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        <!-- BEGIN SIDEBAR -->
        @include('menu')
        <!-- END SIDEBAR -->
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->
            <div class="page-content" style="min-height: 1128px;">

                @yield('contenido')

            </div>
            <!-- END CONTENT BODY -->
        </div>
        <!-- END CONTENT -->
        <!-- BEGIN QUICK SIDEBAR -->
        <!-- END QUICK SIDEBAR -->
    </div>
    <!-- END CONTAINER -->
    <!-- BEGIN FOOTER -->
    <!-- END FOOTER -->
    <!-- BEGIN QUICK NAV -->

    <!-- END QUICK NAV -->
    <!--[if lt IE 9]>
<script src="../assets/global/plugins/respond.min.js"></script>
<script src="../assets/global/plugins/excanvas.min.js"></script> 
<script src="../assets/global/plugins/ie8.fix.min.js"></script> 
<![endif]-->
    <!-- BEGIN CORE PLUGINS -->
    <script async="" src="{{asset('theme/js/gtm.js.download')}}"></script>
    <script async="" src="{{asset('theme/js/analytics.js.download')}}"></script>
    <script src="{{asset('theme/js/jquery.min.js.download')}}" type="text/javascript"></script>
    <script src="{{asset('theme/js/bootstrap.min.js.download')}}" type="text/javascript"></script>
    <script src="{{asset('theme/js/js.cookie.min.js.download')}}" type="text/javascript"></script>
    <script src="{{asset('theme/js/jquery.slimscroll.min.js.download')}}" type="text/javascript"></script>
    <script src="{{asset('theme/js/jquery.blockui.min.js.download')}}" type="text/javascript"></script>
    <script src="{{asset('theme/js/bootstrap-switch.min.js.download')}}" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    {{--

        <script src="./Metronic_files/moment.min.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/daterangepicker.min.js.download" type="text/javascript"></script>
    --}}
        <script src="{{asset('theme/js/morris.min.js.download')}}" type="text/javascript"></script>
    {{--
        <script src="./Metronic_files/raphael-min.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/jquery.waypoints.min.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/jquery.counterup.min.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/amcharts.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/serial.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/pie.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/radar.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/light.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/patterns.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/chalk.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/ammap.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/worldLow.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/amstock.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/fullcalendar.min.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/horizontal-timeline.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/jquery.flot.min.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/jquery.flot.resize.min.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/jquery.flot.categories.min.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/jquery.easypiechart.min.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/jquery.sparkline.min.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/jquery.vmap.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/jquery.vmap.russia.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/jquery.vmap.world.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/jquery.vmap.europe.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/jquery.vmap.germany.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/jquery.vmap.usa.js.download" type="text/javascript"></script>
        <script src="./Metronic_files/jquery.vmap.sampledata.js.download" type="text/javascript"></script>
    --}}
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="{{asset('theme/js/app.min.js.download')}}" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{asset('theme/js/dashboard.min.js.download')}}" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="{{asset('theme/js/layout.min.js.download')}}" type="text/javascript"></script>
    {{--<script src="./Metronic_files/demo.min.js.download" type="text/javascript"></script>--}}
    <script src="{{asset('theme/js/quick-sidebar.min.js.download')}}" type="text/javascript"></script>
    <script src="{{asset('theme/js/quick-nav.min.js.download')}}" type="text/javascript"></script>
    <!-- END THEME LAYOUT SCRIPTS -->
    <script>
        $(document).ready(function () {
            $('#clickmewow').click(function () {
                $('#radio1003').attr('checked', 'checked');
            });
        })
    </script>
    @yield('javascript')
</body>

</html>