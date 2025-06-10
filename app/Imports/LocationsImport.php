<?php

namespace App\Imports;

use App\Models\Location;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LocationsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $import =  new Location([
            'id' => $row['id'],
            'user_id' => $row['user_id'],
            'location_name' => $row['location_name'],
            'lat' => $row['lat'],
            'lng' => $row['lng'],
        ]);

        Location::where([['id', $row['id'], ['user_id', $row['user_id']]]])->delete();

        return $import;
    }
}
