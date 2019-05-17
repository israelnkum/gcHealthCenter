<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GC Health Center') }}</title>

    <!-- plugins:css -->
    <link rel="stylesheet" href="{{asset('public/vendors/iconfonts/simple-line-icon/css/simple-line-icons.css')}}">
    <link rel="stylesheet" href="{{asset('public/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/vendors/css/vendor.bundle.base.css')}}">
    <link rel="stylesheet" href="{{asset('public/vendors/css/vendor.bundle.addons.css')}}">

    <link rel="stylesheet" href="{{asset('public/css/style.css')}}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{asset('public/public/images/favicon.png')}}" />
</head>
<body onload=display_ct();>
<div class="container-scroller">
    <!-- partial:partials/_horizontal-navbar.html -->
    <nav class="navbar horizontal-layout col-lg-12 col-12 p-0">
        <div class="nav-top flex-grow-1">
            <div class="container d-flex flex-row h-100 align-items-center">
                <div class="text-center navbar-brand-wrapper d-flex align-items-center">
                    <a class="navbar-brand brand-logo" href="index-2.html"><img src="public/images/logo.svg" alt="logo"/></a>
                    <a class="navbar-brand brand-logo-mini" href="index-2.html"><img src="public/images/logo-mini.svg" alt="logo"/></a>
                </div>
                <div class="navbar-menu-wrapper d-flex align-items-center justify-content-between flex-grow-1">
                    <form class="search-field d-none d-md-flex" action="#">
                        <div class="form-group mb-0">
                            <span id="ct"></span>
                        </div>
                    </form>
                    <ul class="navbar-nav navbar-nav-right mr-0 ml-auto">
                        {{--<li class="nav-item dropdown d-none d-lg-flex nav-language">
                            <div class="nav-link">
                                <span class="dropdown-toggle btn btn-sm" id="languageDropdown" data-toggle="dropdown">English
                                  <i class="flag-icon flag-icon-gb ml-2"></i>
                                </span>
                                              <div class="dropdown-menu navbar-dropdown" aria-labelledby="languageDropdown">
                                                  <a class="dropdown-item font-weight-medium">
                                                      French
                                                      <i class="flag-icon flag-icon-fr ml-2"></i>
                                                  </a>
                                                  <div class="dropdown-divider"></div>
                                                  <a class="dropdown-item font-weight-medium">
                                                      Espanol
                                                      <i class="flag-icon flag-icon-es ml-2"></i>
                                                  </a>
                                                  <div class="dropdown-divider"></div>
                                                  <div class="dropdown-divider"></div>
                                                  <a class="dropdown-item font-weight-medium">
                                                      Arabic
                                                      <i class="flag-icon flag-icon-sa ml-2"></i>
                                                  </a>
                                              </div>
                                </div>
                            </li>--}}
                        {{--<li class="nav-item dropdown">
                            <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                                <i class="icon-envelope mx-0"></i>
                                <span class="count"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                                <div class="dropdown-item">
                                    <p class="mb-0 font-weight-normal float-left">You have 7 unread mails
                                    </p>
                                    <span class="badge badge-info badge-pill float-right">View all</span>
                                </div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
                                        <img src="public/images/faces/face4.jpg" alt="image" class="profile-pic">
                                    </div>
                                    <div class="preview-item-content flex-grow">
                                        <h6 class="preview-subject ellipsis font-weight-medium">David Grey
                                            <span class="float-right font-weight-light small-text">1 Minutes ago</span>
                                        </h6>
                                        <p class="font-weight-light small-text">
                                            The meeting is cancelled
                                        </p>
                                    </div>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
                                        <img src="public/images/faces/face2.jpg" alt="image" class="profile-pic">
                                    </div>
                                    <div class="preview-item-content flex-grow">
                                        <h6 class="preview-subject ellipsis font-weight-medium">Tim Cook
                                            <span class="float-right font-weight-light small-text">15 Minutes ago</span>
                                        </h6>
                                        <p class="font-weight-light small-text">
                                            New product launch
                                        </p>
                                    </div>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
                                        <img src="public/images/faces/face3.jpg" alt="image" class="profile-pic">
                                    </div>
                                    <div class="preview-item-content flex-grow">
                                        <h6 class="preview-subject ellipsis font-weight-medium"> Johnson
                                            <span class="float-right font-weight-light small-text">18 Minutes ago</span>
                                        </h6>
                                        <p class="font-weight-light small-text">
                                            Upcoming board meeting
                                        </p>
                                    </div>
                                </a>
                            </div>
                        </li>--}}
                        {{--<li class="nav-item dropdown">
                            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                                <i class="icon-bell"></i>
                                <span class="count"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                                <a class="dropdown-item py-3">
                                    <p class="mb-0 font-weight-medium float-left">You have 4 new notifications
                                    </p>
                                    <span class="badge badge-pill badge-info float-right">View all</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-success">
                                            <i class="icon-exclamation mx-0"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content">
                                        <h6 class="preview-subject font-weight-normal text-dark mb-1">Application Error</h6>
                                        <p class="font-weight-light small-text mb-0">
                                            Just now
                                        </p>
                                    </div>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-warning">
                                            <i class="icon-bubble mx-0"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content">
                                        <h6 class="preview-subject font-weight-normal text-dark mb-1">Settings</h6>
                                        <p class="font-weight-light small-text mb-0">
                                            Private message
                                        </p>
                                    </div>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-info">
                                            <i class="icon-user-following mx-0"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content">
                                        <h6 class="preview-subject font-weight-normal text-dark mb-1">New user registration</h6>
                                        <p class="font-weight-light small-text mb-0">
                                            2 days ago
                                        </p>
                                    </div>
                                </a>
                            </div>
                        </li>--}}
                        <li class="nav-item nav-profile dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                                <img src="public/images/faces/face4.jpg" alt="profile"/>
                                <span class="nav-profile-name text-capitalize">{{Auth::user()->first_name ." ".Auth::user()->last_name}}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                                <a class="dropdown-item">
                                    <i class="icon-user text-primary mr-2"></i>
                                    My Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                {{-- <a class="dropdown-item">
                                     <i class="icon-logout text-primary mr-2"></i>
                                     Logout
                                 </a>--}}
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <i class="icon-logout text-primary mr-2"></i>
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                    <button class="navbar-toggler align-self-center" type="button" data-toggle="minimize">
                        <span class="icon-menu"></span>
                    </button>
                </div>
            </div>
        </div>
        <div class="nav-bottom">
            <div class="container">
                <ul class="nav page-navigation">
                    <li class="nav-item">
                        <a href="{{route('home')}}" class="nav-link"><i class="link-icon icon-screen-desktop"></i><span class="menu-title">Home</span></a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('patients.index')}}" class="nav-link">
                            <i class="link-icon icon-user"></i>
                            <span class="menu-title">Patients</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pages/widgets.html" class="nav-link"><i class="link-icon icon-drop"></i><span class="menu-title">Pharmacy</span></a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link"><i class="link-icon icon-note"></i><span class="menu-title">Reports</span></a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('users.index')}}" class="nav-link">
                            <i class="link-icon icon-user"></i>
                            <span class="menu-title">Staff</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pages/documentation/documentation.html" class="nav-link">
                            <i class="link-icon icon-docs"></i>
                            <span class="menu-title">Preferences</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            @yield('content')
            <footer class="footer">
                <div class="w-100 clearfix">
                    <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © 2018 <a href="http://www.urbanui.com/" target="_blank">Urbanui</a>. All rights reserved.</span>
                    <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="icon-heart text-danger"></i></span>
                </div>
            </footer>
            <!-- partial -->
        </div>
        <!-- main-panel ends -->
    </div>
</div>
<!-- container-scroller -->

<script src="{{asset('public/vendors/js/vendor.bundle.base.js')}}"></script>
<script src="{{asset('public/vendors/js/vendor.bundle.addons.js')}}"></script>
<!-- endinject -->
<script src="{{asset('public/js/dashboard.js')}}"></script>
<!-- inject:js -->
<script src="{{asset('public/js/template.js')}}"></script>
<script src="{{asset('public/js/todolist.js')}}"></script>
<script src="{{asset('public/js/select2.js')}}"></script>
<script src="{{asset('public/js/mask.init.js')}}"></script>
<script src="{{asset('public/js/users.js')}}"></script>
<!-- endinject -->


<script type="text/javascript">
    function display_c(){
        var refresh=1000; // Refresh rate in milli seconds
        mytime=setTimeout('display_ct()',refresh)
    }

    function display_ct() {
        var x = new Date();
        var x1=x.toUTCString();// changing the display to UTC string
        document.getElementById('ct').innerHTML = x1;
        display_c();
    }


    /*
    * Form Validation
     */
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>
</body>
</html>
