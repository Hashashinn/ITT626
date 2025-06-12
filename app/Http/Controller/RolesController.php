<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\RolesImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Imports\DepartmentImport;
use App\Imports\LocationsImport;
use App\Imports\UsersImport;
use App\Models\User;

class RolesController extends Controller
{

    public function import()
    {
        Excel::import(new RolesImport, 'storage/seeder/Roles.xlsx');

        return response()->json('success');
    }

    public function importDepartment()
    {
        Excel::import(new DepartmentImport, 'storage/seeder/Department.xlsx');

        return response()->json('success');
    }
    public function importLocation()
    {
        Excel::import(new LocationsImport, 'storage/seeder/Location.xlsx');

        return response()->json('success');
    }
    public function importUser()
    {
        Excel::import(new UsersImport, 'storage/seeder/User.xlsx');

        return response()->json('success');
    }
    public function getUserDepartment($userId)
    {
        $user = User::find($userId);

        if ($user) {
            $departmentId = $user->department_id;

            if ($departmentId) {
                echo "Department ID: " . $departmentId;
            } else {
                echo "Department ID not found for the user.";
            }
        } else {
            echo "User not found.";
        }
        // return response()->json(['department_id' => $departmentId]);
        return response()->json('success');
    }
}
