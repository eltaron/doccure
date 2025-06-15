<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  // Foreign key to the users table (patients)
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');  // Foreign key to the doctors table
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->date('booking_date');
            $table->decimal('amount', 8, 2);  // Amount for the appointment
            $table->string('status')->default('Pending');  // Status of the appointment
            $table->timestamps();  // Created at and updated at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
