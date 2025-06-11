<?php

namespace App\Services;

use App\Models\Department;

class ApiDepartmentServices
{
    public function getAllDepartment()
    {
        $department = Department::all();
        $departments = [];
        $departments[] = $department;

        return $departments;
    }

    public function addDepartment(array $data)
    {
        $departments = [];
        foreach ($data as $departmentData) {

            $department = new Department();
            $department->fill($departmentData);
            $department->save();
        }
        $departments[] = $department;

        return $departments;
    }

    public function editDepartment(array $data)
    {
        $departments = [];

        if (is_array($data)) {
            foreach ($data as $departmentData) {
                if (isset($departmentData['id'])) {
              
                    $department = Department::find($departmentData['id']);
    
                    if ($department) {
                        $department->update([
                            'company_id' => $departmentData['company_id'] ?? $department->company_id,
                            'name' => $departmentData['name'] ?? $department->name,
                            'created_at' => $departmentData['created_at'] ?? $department->created_at,
                            'updated_at' => $departmentData['updated_at'] ?? $department->updated_at,
                        ]);
                    }
                    $departments[] = $department;
                }
            }
    
            return $departments;
        }
    
        return response()->json(['error' => 'Invalid JSON array'], 400);
    }

    public function deleteDepartment($id)
    {
   
        $data2 = Department::where('id', $id[0]['id'])->delete();

        return $data2;
    }
}
