<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>GC Health Center - Login</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{asset('public/vendors/iconfonts/simple-line-icon/css/simple-line-icons.css')}}">
    <link rel="stylesheet" href="{{asset('public/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/vendors/css/vendor.bundle.base.css')}}">
    <link rel="stylesheet" href="{{asset('public/vendors/css/vendor.bundle.addons.css')}}">

    <link rel="stylesheet" href="{{asset('public/css/style.css')}}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{asset('public/images/favicon.png')}}" />
</head>

<body>
@yield('login_content')
<!-- container-scroller -->
<!-- plugins:js -->
<script src="{{asset('public/vendors/js/vendor.bundle.base.js')}}"></script>
<script src="{{asset('public/vendors/js/vendor.bundle.addons.js')}}"></script>
<!-- endinject -->
<!-- inject:js -->
<script src="{{asset('public/js/template.js')}}"></script>
<!-- endinject -->
</body>
</html>
