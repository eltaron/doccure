<!-- resources/views/dashboard/index.blade.php -->
@extends('layouts.app')

@section('content')

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
                            @auth
                                <a href="{{ route('home') }}">{{ Auth::user()->name }}</a>
                            @else
                                <a href="{{ route('login') }}">Login / Signup</a>
                            @endauth
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
                    @auth
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link">Logout</button>
                        </form>
                    </li>
                    @endauth
                </ul>
            </nav>
        </header>
        <!-- /Header -->

        <!-- Breadcrumb -->
        <div class="breadcrumb-bar">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-12 col-12">
                        <nav aria-label="breadcrumb" class="page-breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                            </ol>
                        </nav>
                        <h2 class="breadcrumb-title">Dashboard</h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Breadcrumb -->
        
        <!-- Page Content -->
        <div class="content">
            <div class="container-fluid">

                <div class="row">
                    
                    <!-- Profile Sidebar -->
                    <div class="col-md-5 col-lg-4 col-xl-3 theiaStickySidebar">
                        <div class="profile-sidebar">
                            <div class="widget-profile pro-widget-content">
                                <div class="profile-info-widget">
                                    <a href="#" class="booking-doc-img">
                                        <img src="{{ asset('assets/img/patients/patient.jpg') }}" alt="User Image">
                                    </a>
                                    <div class="profile-det-info">
                                        <h3>{{ Auth::user()->name }}</h3>
                                        <div class="patient-details">
                                            <h5><i class="fas fa-birthday-cake"></i> {{ Auth::user()->birth_date }}, {{ \Carbon\Carbon::parse(Auth::user()->birth_date)->age }} years</h5>
                                            <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> {{ Auth::user()->location }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dashboard-widget">
                                <nav class="dashboard-menu">
                                    <ul>
                                        <li>
                                            <a href="{{ route('home') }}">
                                                <i class="fas fa-columns"></i>
                                                <span>Dashboard</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('home') }}">
                                                <i class="fas fa-user-md"></i>
                                                <span>Doctors</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('patient.dashboard') }}">
                                                <i class="fas fa-calendar-check"></i>
                                                <span>Appointments</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('home') }}">
                                                <i class="fas fa-user-cog"></i>
                                                <span>Profile Settings</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('home') }}">
                                                <i class="fas fa-lock"></i>
                                                <span>Change Password</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <!-- / Profile Sidebar -->

                    <div class="col-md-7 col-lg-8 col-xl-9">
                        <div class="card">
                            <div class="card-body pt-0">
                            
                                <!-- Tab Menu -->
                                <nav class="user-tabs mb-4">
                                    <ul class="nav nav-tabs nav-tabs-bottom nav-justified">
                                        <li class="nav-item">
                                            <a class="nav-link active" href="#pat_appointments" data-toggle="tab">Appointments</a>
                                        </li>
                                    </ul>
                                </nav>
                                <!-- /Tab Menu -->
                                
                                <!-- Tab Content -->
                                <div class="tab-content pt-0">
                                    
                                    <!-- Appointment Tab -->
                                    <div id="pat_appointments" class="tab-pane fade show active">
                                        <div class="card card-table mb-0">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-center mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th>Doctor</th>
                                                                <th>Appt Date</th>
                                                                <th>Booking Date</th>
                                                                <th>Amount</th>
                                                                <th>Status</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- Example Appointment Row -->
                                                            @foreach ($appointments as $appointment)
                                                            <tr>
                                                                <td>
                                                                    <h2 class="table-avatar">
                                                                        <a href="{{ route('doctor.profile', $appointment->doctor->id) }}" class="avatar avatar-sm mr-2">
                                                                            <img class="avatar-img rounded-circle" src="{{ asset('assets/img/doctors/'.$appointment->doctor->avatar) }}" alt="User Image">
                                                                        </a>
                                                                        <a href="{{ route('doctor.profile', $appointment->doctor->id) }}">{{ $appointment->doctor->name }} <span>{{ $appointment->doctor->specialty }}</span></a>
                                                                    </h2>
                                                                </td>
                                                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }} <span class="d-block text-info">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}</span></td>
                                                                <td>{{ \Carbon\Carbon::parse($appointment->created_at)->format('d M Y') }}</td>
                                                                <td>${{ $appointment->amount }}</td>
                                                                <td><span class="badge badge-pill {{ $appointment->status == 'Confirm' ? 'bg-success-light' : 'bg-danger-light' }}">{{ $appointment->status }}</span></td>
                                                                <td class="text-right">
                                                                    <div class="table-action">
                                                                        @if($appointment->status == 'Confirm')
                                                                            <a href="javascript:void(0);" class="btn btn-sm bg-danger-light">
                                                                                <i class="far fa-trash-alt"></i> Cancel
                                                                            </a>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Appointment Tab -->
                                    
                                </div>
                                <!-- Tab Content -->
                                
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>      
        <!-- /Page Content -->
    </div>

@endsection
