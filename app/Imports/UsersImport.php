<?php

namespace App\Imports;

use App\Models\EmployeeHistory;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $import = new User([
            'name' => $row['name'],
            'position' => $row['position'],
            'email' => $row['email'],
            'password' => Hash::make($row['password']),
            'pin_code' => 1,
            'company_id' => Auth::user()->company->id
        ]);

        $existingUser = User::where('email', $import->email)->first();

        if ($existingUser) {
            $existingUser->update([
                'name' => $import->name,
                'position' => $import->position,
                'password' => $import->password,
            ]);

            $role = Role::where('name', $row['role'])->first();
            if ($role) {
                $existingUser->roles()->sync([$role->id]);
            }

            return $existingUser;
        }

        // get user order by id desc
        $users = User::orderBy('id', 'desc')->get();

        $roleName = $row['role']; // Assuming the role name is specified in the 'role' column
        $role = Role::where('name', $roleName)->first();

        if ($role) {
            $userData = $import->toArray();
            $userData['password'] = Hash::make($row['password']); // Include password field
            $user = User::updateOrCreate(['email' => $userData['email']], $userData);
            $user->roles()->sync([$role->id]);
            return $user;
        }else {
            $defaultRole = Role::where('name', 'employee')->first();
            if ($defaultRole) {
                $userData = $import->toArray();
                $userData['password'] = Hash::make($row['password']);
                $user = User::updateOrCreate(['email' => $userData['email']], $userData);
                $user->roles()->sync([$defaultRole->id]);
                return $user;
            }
        }

        if ($role) {
            $import->roles()->attach($role);
        }

        return $import;
    }
}