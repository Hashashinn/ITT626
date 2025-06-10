<?php

namespace App\Imports;

use App\Models\Department;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DepartmentImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $import =  new Department([
            'id' => $row['id'],
            'name' => $row['name'],
        ]);

        Department::where([['id', $row['id'], ['name', $row['name']]]])->delete();

        return $import;
    }
}
