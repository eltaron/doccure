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
                    <a href="{{ url('/') }}" class="navbar-brand logo">
                        <img src="/assets/img/logo.png" class="img-fluid" alt="Logo">
                    </a>
                </div>
                <div class="main-menu-wrapper">
                    <div class="menu-header">
                        <a href="{{ url('/') }}" class="menu-logo">
                            <img src="/assets/img/logo.png" class="img-fluid" alt="Logo">
                        </a>
                        <a id="menu_close" class="menu-close" href="javascript:void(0);">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                    <ul class="main-nav">
                        <li class="active">
                            <a href="{{ url('/') }}">Home</a>
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
                        <a class="nav-link header-login" href="{{ route('login') }}">login / Signup</a>
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
                            
                        <!-- Account Content -->
                        <div class="account-content">
                            <div class="row align-items-center justify-content-center">
                                <div class="col-md-7 col-lg-6 login-left">
                                    <img src="/assets/img/login-banner.png" class="img-fluid" alt="Login Banner">    
                                </div>
                                <div class="col-md-12 col-lg-6 login-right">
                                    <div class="login-header">
                                        <h3>Doctor Register <a href="{{ route('register') }}">Not a Doctor?</a></h3>
                                    </div>
                                    
                                    <!-- Register Form -->
                                    <form method="POST" action="{{ route('doctor.dashboard') }}">
                                        @csrf
                                        <div class="form-group form-focus">
                                            <input type="text" class="form-control floating @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                            <label class="focus-label">Name</label>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group form-focus">
                                            <input type="email" class="form-control floating @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                            <label class="focus-label">Email Address</label>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group form-focus">
                                            <input type="password" class="form-control floating @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                            <label class="focus-label">Create Password</label>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group form-focus">
                                            <input type="password" class="form-control floating" name="password_confirmation" required autocomplete="new-password">
                                            <label class="focus-label">Confirm Password</label>
                                        </div>
                                        <div class="text-right">
                                            <a class="forgot-link" href="{{ route('doctor_login') }}">Already have an account?</a>
                                        </div>
                                        <button class="btn btn-primary btn-block btn-lg login-btn" type="submit">Signup</button>
                                    </form>
                                    <!-- /Register Form -->
                                    
                                </div>
                            </div>
                        </div>
                        <!-- /Account Content -->
                                
                    </div>
                </div>

            </div>

        </div>		
        <!-- /Page Content -->

    </div>
    <!-- /Main Wrapper -->

    <!-- jQuery -->
    <script src="assets/js/jquery.min.js"></script>
    
    <!-- Bootstrap Core JS -->
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    
    <!-- Custom JS -->
    <script src="assets/js/script.js"></script>
    
</body>
@endsection
