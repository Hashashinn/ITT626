<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiLocationServices;
use Illuminate\Support\Facades\Log;

class ApiLocationController extends Controller
{
    protected $as;

    public function __construct(ApiLocationServices  $as)
    {
        $this->as = $as;
    }
    
    public function getAllLocations()
    {
        try {

            $response = $this->as->getAllLocation();
 
            return response()->json([
                'status' => 'success',
                'data' => $response
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions
            $response2=[
                'status' => 'error',
                'message' => 'An error occurred while fetching locations',
                'error' => $e->getMessage(),
            ];
            Log::error('An error occurred while fetching locations',['msg'=>$response2]);
            return response()->json($response2, 500);
        }
    }
    
    public function addLocation(Request $request)
    {
        $data = $request->json()->all();        
        try {
            $locations = $this->as->addLocation($data);
    
            return response()->json([
                'success' => true,
                'message' => 'Locations added successfully!',
                'data' => $locations,
            ], 201);
        } catch (\Exception $e) {
            // Handle any exceptions
            $response2=[
                'status' => 'error',
                'message' => 'An error occurred while add locations',
                'error' => $e->getMessage(),
            ];
            Log::error('An error occurred while add locations',['msg'=>$response2]);
            return response()->json($response2, 500);
        }
    }

    
    public function editLocation(Request $request)
    {
        $data = $request->json()->all();
    
        try {

            $response = $this->as->editLocation($data);
 
            return response()->json([
                'success' => true,
                'message' => 'Locations edited successfully!',
                'data' => $response,
            ], 201);
        } catch (\Exception $e) {
            // Handle any exceptions
            $response2=[
                'status' => 'error',
                'message' => 'An error occurred while edited locations',
                'error' => $e->getMessage(),
            ];
            Log::error('An error occurred while edited locations',['msg'=>$response2]);
            return response()->json($response2, 500);
            
        }
    }

    
    public function deleteLocation(Request $id)
    {
        $data = $id->json()->all();
   
        try {

            $response = $this->as->deleteLocation($data);
 
            return response()->json([
                'success' => true,
                'message' => 'Locations deleted successfully!',
                'data' => $response,
            ], 201);
        } catch (\Exception $e) {
            // Handle any exceptions
            $response2=[
                'status' => 'error',
                'message' => 'An error occurred while deleted locations',
                'error' => $e->getMessage(),
            ];
            Log::error('An error occurred while deleted locations',['msg'=>$response2]);
            return response()->json($response2, 500);
            
           
        }
    }


}