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

    <link rel="stylesheet" href="{{asset('public/vendors/lightgallery/css/lightgallery.css')}}">
    <link rel="stylesheet" href="{{asset('public/css/style.css')}}">
    {{--    <link rel="stylesheet" href="{{asset('public/css/jquery.dataTables.min.css')}}">--}}
    <link rel="stylesheet" href="{{asset('public/css/buttons.dataTables.min.css')}}">

    <!-- endinject -->
    <link rel="shortcut icon" href="{{asset('public/images/logo.jpeg')}}" />

</head>
<body onload=display_ct();>
<div class="container-scroller">
    <!-- partial:partials/_horizontal-navbar.html -->
    <nav class="navbar horizontal-layout col-lg-12 col-12 p-0">
        <div class="nav-top flex-grow-1">
            <div class="container d-flex flex-row h-100 align-items-center">
                <div class="text-center navbar-brand-wrapper d-flex align-items-center">
                    <a class="navbar-brand brand-logo" href="{{route('home')}}"><img src="{{asset('public/images/logo.jpeg')}}" alt="logo"/></a>
                    <a class="navbar-brand brand-logo-mini" href="{{route('home')}}"><img src="{{asset('public/images/logo.jpeg')}}" alt="logo"/></a>
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
                                {{--<img src="public/images/faces/face4.jpg" alt="profile"/>--}}
                                {{--<span class="nav-profile-name text-capitalize">{{Auth::user()->first_name ." ".Auth::user()->last_name}}</span>--}}
                                @if(Auth::user()->updated == 1 && Auth::user()->first_name != "")
                                    <span class="text-capitalize">Hi {{Auth::user()->first_name ." ".Auth::user()->last_name}}</span>
                                @else
                                    <span class="text-capitalize">Hi {{Auth::user()->username}}</span>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{route('users.edit',Auth::user()->id)}}?{{Hash::make(time())}}">
                                    <i class="icon-user text-dark mr-2"></i>
                                    My Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{route('change-password')}}?{{Hash::make(time())}}">
                                    <i class="icon-lock text-dark mr-2"></i>
                                    Change Password
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}?{{Hash::make(time())}}"
                                   onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    <i class="icon-arrow-left-circle text-dark mr-2"></i>
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}?{{Hash::make(time())}}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                    <button class="navbar-toggler text-white align-self-center" type="button" data-toggle="minimize">
                        <span class="icon-menu text-white"></span>
                    </button>
                </div>
            </div>
        </div>
        @if(Auth::user()->updated == 1)
            <div class="nav-bottom">
                <div class="container">
                    <ul class="nav page-navigation">
                        <li class="nav-item">
                            <a href="{{route('home')}}?{{Hash::make(time())}}" class="nav-link"><i class="link-icon icon-screen-desktop"></i><span class="menu-title">Home</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0)" class="nav-link"><i class="link-icon icon-folder-alt"></i><span class="menu-title">File</span><i class="menu-arrow"></i></a>
                            <div class="submenu">
                                <ul class="submenu-item">
                                    <li class="nav-item"><a class="nav-link" href="{{route('patients.index')}}?{{Hash::make(time())}}">Patients</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{route('patients.create')}}?{{Hash::make(time())}}">All Patients</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{route('vitals.index')}}?{{Hash::make(time())}}">Vitals</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('consultation.index')}}?{{Hash::make(time())}}" class="nav-link">
                                <i class="link-icon icon-energy"></i>
                                <span class="menu-title">Consulting</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0)" class="nav-link"><i class="link-icon icon-drop">
                                </i><span class="menu-title">Pharmacy</span><i class="menu-arrow"></i>
                            </a>
                            <div class="submenu">
                                <ul class="submenu-item">
                                    <li class="nav-item"><a class="nav-link" href="{{route('drugs.create')}}?{{Hash::make(time())}}">Dispense</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{route('drugs.index')}}?{{Hash::make(time())}}">Drugs</a></li>
                                </ul>
                            </div>
                        </li>


                        <li class="nav-item">
                            <a href="#" class="nav-link"><i class="link-icon icon-note"></i><span class="menu-title">Reports</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link"><i class="link-icon icon-notebook"></i><span class="menu-title">Archive</span></a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('users.index')}}?{{Hash::make(time())}}" class="nav-link">
                                <i class="link-icon icon-user"></i>
                                <span class="menu-title">Staff</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('preferences.index')}}?{{Hash::make(time())}}" class="nav-link">
                                <i class="link-icon icon-docs"></i>
                                <span class="menu-title">Preferences</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        @endif
    </nav>
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="container mt-3">
                @include('layouts.messages')
            </div>
            @yield('content')
            <footer class="footer">
                <div class="w-100 clearfix">
                    <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">&copy; {{date('Y')}} GC Health Clinic. All rights reserved.</span>
                    <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Powered by ANA Technologies</span>
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
{{--<script src="https://code.jquery.com/jquery-3.3.1.js"></script>--}}
<!-- endinject -->
<script src="{{asset('public/js/dashboard.js')}}"></script>
<!-- inject:js -->

<script src="{{asset('public/js/formpickers.js')}}"></script>
<script src="{{asset('public/js/form-addons.js')}}"></script>

<script src="{{asset('public/js/template.js')}}"></script>
<script src="{{asset('public/js/todolist.js')}}"></script>
<script src="{{asset('public/js/file-upload.js')}}"></script>
<script src="{{asset('public/js/select2.js')}}"></script>

<script src="{{asset('public/js/form-repeater.js')}}"></script>
<script src="{{asset('public/js/repeater.js')}}"></script>
<script src="{{asset('public/js/mask.init.js')}}"></script>
<script src="{{asset('public/js/users.js')}}"></script>
<script src="{{asset('public/js/preferences.js')}}"></script>
<script src="{{asset('public/js/patients.js')}}"></script>
<script src="{{asset('public/js/vitals.js')}}"></script>

<script src="{{asset('public/js/drugs.js')}}"></script>
<script src="{{asset('public/js/dispensed.js')}}"></script>
<script src="{{asset('public/js/consultation.js')}}"></script>
<script src="{{asset('public/js/form-validation.js')}}"></script>
<script src="{{asset('public/js/bt-maxLength.js')}}"></script>
<script src="{{asset('public/vendors/lightgallery/js/lightgallery-all.min.js')}}"></script>
<script src="{{asset('public/js/light-gallery.js')}}"></script>


<script>
    /* Create Repeater */
    $("#repeater").createRepeater({
        showFirstItemToDefault: true,
    });
</script>

<script src="{{asset('public/js/dataTables/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('public/js/dataTables/buttons.flash.min.js')}}"></script>
<script src="{{asset('public/js/dataTables/jszip.min.js')}}"></script>
<script src="{{asset('public/js/dataTables/pdfmake.min.js')}}"></script>
<script src="{{asset('public/js/dataTables/vfs_fonts.js')}}"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="{{asset('public/js/dataTables/buttons.print.min.js')}}"></script>

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

    //auto dismiss alert
    $(document).ready(function () {
        window.setTimeout(function () {
            $('.alert').fadeTo(1000,0).slideUp(1000, function () {
                $(this).remove();
            });
        },3000);
    });

    //print div
    $("#btn_print").click(function () {
        // window.print("#pri");
        $("#print_div").print()
        // $("#print_div").print();
    });
</script>
@if(\Request::is('searchConsultation') || \Request::is('consultation/*'))
    <script type="text/javascript">

        let drugs = '<?php echo $getImplodedMedicine ?>';

        let myArray = drugs.substring(0, drugs.length).split(',');
        //alert(drugs);

        $("#patient_medication").val(myArray).trigger('change');

        let diagnosis ='<?php echo $getImplodedDiagnosis ?>';
        let diagnosisArray = diagnosis.substring(0, diagnosis.length).split(',');
        //alert(drugs);

        $("#patient_diagnosis").val(diagnosisArray).trigger('change');



    </script>
@endif
</body>
</html>
