<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="_token" content="{{csrf_token()}}" />
    <title>@yield('pageHeaderTitle', 'Inventory')</title>

    <link rel="icon" href="{{asset('assets/img/mini_logo.png')}}" type="image/png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}" />
    <!-- themefy CSS -->
    <link rel="stylesheet" href="{{asset('assets/vendors/themefy_icon/themify-icons.css')}}" />
    <!-- select2 CSS -->
    <link rel="stylesheet" href="{{asset('assets/vendors/niceselect/css/nice-select.css')}}" />
    <!-- owl carousel CSS -->
    <link rel="stylesheet" href="{{asset('assets/vendors/owl_carousel/css/owl.carousel.css')}}" />
    <!-- gijgo css -->
    <link rel="stylesheet" href="{{asset('assets/vendors/gijgo/gijgo.min.css')}}" />
    <!-- font awesome CSS -->
    <link rel="stylesheet" href="{{asset('assets/vendors/font_awesome/css/all.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendors/tagsinput/tagsinput.css')}}" />

    <!-- date picker -->
     <link rel="stylesheet" href="{{asset('assets/vendors/datepicker/date-picker.css')}}" />

     <link rel="stylesheet" href="{{asset('assets/vendors/vectormap-home/vectormap-2.0.2.css')}}" />

     <!-- scrollabe  -->
     <link rel="stylesheet" href="{{asset('assets/vendors/scroll/scrollable.css')}}" />
    <!-- datatable CSS -->
    <!--<link rel="stylesheet" href="{{asset('assets/vendors/datatable/css/jquery.dataTables.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendors/datatable/css/responsive.dataTables.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendors/datatable/css/buttons.dataTables.min.css')}}" />-->
    <!-- text editor css -->
    <link rel="stylesheet" href="{{asset('assets/vendors/text_editor/summernote-bs4.css')}}" />
    <!-- morris css -->
    <link rel="stylesheet" href="{{asset('assets/vendors/morris/morris.css')}}">
    <!-- metarial icon css -->
    <link rel="stylesheet" href="{{asset('assets/vendors/material_icon/material-icons.css')}}" />

    <!-- menu css  -->
    <link rel="stylesheet" href="{{asset('assets/css/metisMenu.css')}}">
    <!-- style CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/colors/default.css')}}" id="colorSkinCSS">
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" crossorigin="anonymous" />-->
    <!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->

    <link rel="stylesheet" href="{{asset('assets/css/selectize.min.css')}}">
    @yield('style')

</head>
<body class="crm_body_bg">



<!-- main content part here -->

 <!-- sidebar  -->
<nav class="sidebar dark_sidebar">
    <div class="logo d-flex justify-content-between">
        <a class="large_logo" href="{{url('/')}}"><img src="{{asset('assets/img/logo_white.png')}}" alt=""></a>
        <a class="small_logo" href="{{url('/')}}"><img src="{{asset('assets/img/mini_logo.png')}}" alt=""></a>
        <div class="sidebar_close_icon d-lg-none">
            <i class="ti-close"></i>
        </div>
    </div>
    <ul id="sidebar_menu">
        <!--<li class="">
            <a class="has-arrow" href="#" aria-expanded="false">
                <div class="nav_icon_small">
                    <img src="{{asset('assets/img/menu-icon/1.svg')}}" alt="">
                </div>
                <div class="nav_title">
                    <span>Dashboard </span>
                </div>
            </a>
            <ul>
                <li><a href="index_2.html">Default</a></li>
              <li><a href="index_3.html">Light Sidebar</a></li>
              <li><a href="index-2.html">Dark Sidebar</a></li>
            </ul>
        </li>
        <li class="">
            <a class="" href="{{route('basic-form')}}" aria-expanded="false">
                <div class="nav_icon_small">
                    <img src="{{asset('assets/img/menu-icon/1.svg')}}" alt="">
                </div>
                <div class="nav_title">
                    <span>Basic Form </span>
                </div>
            </a>
        </li>
        <li class="">
            <a class="" href="{{route('table')}}" aria-expanded="false">
                <div class="nav_icon_small">
                    <img src="{{asset('assets/img/menu-icon/1.svg')}}" alt="">
                </div>
                <div class="nav_title">
                    <span>Table </span>
                </div>
            </a>
        </li>
        <li class="">
            <a class="" href="{{route('modal')}}" aria-expanded="false">
                <div class="nav_icon_small">
                    <img src="{{asset('assets/img/menu-icon/1.svg')}}" alt="">
                </div>
                <div class="nav_title">
                    <span>Modal </span>
                </div>
            </a>
        </li>
        <li class="">
            <a class="" href="{{route('icon')}}" aria-expanded="false">
                <div class="nav_icon_small">
                    <img src="{{asset('assets/img/menu-icon/1.svg')}}" alt="">
                </div>
                <div class="nav_title">
                    <span>icon </span>
                </div>
            </a>
        </li>-->

         <li class="">
            <a class="has-arrow" href="#" aria-expanded="false">
                <div class="nav_icon_small">
                    <img src="{{asset('assets/img/menu-icon/1.svg')}}" alt="">
                </div>
                <div class="nav_title">
                    <span>Project </span>
                </div>
            </a>
            <ul>
              <li><a href="{{route('launchProject')}}">Create Project</a></li>

            </ul>
        </li>
        <li class="">
            <a class="has-arrow" href="#" aria-expanded="false">
                <div class="nav_icon_small">
                    <img src="{{asset('assets/img/menu-icon/1.svg')}}" alt="">
                </div>
                <div class="nav_title">
                    <span>Client </span>
                </div>
            </a>
            <ul>
              <li><a href="{{route('launchClient')}}">Create Client</a></li>

            </ul>
        </li>

        <li class="">
            <a class="has-arrow" href="#" aria-expanded="false">
                <div class="nav_icon_small">
                    <img src="{{asset('assets/img/menu-icon/1.svg')}}" alt="">
                </div>
                <div class="nav_title">
                    <span>Category </span>
                </div>
            </a>
            <ul>
              <li><a href="{{route('getCategory')}}">Create Category</a></li>
              <!-- <li><a href="{{ Route::has('createMeasurement') ? Route('createMeasurement') : 'javascript:;'  }}"></a></li> -->
            </ul>
        </li>

        <li class="">
            <a class="has-arrow" href="#" aria-expanded="false">
                <div class="nav_icon_small">
                    <img src="{{asset('assets/img/menu-icon/1.svg')}}" alt="">
                </div>
                <div class="nav_title">
                    <span>Product </span>
                </div>
            </a>
            <ul>
              <li><a href="{{route('create-product')}}">Create Product</a></li>
              <li><a href="{{ Route::has('createMeasurement') ? Route('createMeasurement') : 'javascript:;'  }}"> Product Measurement </a></li>
              <!--<li><a href="{{ Route::has('createMeasurementUnit') ? Route('createMeasurementUnit') : 'javascript:;'  }}"> Product Measurement Setup </a></li> -->
              <li><a href="{{ Route::has('createProductMovement') ? Route('createProductMovement') : 'javascript:;'  }}"> Product Entry (In) </a></li>
              <li><a href="{{ Route::has('createProductGoingOut') ? Route('createProductGoingOut') : 'javascript:;'  }}"> Product Movement (Out) </a></li>

              <li><a href="{{ url('/view/orders-items')}}"> Cancel Orders(Moved Out) </a></li>
              <li><a href="{{ url('/view/orders-in')}}"> Adjust Orders(Moved In) </a></li>
              <li><a href="{{ Route::has('quantityControlShow') ? Route('quantityControlShow') : 'javascript:;'  }}"> Quantity Control </a></li>
              <li><a href="{{ Route::has('transferProduct') ? Route('transferProduct') : 'javascript:;'  }}"> Transfer Product Out </a></li>
              <li><a href="{{ Route::has('createEditTransaferReport') ? Route('createEditTransaferReport') : 'javascript:;'  }}"> View Rejected Transferred </a></li>


            </ul>
        </li>


        <li class="">
            <a class="has-arrow" href="#" aria-expanded="false">
                <div class="nav_icon_small">
                    <img src="{{asset('assets/img/menu-icon/1.svg')}}" alt="">
                </div>
                <div class="nav_title">
                    <span>Report </span>
                </div>
            </a>
            <ul>

              <li><a href="{{ Route::has('createProductMovementInOut') ? Route('createProductMovementInOut') : 'javascript:;'  }}"> Inventory Balance </a></li>
              <li><a href="{{ route('launchReport') }}"> Product Movement Out</a></li>
              <li><a href="{{ route('launchInReport') }}"> Product Movement In</a></li>
              <li><a href="{{ route('quantityControlReportShow') }}"> Quantity Control</a></li>
              <li><a href="{{ route('productInShelve')}}">Product in Shelve</a></li>
              <li><a href="{{ route('productSearch')}}">Product Search</a></li>
              <li><a href="{{ url('/stock-trail')}}">Stock Trail</a></li>
              
              <li><a href="{{ Route::has('viewTransaferReport') ? Route('viewTransaferReport') : 'javascript:;'  }}"> Product Transferred </a></li>
              <!-- <li><a href="{{ route('confirmReport')}}"> Product Status Report</a></li> -->
            </ul>
        </li>


        <li class="">
            <a class="has-arrow" href="#" aria-expanded="false">
                <div class="nav_icon_small">
                    <img src="{{asset('assets/img/menu-icon/1.svg')}}" alt="">
                </div>
                <div class="nav_title">
                    <span>Store/Warehouse </span>
                </div>
            </a>
            <ul>
              <li><a href="{{url('/create/warehouse')}}">Create Store</a></li>
              <li><a href="{{url('/create/store-users')}}">Create Store Users</a></li>
              <li><a href="{{url('/confirm-movement')}}">Confirm Products</a></li>
            </ul>
        </li>
        <li class="">
            <a class="has-arrow" href="#" aria-expanded="false">
                <div class="nav_icon_small">
                    <img src="{{asset('assets/img/menu-icon/1.svg')}}" alt="">
                </div>
                <div class="nav_title">
                    <span>Shelves </span>
                </div>
            </a>
            <ul>
              <li><a href="{{url('/create/shelve')}}">Create Shelf</a></li>

            </ul>
        </li>
        <li class="">
            <a class="has-arrow" href="#" aria-expanded="false">
                <div class="nav_icon_small">
                    <img src="{{asset('assets/img/menu-icon/1.svg')}}" alt="">
                </div>
                <div class="nav_title">
                    <span>Confirmation </span>
                </div>
            </a>
            <ul>
              <li><a href="{{route('display-confirm-in')}}">Confirmation In</a></li>
              <li><a href="{{route('display-confirm-out')}}">Confirmation Out</a></li>

            </ul>
        </li>

    </ul>
</nav>
 <!--/ sidebar  -->


<section class="main_content dashboard_part large_header_bg">

        <!-- menu  -->
        <div class="container-fluid no-gutters">
            <div class="row">
                <div class="col-lg-12 p-0 ">
                    <div class="header_iner d-flex justify-content-between align-items-center">
                        <div class="sidebar_icon d-lg-none">
                            <i class="ti-menu"></i>
                        </div>
                        <div class="line_icon open_miniSide d-none d-lg-block">
                            <img src="{{asset('assets/img/line_img.png')}}" alt="">
                        </div>
                        <div class="header_right d-flex justify-content-between align-items-center">

                            <div class="profile_info d-flex align-items-center">
                                <div class="profile_thumb mr_20">
                                    <img src="{{asset('assets/img/transfer/4.png')}}" alt="#">
                                </div>
                                <div class="author_name">
                                    <h4 class="f_s_15 f_w_500 mb-0">{{ Auth::user()->name }}</h4>
                                    <p class="f_s_12 f_w_400">Manager</p>
                                </div>

                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                @endif

                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                            <div class="profile_info_iner">
                                <div class="profile_author_name">
                                    <p>Manager</p>
                                    <h5>{{ Auth::user()->name }}</h5>
                                </div>
                                <div class="profile_info_details">
                                    <a href="#">My Profile </a>
                                    <a href="#">Settings</a>
                                    <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                </div>
                            </div>
                            @endguest

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ menu  -->
        <div class="main_content_iner overly_inner ">
            <div class="container-fluid p-0 ">
                @yield('content')
            </div>
        </div>

    <!-- footer part -->
    <div class="footer_part">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="footer_iner text-center">
                        <p>2021 © MBR Computer Consultants  <a href="#"> </a><a href="#"> </a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    <!-- main content part end -->



    <div id="back-top" style="display: none;">
        <a title="Go to Top" href="#">
            <i class="ti-angle-up"></i>
        </a>
    </div>


    @yield('modal')
     @yield('modal2')
    <!-- footer  -->
    <script src="{{asset('assets/js/jquery-3.4.1.min.js')}}"></script>
    <!-- popper js -->
    <script src="{{asset('assets/js/popper.min.js')}}"></script>
    <!-- bootstarp js -->
    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    <!-- sidebar menu  -->
    <script src="{{asset('assets/js/metisMenu.js')}}"></script>
    <!-- waypoints js -->
    <script src="{{asset('assets/vendors/count_up/jquery.waypoints.min.js')}}"></script>
    <!-- waypoints js -->
    <script src="{{asset('assets/vendors/chartlist/Chart.min.js')}}"></script>
    <!-- counterup js -->
    <script src="{{asset('assets/vendors/count_up/jquery.counterup.min.js')}}"></script>

    <!-- nice select -->
    <script src="{{asset('assets/vendors/niceselect/js/jquery.nice-select.min.js')}}"></script>
    <!-- owl carousel -->
    <script src="{{asset('assets/vendors/owl_carousel/js/owl.carousel.min.js')}}"></script>

    <!-- responsive table -->
    <script src="{{asset('assets/vendors/datatable/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/vendors/datatable/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('assets/vendors/datatable/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/vendors/datatable/js/buttons.flash.min.js')}}"></script>
    <script src="{{asset('assets/vendors/datatable/js/jszip.min.js')}}"></script>
    <script src="{{asset('assets/vendors/datatable/js/pdfmake.min.js')}}"></script>
    <script src="{{asset('assets/vendors/datatable/js/vfs_fonts.js')}}"></script>
    <script src="{{asset('assets/vendors/datatable/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('assets/vendors/datatable/js/buttons.print.min.js')}}"></script>

    <!-- datepicker  -->
    <script src="{{asset('assets/vendors/datepicker/datepicker.js')}}"></script>
    <script src="{{asset('assets/vendors/datepicker/datepicker.en.js')}}"></script>
    <script src="{{asset('assets/vendors/datepicker/datepicker.custom.js')}}"></script>

    <script src="{{asset('assets/js/chart.min.js')}}"></script>
    <script src="{{asset('assets/vendors/chartjs/roundedBar.min.js')}}"></script>

    <!-- progressbar js -->
    <script src="{{asset('assets/vendors/progressbar/jquery.barfiller.js')}}"></script>
    <!-- tag input -->
    <script src="{{asset('assets/vendors/tagsinput/tagsinput.js')}}"></script>
    <!-- text editor js -->
    <script src="{{asset('assets/vendors/text_editor/summernote-bs4.js')}}"></script>
    <script src="{{asset('assets/vendors/am_chart/amcharts.js')}}"></script>

    <!-- scrollabe  -->
    <script src="{{asset('assets/vendors/scroll/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('assets/vendors/scroll/scrollable-custom.js')}}"></script>

    <!-- vector map  -->
    <script src="{{asset('assets/vendors/vectormap-home/vectormap-2.0.2.min.js')}}"></script>
    <script src="{{asset('assets/vendors/vectormap-home/vectormap-world-mill-en.js')}}"></script>

    <!-- apex chrat  -->
    <script src="{{asset('assets/vendors/apex_chart/apex-chart2.js')}}"></script>
    <script src="{{asset('assets/vendors/apex_chart/apex_dashboard.js')}}"></script>

    <!-- <script src="vendors/echart/echarts.min.js"></script> -->

    <script src="{{asset('assets/vendors/chart_am/core.js')}}"></script>
    <script src="{{asset('assets/vendors/chart_am/charts.js')}}"></script>
    <script src="{{asset('assets/vendors/chart_am/animated.js')}}"></script>
    <script src="{{asset('assets/vendors/chart_am/kelly.js')}}"></script>
    <script src="{{asset('assets/vendors/chart_am/chart-custom.js')}}"></script>

    <!-- custom js -->
    <script src="{{asset('assets/js/custom.js')}}"></script>
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" crossorigin="anonymous"></script>-->
    <script src="{{asset('assets/js/selectize.min.js')}}"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->


    @yield('script')

    </body>
    </html>

