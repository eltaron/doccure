@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Welcome, Dr. {{ $doctor->name }}</h1>
    <p>Specialization: {{ $doctor->specialization }}</p>
    <p>Email: {{ $doctor->email }}</p>
    <p>Birthday: {{ $doctor->birthday }}</p>
    <p>Website: <a href="{{ $doctor->site }}" target="_blank">{{ $doctor->site }}</a></p>

    @if($doctor->image)
        <img src="{{ asset('assets/img/doctors/' . $doctor->image) }}" alt="Doctor Image" width="150">
    @endif

    <hr>

    <p><strong>Total Appointments:</strong> {{ $totalAppointments }}</p>
    <p><strong>Total Unique Patients:</strong> {{ $totalPatients }}</p>

    <h3>Recent Appointments</h3>
    <ul>
        @forelse($appointments as $appointment)
            <li>
                Date: {{ $appointment->date }} |
                Time: {{ $appointment->time }} |
                Patient ID: {{ $appointment->patient_id }}
            </li>
        @empty
            <li>No appointments found.</li>
        @endforelse
    </ul>
</div>
@endsection
