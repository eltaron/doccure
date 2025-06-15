<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Appointment;

class Doctor extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'image', 'specialization', 'site', 'birthday',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }
}
