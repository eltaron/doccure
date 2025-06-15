<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientDashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\DoctorDashboardController;
use App\Http\Controllers\StoreDataController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware('auth')->get('patient/dashboard', [PatientDashboardController::class, 'index'])->name('patient.dashboard');
Route::patch('/appointments/{id}/cancel', [PatientDashboardController::class, 'cancelAppointment'])->name('appointments.cancel');
Route::get('patient/dashboard', [PatientDashboardController::class, 'showDashboard'])->middleware('auth')->name('patient.dashboard');
Route::get('appointment/{id}/cancel', [AppointmentController::class, 'cancel'])->name('appointment.cancel');






// doctor
// Profile Settings
// Route::get('/doctor/profile-settings', [DoctorProfileController::class, 'edit'])->name('doctor.profile.settings');
// Route::post('/doctor/profile-settings', [DoctorProfileController::class, 'update'])->name('doctor.profile.update');

// Appointments Routes
Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
Route::post('/appointments/accept/{id}', [AppointmentController::class, 'accept'])->name('appointments.accept');
Route::post('/appointments/cancel/{id}', [AppointmentController::class, 'cancel'])->name('appointments.cancel');




Route::get('/doctor_register', [DoctorDashboardController::class, 'showDoctorRegisterForm'])->name('doctor_register');
Route::post('/doctor/register', [DoctorDashboardController::class, 'registerDoctor'])->name('doctor.register.submit');
Route::get('doctor_login', [DoctorDashboardController::class, 'showLoginForm'])->name('doctor_login');
Route::post('doctor_login', [DoctorDashboardController::class, 'login'])->name('doctor.login.submit');
Route::get('/doctor/dashboard', [DoctorDashboardController::class, 'dashboard'])->name('doctor.dashboard');
Route::get('doctor/{id}/profile', [DoctorDashboardController::class, 'profile'])->name('doctor.profile');
Route::get('/firebase-simulator', function () {
    return view('simulator_firebase');
});
Route::post('/store-sensor-data', [StoreDataController::class, 'store'])->name('store-sensor-data');
