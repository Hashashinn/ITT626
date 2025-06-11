<?php

namespace App\Services;

use App\Imports\DepartmentImport;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\RolesImport;
use App\Imports\LocationsImport;
use App\Imports\UsersImport;
use App\Models\EmployeeHistory;

class RoleServices
{
    public function uploadExcel($r, $type = '')
    {
        if ($type == 'role') {
            $classImport = new RolesImport;
        } else if ($type == 'location') {
            $classImport = new LocationsImport;
        } else if ($type == 'department') {
            $classImport = new DepartmentImport;
        } else if ($type == 'Employee') {
            $classImport = new UsersImport;
        }

        Excel::import($classImport, $r->file('excel'));

        // for user import
        $users = User::where('pin_code', 1)->get();

        foreach ($users as $user) {
            // insert history employee
            $historyData = [
                'user_id' => $user->id,
                'department_id' => $user->department_id,
                'schedule_id' => $user->schedule_id,
                'location_id' => $user->location_id,
            ];

            EmployeeHistory::create($historyData);

            // update pin code to null
            $userData = [
                'pin_code' => null
            ];

            User::find($user->id)->update($userData);
        }

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Success Create.';

        return $data;
    }
}