<?php

namespace App\Services;


use App\Models\Location;

class ApiLocationServices
{
    public function getAllLocation()
    {   
        $locations = [];
        $location = Location::all();
        $locations[] = $location;

        return $locations;
    }

    public function addLocation(array $data)
    {
        $locations = [];
        
        foreach ($data as $locationData) {
            $location = new Location();
            $location->fill($locationData);
            $location->save();
            $locations[] = $location;
        }

        return $locations;
    }

    public function editLocation(array $data)
    {
        $locations = [];
        
    if (is_array($data)) {
        foreach ($data as $LocationData) {
            if (isset($LocationData['id'])) {
                $location = Location::find($LocationData['id']);
                
                if ($location) {
                    $location->update([
                        'company_id' => $LocationData['company_id'] ?? $location->company_id,
                        'user_id' => $LocationData['user_id'] ?? $location->user_id,
                        'location_name' => $LocationData['location_name'] ?? $location->location_name,
                        'lat' => $LocationData['lat'] ?? $location->lat,
                        'lng' => $LocationData['lng'] ?? $location->lng,
                        'status' => $LocationData['status'] ?? $location->status,
                        'created_at' => $LocationData['created_at'] ?? $location->created_at,
                        'updated_at' => $LocationData['updated_at'] ?? $location->updated_at,
                    ]);
                    $locations[] = $location;
                }
            }
        }
        return $locations;
    }
    return response()->json(['error' => 'Invalid JSON array'], 400);
    }

    public function deleteLocation($data)
    {   
        $data2 = Location::where('id', $data[0]['id'])->delete();

        return $data2;
    }
}
