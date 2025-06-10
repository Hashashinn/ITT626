<?php

use App\Models\Department;
use App\Models\Location;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\User;
use App\Models\UserActivityLogs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

if (!function_exists('isLogin')) {
    function isLogin()
    {
        $username = Auth::user()->id ?? '';

        if ($username) {
            $data = true;
        } else {
            $data = false;
        }

        return $data;
    }
}

if (!function_exists('getAllRoles')) {
    function getAllRoles()
    {
        $data = Role::get();

        if (!$data) {
            $data = [];
        }

        return $data;
    }
}

if (!function_exists('getAllLocation')) {
    function getAllLocation()
    {
        $data = Location::where('company_id', Auth::user()->company_id)->get();

        if (!$data) {
            $data = [];
        }

        return $data;
    }
}

if (!function_exists('uploadFile')) {
    function uploadFile($uploadedFile)
    {
        $file = file_get_contents($uploadedFile);
        $filename = time() . '-' . $uploadedFile->getClientOriginalName();

        $disk = 'attendance-photo';

        Storage::disk($disk)->put($filename, $file);

        $data['filename'] = $filename;

        return $data;
    }
}

// if (!function_exists('uploadFile')) {
//     function uploadFile($uploadedFile)
//     {
//         $filename = time() . '-' . $uploadedFile->getClientOriginalName();

//         Storage::disk('local')->put(
//             'public/attendance/' . $filename,
//             file_get_contents($uploadedFile),
//         );

//         $data['filename'] = $filename;

//         return $data;
//     }
// }

if (!function_exists('getAllDepartment')) {
    function getAllDepartment()
    {
        $data = Department::where('company_id', Auth::user()->company_id)->get();

        if (!$data) {
            $data = [];
        }

        return $data;
    }
}

if (!function_exists('getAllSchedule')) {
    function getAllSchedule()
    {
        $data = Schedule::where('company_id', Auth::user()->company_id)->get();

        if (!$data) {
            $data = [];
        }

        return $data;
    }
}


if (!function_exists('generateToken')) {
    function generateToken()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $tokens = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 9; $i++) {
            $n = rand(0, $alphaLength);
            $tokens[] = $alphabet[$n];
        }

        $token = implode($tokens); //turn the array into a string

        return $token;
    }
}

if (!function_exists('createActionLogUser')) {
    function createActionLogUser($logData = [])
    {
        $data = UserActivityLogs::create($logData);

        if (!$data) {
            $data = [];
        }

        return $data;
    }
}

if (!function_exists('getAllUsers')) {
    function getAllUsers()
    {
        $data = User::where('company_id', Auth::user()->company_id)->get();

        if (!$data) {
            $data = [];
        }

        return $data;
    }
}

if (!function_exists('getDepartmentById')) {
    function getDepartmentById($id = '')
    {
        $data = Department::find($id);

        if (!$data) {
            $data = [];
        }

        return $data;
    }
}

if (!function_exists('getLocationById')) {
    function getLocationById($id = '')
    {
        $data = Location::find($id);

        if (!$data) {
            $data = [];
        }

        return $data;
    }
}