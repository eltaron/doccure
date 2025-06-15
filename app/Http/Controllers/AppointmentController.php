<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Show the list of appointments for the authenticated user.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch the currently authenticated user
        $user = Auth::user();

        // Fetch the appointments for the authenticated user
        $appointments = Appointment::where('user_id', $user->id)
            ->with('doctor')  // Load doctor information along with appointments
            ->get();

        // Return the user dashboard view with the appointments data
        return view('patient.dashboard', compact('user', 'appointments'));
    }

    /**
     * Show the form to create a new appointment.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Get all available doctors
        $doctors = Doctor::all();

        // Return the view to create a new appointment
        return view('patient.create-appointment', compact('doctors'));
    }

    /**
     * Store a new appointment.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'amount' => 'required|numeric',
        ]);

        // Create the new appointment
        $appointment = Appointment::create([
            'user_id' => Auth::id(),
            'doctor_id' => $request->doctor_id,
            'appointment_date' => Carbon::parse($request->appointment_date),
            'appointment_time' => $request->appointment_time,
            'booking_date' => Carbon::now(),
            'amount' => $request->amount,
            'status' => 'Pending',
        ]);

        // Redirect to the user's dashboard with a success message
        return redirect()->route('patient.dashboard')->with('success', 'Appointment booked successfully!');
    }

    /**
     * Show the details of a single appointment.
     *
     * @param \App\Models\Appointment $appointment
     * @return \Illuminate\View\View
     */
    public function show(Appointment $appointment)
    {
        // Ensure the authenticated user is the one who made the appointment
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Return the appointment details view
        return view('user.appointment-detail', compact('appointment'));
    }

    /**
     * Cancel an existing appointment.
     *
     * @param \App\Models\Appointment $appointment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Appointment $appointment)
    {
        // Ensure the authenticated user is the one who made the appointment
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if the appointment is not already canceled
        if ($appointment->status != 'Cancelled') {
            $appointment->status = 'Cancelled';
            $appointment->save();
        }

        // Redirect back to the user's dashboard with a success message
        return redirect()->route('patient.dashboard')->with('success', 'Appointment cancelled successfully.');
    }

    /**
     * Reschedule an existing appointment.
     *
     * @param \App\Models\Appointment $appointment
     * @return \Illuminate\View\View
     */
    public function edit(Appointment $appointment)
    {
        // Ensure the authenticated user is the one who made the appointment
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Get all available doctors for rescheduling
        $doctors = Doctor::all();

        // Return the edit appointment form
        return view('user.edit-appointment', compact('appointment', 'doctors'));
    }

    /**
     * Update the appointment after rescheduling.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Appointment $appointment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Appointment $appointment)
    {
        // Ensure the authenticated user is the one who made the appointment
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the incoming request data
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'amount' => 'required|numeric',
        ]);

        // Update the appointment
        $appointment->update([
            'doctor_id' => $request->doctor_id,
            'appointment_date' => Carbon::parse($request->appointment_date),
            'appointment_time' => $request->appointment_time,
            'amount' => $request->amount,
            'status' => 'Pending', // Reset status to Pending on reschedule
        ]);

        // Redirect to the user's dashboard with a success message
        return redirect()->route('patient.dashboard')->with('success', 'Appointment rescheduled successfully!');
    }
}
