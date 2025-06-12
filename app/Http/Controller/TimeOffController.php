<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\LeaveApplicationEmail;
use Illuminate\Support\Facades\Mail;

class TimeOffController extends Controller
{
    //
    public function store(Request $request)
    {
        $duration = $request->input('duration');
        $punchIn = $request->input('punchIn');
        $halfDay = $request->has('halfDay');

        // Perform additional logic, such as sending email and storing data in the database

        // Send email for leave application
        if ($halfDay) {
            // Send half-day leave application email
            $email = $request->user()->email; // Assuming you have user authentication
            Mail::to($email)->send(new LeaveApplicationEmail($duration, true));
        } else {
            // Send full-day leave application email
            $email = $request->user()->email; // Assuming you have user authentication
            Mail::to($email)->send(new LeaveApplicationEmail($duration, false));
        }

    }
}
