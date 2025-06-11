<?php

namespace App\Services;

use App\Mail\Email;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class EmailServices
{
    public function sendRegisterEmail($input = [])
    {
        $data = [];
        $receiver = $input['email'];
        $response['title'] = 'AMS Email';
        $response['typeEmail'] = 'registerUser';
        $response['from'] = env('MAIL_FROM_ADDRESS');
        $response['nameFrom'] = 'Admin';
        $response['subject'] = 'Admin Attendance';
        $response['data'] = $input;

        Mail::to($receiver)->send(new Email($response));

        return $data;
    }

    public function sendResetPasswordEmail($r)
    {
        $input = $r->input();

        // Find user data using email, return error user not found
        $user = User::where('email', $input['email'])->first();

        if (!$user) {
            // User not found, return error response
            $data = [
                'status' => config('app.response.error.status'),
                'type' => config('app.response.error.type'),
                'title' => config('app.response.error.title'),
                'msg' => 'User not found.',
            ];
            return $data;
        }


        // generate the reset password token
        $token = generateToken();
        // Update the reset_pass_token in users
        $user->reset_pass_token = $token;
        $user->save();


        // pass the token to email
        $input['token'] = $token ?? '';

        $data = [];

        $receiver = $input['email'];
        $response['title'] = 'AMS Email';
        $response['typeEmail'] = 'resetPassword';
        $response['from'] = env('MAIL_FROM_ADDRESS');
        $response['nameFrom'] = 'Admin';
        $response['subject'] = 'Admin Attendance';
        $response['data'] = $input;

        Mail::to($receiver)->send(new Email($response));

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Reset password link have been send to you. Please check your email';

        return $data;
    }

    public function sendReminderHalfDayEmail($receiver = '', $timeoffData = [])
    {
        $data = [];
        $receiver = $receiver;
        $response['title'] = 'AMS Email';
        $response['typeEmail'] = 'reminderHalfDay';
        $response['from'] = env('MAIL_FROM_ADDRESS');
        $response['nameFrom'] = 'Admin';
        $response['subject'] = 'Admin Attendance';
        $response['data'] = $timeoffData;

        Mail::to($receiver)->send(new Email($response));

        return $data;
    }

    public function PunchOutNotification($receiver = '', $data)
    {
        $data = [];
        $receiver = $receiver;
        $response['title'] = 'AMS Email';
        $response['typeEmail'] = 'punchOutNotification';
        $response['from'] = env('MAIL_FROM_ADDRESS');
        $response['nameFrom'] = 'Admin';
        $response['subject'] = 'Admin Attendance';
        $response['data'] = $data;

        Mail::to($receiver)->send(new Email($response));

        return $data;
    }
}