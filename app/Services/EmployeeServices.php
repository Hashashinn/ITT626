<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\EmployeeHistory;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeeServices
{
    public function getAllEmployees($branch = '', $department = '', $r)
    {

        $cond[0] = ['id', '!=', ''];

        if ($branch) {
            $cond[1] = ['location_id', $branch];
        }

        if ($department) {
            $cond[2] = ['department_id', $department];
        }

        if (Auth::user()->role->role_id != 3) {
            $cond[3] = ['company_id', Auth::user()->company->id];
        }

        if ($r->department_id) {
            $cond[4] = ['department_id', $r->input('department_id')];
        }

        if ($r->location_id) {
            $cond[5] = ['location_id', $r->input('location_id')];
        }

        $data = User::where($cond)->get();

        return $data;
    }

    public function getAllEmployeesByDepartment($departmentId)
    {
        $data = User::where('department_id', $departmentId)->get();

        return $data;
    }

    public function getAllEmployeesByLocation($locationId)
    {
        $data = User::where('location_id', $locationId)->get();

        return $data;
    }

    public function employeeStore($r)
    {
        $input = $r->input();
        unset($input['_token']);

        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 9; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        // check user exist
        $userCheck = User::where('email', $input['user']['email'])->first();

        if ($userCheck) {
            $data['status'] = config('app.response.error.status');
            $data['type'] = config('app.response.error.type');
            $data['title'] = config('app.response.error.title');
            $data['msg'] = 'Email already exists.';

            return $data;
        }

        $password = implode($pass); //turn the array into a string

        $input['user']['password'] = Hash::make($password);
        $input['user']['company_id'] = Auth::user()->company->id;

        User::create($input['user']);

        $userData = User::orderBy('created_at', 'DESC')->first();

        // role user data
        $roleUserData = [
            'role_id' => $input['role']['role_id'],
            'user_id' => $userData->id,
        ];

        $emailData = [
            'email' => $input['user']['email'],
            'password' => $password
        ];

        // email register email to user
        $em = new EmailServices;
        $em->sendRegisterEmail($emailData);

        RoleUser::create($roleUserData);

        // insert user log
        $logData = [
            'user_id' => Auth::user()->id,
            'desc' => 'Create new user ' . $input['user']['email'],
        ];

        createActionLogUser($logData);

        // insert history change employee
        $historyData = [
            'user_id' => $userData->id,
            'department_id' => $input['user']['department_id'],
            'schedule_id' => $input['user']['schedule_id'],
            'location_id' => $input['user']['location_id'],
        ];

        EmployeeHistory::create($historyData);

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Success Create.';

        return $data;
    }

    public function getemployeeById($id = '')
    {
        $data = User::with('role')->find($id);

        return $data;
    }
    public function employeeUpdate($r, $id)
    {
        $input = $r->input();
        unset($input['_token']);

        User::where('id', $id)->update($input['user']);
        RoleUser::where('user_id', $id)->update($input['role']);

        // insert user log
        $logData = [
            'user_id' => Auth::user()->id,
            'desc' => 'Update user id ' . $id,
        ];

        createActionLogUser($logData);

        // insert history change employee
        $historyData = [
            'user_id' => $id,
            'department_id' => $input['user']['department_id'],
            'schedule_id' => $input['user']['schedule_id'],
            'location_id' => $input['user']['location_id'],
        ];

        EmployeeHistory::create($historyData);

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Success Update.';


        return $data;
    }

    public function employeeDelete($r, $id)
    {
        User::where('id', $id)->delete();

        // insert  delete user log
        $logData = [
            'user_id' => Auth::user()->id,
            'desc' => 'Delete user id ' . $id,
        ];

        createActionLogUser($logData);

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Success Delete.';


        return $data;
    }

    public function employeeHistoryData(Request $r, $user_id ='', $department_id ='')
    {   
        $companyId = Auth::user()->company->id;
        $roleId = Auth::user()->role->role_id;

        $query = EmployeeHistory::with('department', 'location', 'schedule')
            ->whereHas('user', function ($query) use ($companyId, $roleId) {
                if ($roleId != 3) {
                    $query->where('company_id', $companyId);
                }
            });

            if ($r->has('user_id')) {
                $query->where('user_id', $r->input('user_id'));
            }
        
            if ($r->has('department_id')) {
                $query->where('department_id', $r->input('department_id'));
            }

        $result = $query->get();
        return $result;
    }
}