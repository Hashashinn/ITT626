<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\Department;
use App\Models\Location;
use App\Http\Requests\EmployeeRec;
use App\Models\EmployeeHistory;
use App\Services\EmployeeServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeController extends Controller
{

    private $es;

    public function __construct(EmployeeServices $es)
    {
        $this->es = $es;
    }

    public function index(Request $r)
    {
        $ls = new EmployeeServices;

        $department_id = '';
        $branch_id = '';
        if (Auth::user()->role->role_id == 4) {
            $department_id = Auth::user()->department_id;
            // $branch_id = Auth::user()->location_id;
        }

        $result['employees'] = $ls->getAllEmployees($branch_id, $department_id, $r);
        $result['totalEmployee'] = $result['employees']->count();
        $result['department_id'] = '';
        $result['location_id'] = '';
        
        return view('admin.employee', $result);
        
    }

    public function store(EmployeeRec $request)
    {
        $request->validated();

        $employee = new Employee;
        $employee->name = $request->name;
        $employee->position = $request->position;
        $employee->email = $request->email;
        $employee->pin_code = bcrypt($request->pin_code);
        $employee->save();

        if ($request->schedule) {

            $schedule = Schedule::whereSlug($request->schedule)->first();

            $employee->schedules()->attach($schedule);
        }

        // $role = Role::whereSlug('emp')->first();

        // $employee->roles()->attach($role);

        flash()->success('Success', 'Employee Record has been created successfully !');

        return redirect()->route('employees.index')->with('success');
    }


    public function update(EmployeeRec $request, Employee $employee)
    {
        $request->validated();


        return redirect()->route('employees.index')->with('success');
    }


    public function destroy(Employee $employee)
    {
        $employee->delete();
        flash()->success('Success', 'Employee Record has been Deleted successfully !');
        return redirect()->route('employees.index')->with('success');
    }

    public function employeeStore(Request $r)
    {
        $ls = new EmployeeServices;

        $result = $ls->employeeStore($r);

        return response()->json($result);
    }

    public function employeeUpdate(Request $r, $id)
    {
        $ls = new EmployeeServices;

        $result = $ls->employeeUpdate($r, $id);

        return response()->json($result);
    }

    public function employeeDelete(Request $r, $id)
    {
        $ls = new EmployeeServices;

        $result = $ls->employeeDelete($r, $id);

        return response()->json($result);
    }


    public function getemployeeById($id = '')
    {
        $ls = new EmployeeServices;

        $result = $ls->getemployeeById($id);

        return response()->json($result);
    }

    public function employeHistoryView(Request $r)
    {
        $data['historys'] = $this->es->employeeHistoryData($r);

        $data['user_id'] = '';
        $data['department_id'] ='';

        return view('admin.employeeHistory', $data);
    }

    public function historySearch(Request $r)
    {
        $user_id = $r->input('user_id');
        $department_id = $r->input('department_id');
        $query = EmployeeHistory::with(
            'department',
            'location',
            'schedule'
        );
    
        if ($user_id !== null) {
            $query->where('user_id', $user_id);
        }
    
        if ($department_id !== null) {
            $query->where('department_id', $department_id);
        }
    
        $data['historys'] = $query->get();
    
        $data['user_id'] = $user_id;
        $data['department_id'] = $department_id;
    
        return view('admin.employeeHistory', $data);
    }
    


    public function searchEmployee(Request $r)
    {
        $ls = new EmployeeServices;

        $department_id = '';
        $branch_id = '';
    
        if (Auth::user()->role->role_id == 4) {
            $department_id = Auth::user()->department_id;
            // $branch_id = Auth::user()->location_id;
        }

        $result['department_id'] = $r->input('department_id');
        $result['location_id'] = $r->input('location_id');

        $result['employees'] = $ls->getAllEmployees($branch_id, $department_id, $r);
    

        return view('admin.employee', $result);
    }
}