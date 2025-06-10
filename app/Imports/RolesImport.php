<?php

namespace App\Imports;

use App\Models\Role;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RolesImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $import =  new Role([
            'id' => $row['id'],
            'slug' => $row['slug'],
            'name' => $row['name'],
        ]);

        Role::where([['id', $row['id'], ['slug', $row['slug']]]])->delete();

        return $import;
    }
}
