<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Services\EmailServices;
use App\Services\LoginServices;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    public function loginProcess(Request $r)
    {
        $ls = new LoginServices;

        $result = $ls->loginProcess($r);

        return response()->json($result);
    }

    public function loginView()
    {

        $result = isLogin();
        if ($result) {
            return redirect('/admin');
        }

        return view('auth.login');
    }

    public function logout()
    {
        $ls = new LoginServices;

        // reset is login 
        $ls->resetPinCode();

        Auth::logout();
        Session::flush();

        return redirect('/auth/loginView');
    }

    public function sendResetPasswordEmail(Request $r)
    {
        $es = new EmailServices;

        $result = $es->sendResetPasswordEmail($r);

        return $result;
    }

    public function resetPasswordView($token = '')
    {

        // return view('') view for reset password
        return view('auth.passwords.resetPasswordForm');
    }

    // reset password update function
    public function updateResetPassword(Request $r, $resetPassToken = "")
    {
        $ls = new LoginServices;

        $result = $ls->updateResetPassword($r, $resetPassToken);

        return response()->json($result);
    }

    public function requestResetPassword()
    {
        return view('auth.passwords.requestResetPassword');
    }

    public function heartbeat(Request $request)
    {
        // Update the last_activity session with the current timestamp to reset the idle timer
        $request->session()->put('last_activity', time());
    }
}