<?php

namespace App\Services;

use App\Models\Location;
use Illuminate\Support\Facades\Auth;


class LocationServices
{
    public function getAllLocations()
    {
        $cond[0] = ['id', '!=', ''];

        if (Auth::user()->role->role_id != 3) {
            $cond[1] = ['company_id', Auth::user()->company_id];
        }

        $data = Location::where($cond)->get();

        return $data;
    }

    public function locationStore($r)
    {
        $input = $r->input();
        unset($input['_token']);
        $locationData = Location::where('location_name', $input['location_name'])->where('company_id',Auth::user()->company_id)->first();

        if ($locationData) {
            $data['status'] = config('app.response.error.status');
            $data['type'] = config('app.response.error.type');
            $data['title'] = config('app.response.error.title');
            $data['msg'] = 'The location already exists.';

            return $data;
        }

        $input['company_id'] = Auth::user()->company_id;

        Location::create($input);

        // insert user log
        $logData = [
            'user_id' => Auth::user()->id,
            'desc' => 'Create new Location: ' . $input['location_name'],
        ];

        createActionLogUser($logData);

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Success Create.';

        return $data;
    }

    public function getLocationById($id = '')
    {
        $data = Location::find($id);

        return $data;
    }
    public function locationUpdate($r, $id)
    {
        $input = $r->input();
        unset($input['_token']);

        Location::where('id', $id)->update($input);

        // insert user log
        $logData = [
            'user_id' => Auth::user()->id,
            'desc' => 'Update location : ' . $id,
        ];

        createActionLogUser($logData);

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Success Update.';


        return $data;
    }

    public function locationDelete($r, $id)
    {
        // $input = $r->input();
        // unset($input['_token']);
        Location::where('id', $id)->delete();

        // insert user log
        $logData = [
            'user_id' => Auth::user()->id,
            'desc' => 'Delete location :' . $id,
        ];

        createActionLogUser($logData);

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Success Delete.';


        return $data;
    }
}