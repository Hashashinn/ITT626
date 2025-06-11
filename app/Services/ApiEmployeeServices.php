<?php

namespace App\Services;

use App\Imports\UsersImport;
use App\Models\User;
use App\Models\EmployeeHistory;
use App\Models\Attendance;
use App\Models\Timeoff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ApiEmployeeServices
{
    public function getAllEmployee()
    {
        $employees = User::with(['company', 'location', 'department','schedule'])->get();
        $formattedEmployees = $employees->map(function($employee) {
            $createdAt = (new \DateTime($employee->created_at))->format('Y-m-d H:i:s');
            return [
                'id' => $employee->id,
                'name' => $employee->name,
                'department_name' => $employee->department->name ?? null,
                'position' => $employee->position,
                'schedule_name' => $employee->schedule->slug ?? null,
                'location_name' => $employee->location->location_name ?? null,
                'email' => $employee->email,
                'member_since' => $createdAt ?? null
            ];
        });
        return $formattedEmployees;
        
    }


    // public function uploadExcel(Request $data){

    //     $validated  = $data->validate([
    //         'file' => 'required|mimes:xlsx,xls',
    //     ]);

    //     Excel::import(new UsersImport, $data->file('file'));

    //     return $validated;

    // }


    public function addEmployee(array $data)
    {
        $employees=[];

        foreach ($data as $employeeData) {
                
            if (isset($employeeData['password'])) {
                $employeeData['password'] = Hash::make($employeeData['password']);
            }
            $employee = new User();
            $employee->fill($employeeData);
            $employee->save();
        }
        $employees[] = $employee;

        $userData = User::orderBy('created_at', 'DESC')->first();

        foreach ($data as $inputData) {

            $historyData = [
                        'user_id' => $userData->id,
                        'department_id' => $inputData['department_id'],
                        'schedule_id' => $inputData['schedule_id'],
                        'location_id' => $inputData['location_id'],
                    ];
            
            EmployeeHistory::create($historyData);
        }

        return $employees;
    }

    public function editEmployee(array $data)
{
    $employees = [];

    if (is_array($data)) {
        foreach ($data as $userData) {
            if (isset($userData['id'])) {
                $user = User::select('id')->where('id',$userData['id'])->first();
                if ($user) {
                    $user->update([
                        'location_id' => $userData['location_id'] ?? $user->location_id,
                        'department_id' => $userData['department_id'] ?? $user->department_id,
                        'schedule_id' => $userData['schedule_id'] ?? $user->schedule_id,
                        'name' => $userData['name'] ?? $user->name,
                        'email' => $userData['email'] ?? $user->email,
                        'position' => $userData['position'] ?? $user->position,
                        // 'password' => $userData['password'] ?? $user->password,
                        'updated_at' => $userData['updated_at'] ?? $user->updated_at,
                    ]);

                $user = User::with('location','department', 'schedule')->where('id',$userData['id'])->first();

                $userData = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'position' => $user->position,
                    'department_name' => $user->department->name,
                    'schedule_name' => $user->schedule->slug,
                    'location_name' => $user->location->location_name,
                    'email' => $user->email,
                    'member_since' => $user->created_at,
                ];
                    $employees[] = $userData;
                }
            }
        }

        foreach ($data as $inputData) {
            if (isset($inputData['id'])) { // Ensure $inputData contains necessary fields
                $historyData = [
                    'user_id' => $inputData['id'], // Use $inputData['id'] here
                    'department_id' => $inputData['department_id'] ?? null,
                    'schedule_id' => $inputData['schedule_id'] ?? null,
                    'location_id' => $inputData['location_id'] ?? null,
                ];

                EmployeeHistory::create($historyData);
            }
        }

        return $employees;
    }

    return response()->json(['error' => 'Invalid JSON array'], 400);
}


    public function deleteEmployee($id)
    {
   
        $data2 = User::where('id', $id[0]['id'])->delete();

        return $data2;
    }

    public function getAllAttendances()
    {
        $attendances=[];
        $attendances[] = User::all();

        return response()->json([
            'status' => 'success',
            'data' => $attendances
        ], 200);
    }

    // public function getAllAttendancesByDepartment($data)
    // {
    //     $companyId = Auth::user()->company->id;
    //     $roleId = Auth::user()->role->role_id;
    //     $departmentId = $data['department_id']?? null;
    //     $locationId = $data['location_id']?? null;
    //     $minDate = $data['min']??null;
    //     $maxDate = $data['max']?? null;
    
    //     $baseQuery = Attendance::with('location', 'user', 'department', 'schedule')
    //     ->when($minDate && $maxDate, function ($query) use ($minDate, $maxDate) {
    //         $query->where('created_at', '>=', $minDate)
    //               ->where('created_at', '<=', $maxDate . ' 23:59:59');
    //     })
    //     ->whereHas('user', function ($query) use ($companyId, $roleId, $departmentId, $locationId) {
    //         if ($roleId != 3) {
    //             $query->where('company_id', $companyId);
    //         }

    //         if ($departmentId) {
    //             $query->where('department_id', $departmentId);
    //         }

    //         if ($locationId) {
    //             $query->where('location_id', $locationId);
    //         }
    //     })
    //     ->orderBy('created_at', 'DESC');
    
    //     // Execute the base query to get all results
    //     $results = $baseQuery->get();

    //     return $results;
    // }

    // public function getAllUserByDepartmentIdAndLocId($departId, $locId)
    // {
    //     $cond[0] = ['company_id', Auth::user()->company_id];

    //     if ($departId) {
    //         $cond[1] = ['department_id', $departId];
    //     }
    //     if ($locId) {
    //         $cond[2] = ['location_id', $locId];
    //     }

    //     $data = User::where($cond)->get();

    //     return $data;
    // }

    // public function getTimeOffByUserId()
    // {
    //     // Get all time off records for the authenticated user for the current month
    //     $data = Timeoff::where('user_id', Auth::user()->id)->whereMonth('created_at', date('m'))->first();

    //     return $data;
    // }
}
