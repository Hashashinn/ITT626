<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Latetime;
use App\Models\Attendance;
use App\Models\Location;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Services\AdminServices;
use App\Services\LocationServices;
use App\Services\AttendanceServices;
use DateTime;
use App\Services\EmployeeServices;
use Illuminate\Support\Facades\Auth;
use App\Services\DepartmentServices;
use App\Services\RoleServices;

class AdminController extends Controller
{
    public function index(Request $r)
    {
        $user = auth()->user();
        $role = $user->role;

        $as = new AttendanceServices;
        $es = new EmployeeServices;
        $ads = new AdminServices;

        $data['lateEmployee'] = $as->getAllAttendancesByUserIdByMonth(Auth::user()->id, 'late')->count();
        $data['ontimeEmployee'] = $as->getAllAttendancesByUserIdByMonth(Auth::user()->id, 'ontime')->count();
        $data['percentEmployee'] = ($data['ontimeEmployee'] / 31) * 100;
        $data['logActions'] = [];
        $branch_id = '';
        $department_id = '';

        if (isset($role)) {
            if (in_array($role->role_id, [3, 2])) {
                // Superadmin and Admin
                $data['lateAdmin'] = $as->getAllAttendancesByMonth('late')->count();
                $data['ontimeAdmin'] = $as->getAllAttendancesByMonth('ontime')->count();
                // $data['allEmployee'] = $es->getAllEmployees()->count();
                $data['allEmployee'] = $es->getAllEmployees($branch_id, $department_id, $r)->count();
                $data['logActions'] = $ads->getLogActionUser();
            } elseif ($role->role_id == 4) {
                // Manager
                $departmentId = $user->department_id;
                $data['lateAdmin'] = $as->getAllAttendancesByMonthAndDepartment('late', $departmentId)->count();
                $data['ontimeAdmin'] = $as->getAllAttendancesByMonthAndDepartment('ontime', $departmentId)->count();
                $data['allEmployee'] = $es->getAllEmployeesByDepartment($departmentId)->count();
            }
        }

        return view('admin.index', $data);
    }

    public function roleView()
    {
        $dp = new AdminServices;

        $data['roles'] = $dp->getAllRoles();

        return view('admin.role', $data);
    }

    public function store(Request $r)
    {
        $dp = new AdminServices;

        $result = $dp->store($r);

        return response()->json($result);
    }

    public function update(Request $r, $id)
    {
        $dp = new AdminServices;

        $result = $dp->update($r, $id);

        return response()->json($result);
    }

    public function delete(Request $r, $id)
    {
        $dp = new AdminServices;

        $result = $dp->delete($r, $id);

        return response()->json($result);
    }

    public function getRoleById($id = '')
    {
        $dp = new AdminServices;

        $result = $dp->getRoleById($id);

        return response()->json($result);
    }

    public function registerView()
    {
        // $dp = new AdminServices;

        $data = [];

        return view('auth.register', $data);
    }

    public function registerStore(Request $r)
    {
        $dp = new AdminServices;

        $result = $dp->registerStore($r);

        return response()->json($result);
    }

    public function locationView()
    {
        $ls = new LocationServices;

        $data['locations'] = $ls->getAllLocations();

        return view('admin.location', $data);
    }

    public function locationStore(Request $r)
    {
        $ls = new LocationServices;

        $result = $ls->locationStore($r);

        return response()->json($result);
    }

    public function locationUpdate(Request $r, $id)
    {
        $ls = new LocationServices;

        $result = $ls->locationUpdate($r, $id);

        return response()->json($result);
    }

    public function locationDelete(Request $r, $id)
    {
        $ls = new LocationServices;

        $result = $ls->locationDelete($r, $id);

        return response()->json($result);
    }


    public function getLocationById($id = '')
    {
        $ls = new LocationServices;

        $result = $ls->getLocationById($id);

        return response()->json($result);
    }

    public function getByDepartment($departmentId)
    {
        // Retrieve the employees belonging to the specified department using the $departmentId
        $employees = Employee::where('department_id', $departmentId)->get();

        // Return the employees data or pass it to a view to display the results
        return view('employees-by-department', ['employees' => $employees]);
    }

    public function getByLocation($locationId)
    {
        // Retrieve the employees located at the specified location using the $locationId
        $employees = Employee::where('location_id', $locationId)->get();

        // Return the employees data or pass it to a view to display the results
        return view('employees-by-location', ['employees' => $employees]);
    }

    public function uploadExcel(Request $r, $type = '')
    {
        $rs = new RoleServices;

        $result = $rs->uploadExcel($r, $type);

        return response()->json($result);
    }

    public function departmentStore(Request $r)
    {
        $ds = new DepartmentServices;

        $result = $ds->departmentStore($r);

        return response()->json($result);
    }

    public function departmentUpdate(Request $r, $id)
    {
        $ds = new DepartmentServices;

        $result = $ds->departmentUpdate($r, $id);

        return response()->json($result);
    }

    public function departmentDelete(Request $r, $id)
    {
        $ds = new DepartmentServices;

        $result = $ds->departmentDelete($r, $id);

        return response()->json($result);
    }

    public function departmentView()
    {
        $ds = new DepartmentServices;

        $data['departments'] = $ds->getAlldepartment();

        return view('admin.department', $data);
    }

    public function changePasswordView()
    {
        $ds = new DepartmentServices;

        return view('admin.changePasswordView');
    }

    public function changePassword(Request $r)
    {
        $ds = new AdminServices;

        $result = $ds->changePassword($r);

        return response()->json($result);
    }
}