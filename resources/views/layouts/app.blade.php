<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8"/>
    <title>UET Codehub</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
          type="text/css"/>
    <link href="{{URL::asset('assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{URL::asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{URL::asset('assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{URL::asset('assets/global/plugins/uniform/css/uniform.default.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{URL::asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{URL::asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{URL::asset('assets/global/plugins/morris/morris.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{URL::asset('assets/global/plugins/fullcalendar/fullcalendar.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{URL::asset('assets/global/plugins/jqvmap/jqvmap/jqvmap.css')}}" rel="stylesheet" type="text/css"/>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="{{URL::asset('assets/global/css/components.min.css')}}" rel="stylesheet" id="style_components"
          type="text/css"/>
    <link href="{{URL::asset('assets/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css"/>
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->

    <link href="{{URL::asset('assets/layouts/layout2/css/layout.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{URL::asset('assets/layouts/layout2/css/themes/blue.min.css')}}" rel="stylesheet" type="text/css"
          id="style_color"/>
    <link href="{{URL::asset('assets/layouts/layout2/css/custom.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{URL::asset('assets/global/plugins/animate/animate.min.css')}}" rel="stylesheet" type="text/css"/>
    <!-- END THEME LAYOUT STYLES -->
    {{--<link rel="shortcut icon" href="favicon.ico"/>--}}

    <!--[if lt IE 9]>
    <script src="../assets/global/plugins/respond.min.js"></script>
    <script src="../assets/global/plugins/excanvas.min.js"></script>
    <![endif]-->
    <!-- BEGIN CORE PLUGINS -->
    <script src="{{URL::asset('assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('assets/global/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('assets/global/plugins/js.cookie.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js')}}"
            type="text/javascript"></script>
    <script src="{{URL::asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}"
            type="text/javascript"></script>
    <script src="{{URL::asset('assets/global/plugins/jquery.blockui.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('assets/global/plugins/uniform/jquery.uniform.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"
            type="text/javascript"></script>
    <!-- END CORE PLUGINS -->

    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="{{URL::asset('assets/global/scripts/app.min.js')}}" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{URL::asset('assets/global/plugins/jquery-ui/jquery-ui.min.js')}}" type="text/javascript"></script>

    <!-- END PAGE LEVEL SCRIPTS -->

    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="{{URL::asset('assets/layouts/layout2/scripts/layout.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('assets/layouts/layout2/scripts/demo.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('assets/layouts/global/scripts/quick-sidebar.min.js')}}" type="text/javascript"></script>

    <!-- END THEME LAYOUT SCRIPTS -->
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    @yield('extendedHead')
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="{{url('/')}}">
                <img src="{{URL::asset('assets/layouts/layout2/img/CodehubLogo.png')}}" alt="logo"
                     class="logo-default"/> </a>
            <div class="menu-toggler sidebar-toggler">
                <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
           data-target=".navbar-collapse"> </a>
        <div class="page-top">
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <!-- BEGIN USER LOGIN DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    @if(Auth::guest())
                        <li style="margin-top: 7px">
                            <a href="{{url('/login')}}">
                                <i class="icon-login"></i><span>Đăng nhập</span>
                            </a>
                        </li>
                    @else
                        <li class="dropdown dropdown-user">
                            <a href="{{url('/user')}}" class="dropdown-toggle" data-toggle="dropdown"
                               data-hover="dropdown"
                               data-close-others="true">
                                <img alt="" class="img-circle"
                                     src="{{URL('assets/pages/media/profile/default_user.jpg')}}"/>
                                <span class="username username-hide-on-mobile"> {{Auth::user()->getFullname()}} </span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <li>
                                    <a href="{{url('/user')}}">
                                        <i class="icon-user"></i> Hồ sơ cá nhân </a>
                                </li>
                                <li>
                                    <a data-toggle="modal" data-target="#changePasswordModal">
                                        <i class="icon-lock"></i> Đổi mật khẩu
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="{{url('logout')}}">
                                        <i class="icon-key"></i> Đăng xuất </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                                <!-- END USER LOGIN DROPDOWN -->
                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END PAGE TOP -->
    </div>
    <!-- END HEADER INNER -->
</div>
<div class="clearfix"></div>
<div class="page-container">
    <div class="page-sidebar-wrapper">
        <div class="page-sidebar navbar-collapse collapse">
            <ul class="page-sidebar-menu  page-header-fixed page-sidebar-menu-hover-submenu " data-keep-expanded="false"
                data-auto-scroll="true" data-slide-speed="200">
                <li class="nav-item {{{ (Request::is('/') ? 'active open' : '') }}}">
                    <a href="{{url('/')}}" class="nav-link nav-toggle">
                        <i class="icon-home"></i>
                        <span class="title">Trang chủ</span>
						<span class="arrow"></span>
                        <span class="{{{ (Request::is('/') ? 'selected' : '') }}}"></span>
                    </a>
                </li>
                <li class="nav-item {{{ ((Request::is('my-courses')||Request::is('all-courses')||Request::is('my-courses/*')) ? 'active open' : '') }}}">
                    <a href="{{url('/my-courses')}}" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Khóa học</span>
                        <span class="arrow"></span>
						<span class="{{{ ((Request::is('my-courses')||Request::is('all-courses')||Request::is('my-courses/*')) ? 'selected' : '') }}}"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item  ">
                            <a href="{{url('/all-courses')}}" class="nav-link ">
                                <span class="title">Đăng ký thêm</span>
                            </a>
                        </li>
                        <li class="nav-item  ">
                            <a href="{{url('/my-courses')}}" class="nav-link ">
                                <span class="title">Đang tham gia</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{{ ((Request::is('exams')||Request::is('exams/*')) ? 'active open' : '') }}}">
                    <a href="{{url('/exams')}}" class="nav-link nav-toggle">
                        <i class="icon-note"></i>
                        <span class="title">Bài kiểm tra</span>
                        <span class="arrow"></span>
						<span class="{{{ ((Request::is('exams')||Request::is('exams/*')) ? 'selected' : '') }}}"></span>
                    </a>
                </li>
            </ul>
            <!-- END SIDEBAR MENU -->
        </div>
        <!-- END SIDEBAR -->
    </div>

    <!-- END SIDEBAR -->
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper" style="margin: 0px !important;">
        <!-- BEGIN CONTENT BODY -->
        <div class="page-content">
            <!-- BEGIN PAGE HEADER-->
            <h3 class="page-title"> @yield('pageTitle')
            </h3>
            <!-- END PAGE HEADER-->

            @if(!Auth::guest())
                @include('auth.changePassword')
            @endif

            @yield('content')
        </div>
        <!-- END CONTENT BODY -->
    </div>
    <!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="page-footer">
    <div class="page-footer-inner"> 2016 &copy; UETCodehub
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
<!-- END FOOTER -->
@yield('pageScript')
@yield('script')

</body>

</html>