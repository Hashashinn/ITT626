<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiDepartmentServices;
use Illuminate\Support\Facades\Log;


class ApiDepartmentController extends Controller
{
    protected $as;

    public function __construct(ApiDepartmentServices  $as)
    {
        $this->as = $as;
    }

    public function getAllDepartment()
    {
        try {

            $response = $this->as->getAllDepartment();
 
            return response()->json([
                'status' => 'success',
                'data' => $response
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions
            $response2=[
                'status' => 'error',
                'message' => 'An error occurred while fetching department',
                'error' => $e->getMessage(),
            ];
            Log::error('An error occurred while fetching department',['msg'=>$response2]);
            return response()->json($response2, 500);
        }
    }

    public function addDepartment(Request $request)
    {
        $data = $request->all();
        
        try {
            $department = $this->as->addDepartment($data);
    
            return response()->json([
                'success' => true,
                'message' => 'Department added successfully!',
                'data' => $department,
            ], 201);
        } catch (\Exception $e) {
            // Handle any exceptions
            $response2=[
                'status' => 'error',
                'message' => 'An error occurred while added department',
                'error' => $e->getMessage(),
            ];
            Log::error('An error occurred while added department',['msg'=>$response2]);
            return response()->json($response2, 500);
        }
    }

    
    public function editDepartment(Request $request)
    {
        $data = $request->json()->all();

        try {
            $department = $this->as->editDepartment($data);
    
            return response()->json([
                'success' => true,
                'message' => 'Department edited successfully!',
                'data' => $department,
            ], 201);
        } catch (\Exception $e) {
            // Handle any exceptions
            $response2=[
                'status' => 'error',
                'message' => 'An error occurred while edited department',
                'error' => $e->getMessage(),
            ];
            Log::error('An error occurred while edited department',['msg'=>$response2]);
            return response()->json($response2, 500);
        }
    }

    public function deleteDepartment(Request $id)
    {
        $data = $id->json()->all();
   
        try {
            $department = $this->as->deleteDepartment($data);
    
            return response()->json([
                'success' => true,
                'message' => 'Department deleted successfully!',
                'data' => $department,
            ], 201);
        } catch (\Exception $e) {
            // Handle any exceptions
            $response2=[
                'status' => 'error',
                'message' => 'An error occurred while deleted department',
                'error' => $e->getMessage(),
            ];
            Log::error('An error occurred while deleted department',['msg'=>$response2]);
            return response()->json($response2, 500);
        }
    }


}
