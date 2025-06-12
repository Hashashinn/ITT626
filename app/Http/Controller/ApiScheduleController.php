<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiScheduleServices;
use Illuminate\Support\Facades\Log;

class ApiScheduleController extends Controller
{
    protected $as;

    public function __construct(ApiScheduleServices  $as)
    {
        $this->as = $as;
    }
    //
     public function getAllSchedule()
     {
        try {

            $response = $this->as->getAllSchedule();
 
            return response()->json([
                'status' => 'success',
                'data' => $response
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions
            $response2=[
                'status' => 'error',
                'message' => 'An error occurred while fetching schedules',
                'error' => $e->getMessage(),
            ];
            Log::error('An error occurred while fetching schedules',['msg'=>$response2]);
            return response()->json($response2, 500);
        }
     }
 
     public function addSchedule(Request $request)
     {
        $data = $request->json()->all();

        try {
            $response = $this->as->addSchedule($data);
    
            return response()->json([
                'success' => true,
                'message' => 'Schedule added successfully!',
                'data' => $response,
            ], 201);
        } catch (\Exception $e) {
            // Handle any exceptions
            $response2=[
                'status' => 'error',
                'message' => 'An error occurred while add schedules',
                'error' => $e->getMessage(),
            ];
            Log::error('An error occurred while add schedules',['msg'=>$response2]);
            return response()->json($response2, 500);
        }
     }
 
     public function editSchedule(Request $request)
     {
         $data = $request->json()->all();

         try {
            $response = $this->as->editSchedule($data);
    
            return response()->json([
                'success' => true,
                'message' => 'Schedule edited successfully!',
                'data' => $response,
            ], 201);
        } catch (\Exception $e) {
            // Handle any exceptions
            $response2=[
                'status' => 'error',
                'message' => 'An error occurred while edited schedules',
                'error' => $e->getMessage(),
            ];
            Log::error('An error occurred while edited schedules',['msg'=>$response2]);
            return response()->json($response2, 500);
        }

     }

     public function deleteSchedule(Request $id)
     {
         $data = $id->json()->all();
    
         try {
            $response = $this->as->deleteSchedule($data);
    
            return response()->json([
                'success' => true,
                'message' => 'Schedule deleted successfully!',
                'data' => $response,
            ], 201);
        } catch (\Exception $e) {
            // Handle any exceptions
            $response2=[
                'status' => 'error',
                'message' => 'An error occurred while deleted schedules',
                'error' => $e->getMessage(),
            ];
            Log::error('An error occurred while deleted schedules',['msg'=>$response2]);
            return response()->json($response2, 500);
        }
     }
 
}
