<?php

namespace App\Services;

use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginServices
{
    public function loginProcess($r)
    {
        $input = $r->input();
        $userData = User::where([['email', $input['email']]])->first();
        unset($input['_token']);
        Auth::attempt($input);

        if (!$userData) {
            $data['status'] = config('app.response.error.status');
            $data['type'] = config('app.response.error.type');
            $data['title'] = config('app.response.error.title');
            $data['msg'] = 'User not found';

            return $data;
        }

        // if ($userData->pin_code) {
        //     $data['status'] = config('app.response.error.status');
        //     $data['type'] = config('app.response.error.type');
        //     $data['title'] = config('app.response.error.title');
        //     $data['msg'] = 'System is not allowed to login multiple time. Please logout first from previous login and try again';

        //     return $data;
        // }
        $userToken = $userData->createToken('API for ' . $userData->name)->plainTextToken;

        session(['token' => $userToken]);

        if (Hash::check($input['password'], $userData->password)) {
            $data['status'] = config('app.response.success.status');
            $data['type'] = config('app.response.success.type');
            $data['title'] = config('app.response.success.title');
            $data['msg'] = 'Authorized!';

            // // record data isLogin
            // $userData = [
            //     'pin_code' => 'isLogin'
            // ];

            // User::find(Auth::user()->id)->update($userData);

            return $data;
        } else {
            $data['status'] = config('app.response.error.status');
            $data['type'] = config('app.response.error.type');
            $data['title'] = config('app.response.error.title');
            $data['msg'] = 'Password and user name not match    ';

            return $data;
        }
    }

    public function updateResetPassword($r, $token)
    {
        // Retrieve the user by the reset password token
        $user = User::where('reset_pass_token', $token)->first();

        $array_update_data = [
            'reset_pass_token' => null,
            'password' => Hash::make($r->password)
        ];

        $user->update($array_update_data);

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Password has been reset';

        return $data;
    }

    public function resetPinCode()
    {
        if (Auth::check()) {

            $user = Auth::user();
            if ($user->pin_code !== null) {
                // If pin_code is already null, no need to reset it, so return early

                // record data isLogin
                $userData = [
                    'pin_code' => NULL
                ];
                User::find(Auth::user()->id)->update($userData);
            }
        }
    }
}
