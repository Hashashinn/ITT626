<?php

namespace App\Services;

use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Schedule;
use App\Models\User;
use App\Models\UserActivityLogs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminServices
{
    public function getRoleById($id = '')
    {
        $data = Role::find($id);

        return $data;
    }

    public function store($r)
    {
        $input = $r->input();
        unset($input['_token']);
        $roleData = Role::where('name', $input['name'])->first();

        if ($roleData) {
            $data['status'] = config('app.response.error.status');
            $data['type'] = config('app.response.error.type');
            $data['title'] = config('app.response.error.title');
            $data['msg'] = 'The role already exists.';

            return $data;
        }


        Role::create($input);

        // insert user log
        $logData = [
            'user_id' => Auth::user()->id,
            'desc' => 'Create new role: ' . $input['name'],
        ];

        createActionLogUser($logData);

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Success Create.';

        return $data;
    }

    public function update($r, $id)
    {
        $input = $r->input();
        unset($input['_token']);

        Role::where('id', $id)->update($input);

        // insert   update user log
        $logData = [
            'user_id' => Auth::user()->id,
            'desc' => 'Update Role : ' . $id,
        ];

        createActionLogUser($logData);

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Success Update.';


        return $data;
    }

    public function delete($r, $id)
    {
        // $input = $r->input();
        // unset($input['_token']);
        Role::where('id', $id)->delete();

        // insert   update user log
        $logData = [
            'user_id' => Auth::user()->id,
            'desc' => 'Delete Role : ' . $id,
        ];

        createActionLogUser($logData);

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Success Delete.';


        return $data;
    }

    public function getAllRoles()
    {
        $data = Role::all();

        return $data;
    }

    public function registerStore($r)
    {
        $input = $r->input();
        $dataInputUser = $input['user'];
        unset($input['_token']);

        // validation
        $user = User::where('email', $dataInputUser['email'])->first();
        if ($user) {
            $data['status'] = config('app.response.error.status');
            $data['type'] = config('app.response.error.type');
            $data['title'] = config('app.response.error.title');
            $data['msg'] = 'User already exist';

            return $data;
        }

        $dataInputUser['password'] = Hash::make($dataInputUser['password']);
        User::create($dataInputUser);

        $userData = User::where('email', $dataInputUser['email'])->first();

        $roleUserData = [
            'user_id' => $userData->id,
            'role_id' => 1, // by default role employee
        ];

        RoleUser::create($roleUserData);

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Success Create.';

        return $data;
    }

    public function getScheduleById($id = '')
    {
        $data = Schedule::find($id);

        return $data;
    }

    public function scheduleStore($r)
    {
        $input = $r->input();
        $input['schedule']['company_id'] = Auth::user()->company_id;

        $scheduleData = Schedule::where('slug', $input['schedule']['slug'])->first();

        if ($scheduleData) {
            $data['status'] = config('app.response.error.status');
            $data['type'] = config('app.response.error.type');
            $data['title'] = config('app.response.error.title');
            $data['msg'] = 'The schedule already exists.';

            return $data;
        }


        Schedule::create($input['schedule']);

        // Insert user log
        $logData = [
            'user_id' => Auth::user()->id,
            'desc' => 'Create new Schedule: ' . $input['schedule']['slug'],
        ];

        createActionLogUser($logData);

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Success Create.';

        return $data;
    }

    public function scheduleUpdate($r, $id)
    {
        $input = $r->input();

        Schedule::where('id', $id)->update($input['schedule']);

        // Insert update schedule log
        $logData = [
            'user_id' => Auth::user()->id,
            'desc' => 'Update schedule  ' . $input['schedule']['slug'],
        ];

        createActionLogUser($logData);

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Success Update.';


        return $data;
    }

    public function scheduleDelete($r, $id)
    {
        // $input = $r->input();
        // unset($input['_token']);
        Schedule::where('id', $id)->delete();

        // Insert delete schedule log
        $logData = [
            'user_id' => Auth::user()->id,
            'desc' => 'Delete schedule :' . $id,
        ];

        createActionLogUser($logData);


        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Success Delete.';


        return $data;
    }

    public function getLogActionUser()
    {
        $companyId = Auth::user()->company_id;

        $data = UserActivityLogs::with('users')
            ->whereHas('users', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->get();

        return $data;
    }

    public function changePassword($r)
    {
        $password = Hash::make($r->password);
        $updateData = [
            'password' => $password
        ];

        User::find(Auth::user()->id)->update($updateData);

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Success Change Password.';

        return $data;
    }
}