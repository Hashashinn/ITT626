<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    //
    public function updateUserLocation(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        // Update the user's location in the database
        // Find the authenticated user or adjust the logic to retrieve the user based on your authentication setup
        $user = auth()->user();

        // Update the user's location
        $user->latitude = $latitude;
        $user->longitude = $longitude;


        return response()->json(['message' => 'User location updated successfully']);
    }
}
