<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class PatientDashboardController extends Controller
{
    /**
     * Show the dashboard for the patient.
     * This method fetches the appointments and passes them to the view.
     */
    public function showDashboard()
    {
        // Fetch the currently authenticated patient
        $patient = Auth::user(); // You can also use auth()->user()

        // Fetch the appointments for the authenticated patient using the relationship
        $appointments = $patient->appointments; // Assuming you have a method in the User model for appointments

        // Return the view with the appointments data
        return view('patient.dashboard', compact('patient', 'appointments'));
    }

    /**
     * Cancel an appointment by changing its status.
     */
    public function cancelAppointment($id)
    {
        // Find the appointment by its ID
        $appointment = Appointment::find($id);

        // Check if the appointment exists and belongs to the authenticated patient
        if ($appointment && $appointment->user_id == auth()->id() && $appointment->status != 'Cancelled') {
            // Update the appointment status to "Cancelled"
            $appointment->status = 'Cancelled';
            $appointment->save();
        }

        // Redirect back to the dashboard
        return back();
    }
}
