@extends('layouts.login')

@section('login_content')
{{--<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>--}}

<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper  align-items-center auth auth-bg-1 theme-one">
            <div class="row mt-5 w-100 mx-auto">
                <div class="col-lg-4  mt-5 mx-auto text-center">
                    <p class="text-white" style="font-size: 1.5rem; font-weight: 100">Patient's Resource Management System</p>

                </div>
            </div>
            <div class="row w-100 mx-auto">
                <div class="col-lg-3 mx-auto text-center">
                    <div class="auto-form-wrapper">
                        <div class="text-center mb-2">
                            <img height="auto" width="200" src="{{asset('public/images/logo.jpeg')}}" class="img-fluid" alt="">
                        </div>
                        <form novalidate class="needs-validation" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group mb-1">
                                <div class="input-group">
                                    <input type="text" style="border-radius: 0" placeholder="Username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="icon-user"></i></span>
                                    </div>
                                    @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group mb-1">
                                <div class="input-group">
                                    <input type="password" style="border-radius: 0"  id="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="icon-lock"></i></span>
                                    </div>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary submit-btn btn-block">Login</button>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="form-check form-check-flat mt-0">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input ml-0" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <small> <a class="text-dark mt-0" href="javascript:void(0)">Forgot Password</a></small>
                    </div>

                    <ul class="auth-footer">
{{--                        <li><a href="{{ route('password.request') }}">Forgot Password</a></li>--}}
                        <li><a class="text-white" href="javascript:void(0)" style="text-decoration: none">© {{date('Y')}} GC Health Center</a></li>
                        <li><a class="text-white" href="javascript:void(0)" style="text-decoration: none">By ANA Technologies</a></li>
                    </ul>
                    {{--<p class="footer-text text-center mt-1 mb-0">© {{date('Y')}} GC Health Center. All rights reserved.</p>
                    <small class="footer-text text-center mt-0">Powered by ANA Technologies</small>--}}
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>
@endsection
