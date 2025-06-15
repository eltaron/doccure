<?php

namespace App\Http\Controllers\auth;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class DoctorDashboardController extends Controller
{
    public function showDoctorRegisterForm()
    {
        return view('auth.doctor_register');
    }

    public function registerDoctor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email',
            'password' => 'required|string|min:8|confirmed',
            'specialization' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'site' => 'nullable|url',
            'birthday' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->route('doctor_register')
                ->withErrors($validator)
                ->withInput();
        }

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('assets/img/doctors'), $imageName);
        }

        $doctor = Doctor::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'specialization' => $request->specialization,
            'image' => $imageName,
            'site' => $request->site,
            'birthday' => $request->birthday,
        ]);

        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }

    public function showLoginForm()
    {
        return view('auth.passwords.doctor_login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::guard('doctor')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ], $request->remember)) {
            return redirect()->route('doctor.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

  public function dashboard()
{
    $doctor = Auth::guard('doctor')->user();

    if (!$doctor) {
        return redirect()->route('doctor.login')->withErrors([
            'auth' => 'You must be logged in as a doctor to access the dashboard.'
        ]);
    }

    $appointments = \App\Models\Appointment::where('doctor_id', $doctor->id)->latest()->take(10)->get();
    $totalAppointments = $appointments->count();
    $totalPatients = $appointments->pluck('patient_id')->unique()->count();

    return view('doctor.dashboard', compact('doctor', 'appointments', 'totalAppointments', 'totalPatients'));
}
public function showDashboard()
{
    $doctor = Auth::guard('doctor')->user();

    if (!$doctor) {
        return redirect()->route('doctor.login')->withErrors([
            'auth' => 'You must be logged in to view your dashboard.'
        ]);
    }

    $appointments = $doctor->appointments; // Assumes you defined appointments() in Doctor model

    return view('doctor.dashboard', compact('doctor', 'appointments'));
}

/**
 * Cancel an appointment by updating its status.
 */
public function cancelAppointment($id)
{
    $appointment = Appointment::find($id);

    if ($appointment && $appointment->doctor_id == Auth::guard('doctor')->id() && $appointment->status != 'Cancelled') {
        $appointment->status = 'Cancelled';
        $appointment->save();
    }

    return back();
}

    
}
