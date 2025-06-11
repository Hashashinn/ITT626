<?php

namespace App\Services;

use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DepartmentServices
{
    public function getAllDepartment()
    {
        $cond[0] = ['id', '!=', ''];

        if (Auth::user()->role->role_id != 3) {
            $cond[1] = ['company_id', Auth::user()->company_id];
        }

        $data = Department::where($cond)->get();

        return $data;
    }
    public function getDepartmentById($id = '')
    {
        $data = Department::find($id);

        return $data;
    }

    public function departmentStore($r)
    {
        $input = $r->input();
        unset($input['_token']);
        $DepartmentData = Department::where('name', $input['name'])->where('company_id',Auth::user()->company_id)->first();

        if ($DepartmentData) {
            $data['status'] = config('app.response.error.status');
            $data['type'] = config('app.response.error.type');
            $data['title'] = config('app.response.error.title');
            $data['msg'] = 'The Department already exists.';

            return $data;
        }

        $input['company_id'] = Auth::user()->company_id;

        Department::create($input);

        $logData = [
            'user_id' => Auth::user()->id,
            'desc' => 'Create new department: ' . $input['name'],
        ];
        createActionLogUser($logData);

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Success Create.';

        return $data;
    }

    public function departmentUpdate($r, $id)
    {
        $input = $r->input();

        unset($input['_token']);

        Department::where('id', $id)->update($input);
        $logData = [
            'user_id' => Auth::user()->id,
            'desc' => 'Update department : ' . $input['name'],
        ];

        createActionLogUser($logData);

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Success Update.';


        return $data;
    }

    public function departmentDelete($r,$id)
    {
        // $input = $r->input();
        // unset($input['_token']);
        $department = Department::find($id);
        if (!$department) {
            $data['status'] = config('app.response.success.status');
            $data['type'] = config('app.response.success.type');
            $data['title'] = config('app.response.success.title');
            $data['code'] = 404;
            $data['msg'] = 'Department Not Found';

            return $data;
        }
        $department->delete();

        $logData = [
            'user_id' => Auth::user() ? Auth::user()->id : null,
            'desc' => 'Delete department : ' . $id,
        ];

        createActionLogUser($logData);

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['code'] = 200;
        $data['msg'] = 'Success Delete.';


        return $data;
    }
}