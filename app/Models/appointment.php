<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Doctor;
use App\Models\User;

class Appointment extends Model
{
    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'patient_id', 
        'doctor_id', 
        'appointment_date', 
        'appointment_time', 
        'booking_date', 
        'amount', 
        'status'
    ];

    /**
     * Define the relationship with the Doctor model.
     * An appointment belongs to one doctor.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    /**
     * Define the relationship with the Patient model.
     * An appointment belongs to one patient (user).
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
    // In the Appointment model (App\Models\Appointment)

public function user()
{
    return $this->belongsTo(User::class, 'user_id'); // or 'patient_id' if your foreign key is 'patient_id'
}


}
