@extends('layouts.app')

@section('content')
<body class="account-page">

    <!-- Main Wrapper -->
    <div class="main-wrapper">
    
        <!-- Header -->
        <header class="header">
            <nav class="navbar navbar-expand-lg header-nav">
                <div class="navbar-header">
                    <a id="mobile_btn" href="javascript:void(0);">
                        <span class="bar-icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </a>
                    <a href="{{ route('home') }}" class="navbar-brand logo">
                        <img src="{{ asset('assets/img/logo.png') }}" class="img-fluid" alt="Logo">
                    </a>
                </div>
                <div class="main-menu-wrapper">
                    <div class="menu-header">
                        <a href="{{ route('home') }}" class="menu-logo">
                            <img src="{{ asset('assets/img/logo.png') }}" class="img-fluid" alt="Logo">
                        </a>
                        <a id="menu_close" class="menu-close" href="javascript:void(0);">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                    <ul class="main-nav">
                        <li class="active">
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        
                        <li class="login-link">
                            <a href="{{ route('login') }}">Login / Signup</a>
                        </li>
                    </ul>		 
                </div>		 
                <ul class="nav header-navbar-rht">
                    <li class="nav-item contact-item">
                        <div class="header-contact-img">
                            <i class="far fa-hospital"></i>							
                        </div>
                        <div class="header-contact-detail">
                            <p class="contact-header">Contact</p>
                            <p class="contact-info-header"> +1 315 369 5943</p>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link header-login" href="{{ route('login') }}">Login / Signup</a>
                    </li>
                </ul>
            </nav>
        </header>
        <!-- /Header -->
        
        <!-- Page Content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <!-- Login Tab Content -->
                        <div class="account-content">
                            <div class="row align-items-center justify-content-center">
                                <div class="col-md-7 col-lg-6 login-left">
                                    <img src="{{ asset('assets/img/login-banner.png') }}" class="img-fluid" alt="Doccure Login">    
                                </div>
                                <div class="col-md-12 col-lg-6 login-right">
                                    <div class="login-header">
                                        <h3>Login <span>Doccure</span> Patient</h3>
                                    </div>
                                    
                                    <!-- Login Form -->
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf

                                        <div class="form-group form-focus">
                                            <input type="email" class="form-control floating @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                            <label class="focus-label">Email</label>
                                            
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group form-focus">
                                            <input type="password" class="form-control floating @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                            <label class="focus-label">Password</label>

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <button class="btn btn-primary btn-block btn-lg login-btn" type="submit">Login</button>

                                        <div class="text-center dont-have">Don’t have an account? <a href="{{ route('register') }}">Register</a></div>
                                    </form>
                                    <!-- /Login Form -->
                                </div>
                            </div>
                        </div>
                        <!-- /Login Tab Content -->
                    </div>
                </div>
            </div>
        </div>		
        <!-- /Page Content -->
    </div>
    <!-- /Main Wrapper -->

</body>
@endsection
