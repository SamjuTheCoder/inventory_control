
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">


<!-- Mirrored from demo.dashboardpack.com/cryptocurrency-html/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 25 May 2021 12:29:27 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- <link rel="icon" href="img/favicon.png" type="image/png"> -->
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}" />
    <!-- themefy CSS -->
    <link rel="stylesheet" href="{{asset('assets/vendors/themefy_icon/themify-icons.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendors/font_awesome/css/all.min.css')}}" />
    <!-- datatable CSS -->
     <!-- scrollabe  -->
     <link rel="stylesheet" href="{{asset('assets/vendors/scroll/scrollable.css')}}" />

    <!-- menu css  -->
    <link rel="stylesheet" href="{{asset('assets/css/metisMenu.css')}}">
    <!-- style CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/colors/default.css')}}" id="colorSkinCSS">
</head>
<body class="crm_body_bg">
    <div class="main_content_iner m-lg-5">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
