<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ApiEmployeeServices;
use App\Services\AttendanceServices;
use App\Services\RoleServices;
use Illuminate\Support\Facades\Log;
class ApiEmployeeController extends Controller
{
    protected $as;
    protected $a;
    protected $b;

    public function __construct(ApiEmployeeServices  $as, AttendanceServices $a, RoleServices $b)
    {
        $this->as = $as;
        $this->a = $a;
        $this->b = $b;
    }

    public function getAllEmployees()
    {
        try {

            $response = $this->as->getAllEmployee();
 
            return response()->json([
                'status' => 'success',
                'data' => $response
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions
            $response2=[
                'status' => 'error',
                'message' => 'An error occurred while fetching employee',
                'error' => $e->getMessage(),
            ];
            Log::error('An error occurred while fetching employee',['msg'=>$response2]);
            return response()->json($response2, 500);
        }
    }

    public function addEmployee(Request $request)
    {
        
        // $data = $request->all();
        $data = $request->json()->all();
        
        try {
            $employee = $this->as->addEmployee($data);
    
            return response()->json([
                'success' => true,
                'message' => 'Employee added successfully!',
                'data' => $employee,
            ], 201);
        } catch (\Exception $e) {
            // Handle any exceptions
            $response2=[
                'status' => 'error',
                'message' => 'An error occurred saving the employee',
                'error' => $e->getMessage(),
            ];
            Log::error('An error occurred while saving the employee',['msg'=>$response2]);
            return response()->json($response2, 500);
        }

    }
    

    public function uploadExcel(Request $request)
    {
        
        try {
            $employee = $this->b->uploadExcel($request);
    
            return response()->json([
                'success' => true,
                'message' => 'Employee imported successfully!',
                'data' => $employee,
            ], 201);
        } catch (\Exception $e) {
            // Handle any exceptions
            $response2=[
                'status' => 'error',
                'message' => 'An error occurred while imported employee',
                'error' => $e->getMessage(),
            ];
            Log::error('An error occurred while imported employee',['msg'=>$response2]);
            return response()->json($response2, 500);
        }
    }

    public function editEmployee(Request $request)
    {   
        $data = $request->json()->all();
        
        try {
            $employee = $this->as->editEmployee($data);
    
            return response()->json([
                'success' => true,
                'message' => 'Employee edited successfully!',
                'data' => $employee,
            ], 201);
        } catch (\Exception $e) {
            // Handle any exceptions
            $response2=[
                'status' => 'error',
                'message' => 'An error occurred while edited employee',
                'error' => $e->getMessage(),
            ];
            Log::error('An error occurred while edited employee',['msg'=>$response2]);
            return response()->json($response2, 500);
        }
    }

    public function deleteEmployee(Request $id)
    {

        $data = $id->json()->all();
        

        try {

            $response = $this->as->deleteEmployee($data);
            $response3 = $this->as->getAllEmployee();
 
            return response()->json([
                'success' => true,
                'message' => 'Employee deleted successfully!',
                'data' => $response,
                'data_db' => $response3
            ], 201);
        } catch (\Exception $e) {
            // Handle any exceptions
            $response2=[
                'status' => 'error',
                'message' => 'An error occurred while deleted employee',
                'error' => $e->getMessage(),
                'data_db' => $response3
            ];
            Log::error('An error occurred while deleted employee',['msg'=>$response2]);
            return response()->json($response2, 500);
        }
    }

    public function attendances(Request $data)
    {

        $input = $data->json()->all();

        try {

            if (isset(Auth::user()->role)) {
                if (in_array(Auth::user()->role->role_id, [2, 3])) {
                    $input['attendances'] = $this->a->getAllAttendances($data);
                } elseif (Auth::user()->role->role_id == 4) {
                    $departmentId = Auth::user()->department_id;
                    $input['attendances'] = $this->a->getAllAttendancesByDepartment($departmentId);
                } else {
                    $input['attendances'] = $this->a->getAllAttendancesByUserId(Auth::user()->id,$data);
                }
            }
 
            return response()->json([
                'success' => true,
                'message' => 'All attendance view successfully!',
                'data' => $input,
            ], 201);
        } catch (\Exception $e) {
            // Handle any exceptions
            $response2=[
                'status' => 'error',
                'message' => 'An error occurred while fetching attendances',
                'error' => $e->getMessage(),
            ];
            Log::error('An error occurred while fetching attendances',['msg'=>$response2]);
            return response()->json($response2, 500);
        }
    }

    public function timeoffListView(Request $r)
    {
        try {
            $data['timeoffs'] = $this->a->getTimeOffByUserId(Auth::user()->id);
            if (isset(Auth::user()->role)) {
                if (in_array(Auth::user()->role->role_id, [2, 3])) {
                    $data['timeoffs'] = $this->a->getAllTimeoff($r);
                } elseif (Auth::user()->role->role_id == 4) {
                    $departmentId = Auth::user()->department_id;
                    $data['timeoffs'] = $this->a->getAllTimeoffByDepartment($departmentId);
                } else {
                    $data['timeoffs'] = $this->a->getAllTimeoffByUserId(Auth::user()->id);
                }
            }

            $data['department_id'] = '';
            $data['location_id'] = '';

            // return  $data;

            return response()->json([
                'success' => true,
                'message' => 'All attendance view successfully!',
                'data' => $data,
            ], 201);
        } catch (\Exception $e) {
            // Handle any exceptions
            $response2=[
                'status' => 'error',
                'message' => 'An error occurred while fetching attendances',
                'error' => $e->getMessage(),
            ];
            Log::error('An error occurred while fetching attendances',['msg'=>$response2]);
            return response()->json($response2, 500);
        }
    }
    
}
