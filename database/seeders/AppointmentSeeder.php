<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Doctor;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    public function run()
    {
        // Get a user and a doctor (assuming you have some users and doctors in the database)
        $user = User::first(); // You can adjust this as needed to get a specific user
        $doctor = Doctor::first(); // Adjust this to get a specific doctor

        // Create sample appointments
        Appointment::create([
            'user_id' => $user->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => Carbon::tomorrow()->toDateString(),
            'appointment_time' => '10:00:00',
            'booking_date' => Carbon::now(),
            'amount' => 50.00,
            'status' => 'Pending',
        ]);
    }
}
