<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Employee;
use App\Models\User;
use App\Models\Latetime;
use App\Models\Attendance;
use App\Models\Location;
use App\Models\Department;
use App\Services\ApiServices;
use App\Services\AttendanceServices;
use App\Services\EmailServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Response;

class AttendanceController extends Controller
{
    //show attendance
    public function index(Request $r)
    {
        $as = new AttendanceServices;
        $ac = new ApiServices(new EmailServices, new AttendanceServices);
        // $attendances = [];
        $attendances = collect();

        if (isset(Auth::user()->role)) {
            if (in_array(Auth::user()->role->role_id, [2, 3])) {
                $attendances = $as->getAllAttendances($r);
            } elseif (Auth::user()->role->role_id == 4) {
                // $departmentId = Auth::user()->department_id;
                $attendances = $as->getAllAttendancesByDepartment($r);
            }

            if (Auth::user()->role->role_id == 1) {
                $departmentId = Auth::user()->department_id;
                $locationId = Auth::user()->location_id;
                $result['departmentName'] = getDepartmentById($departmentId)->name;
                $result['locationName'] = getLocationById($locationId)->location_name;
                $result['totalEmployee'] = 1;
            }
        }

        $ontimeCount = 0;
        $lateCount = 0;

        foreach ($attendances as $attendance) {
            $timein = $attendance->user->schedule->time_in ?? '09:00';
            if ($attendance->attendance_timein <= ($timein . ':59' ?? '09:00:00')) {
                $ontimeCount++;
            } else {
                $lateCount++;
            }
        }

        $totalEmployee = $ac->getAllUserByDepartmentIdAndLocId($r->department_id, $r->location_id)->count();

        $result = [
            'data' => $attendances,
            'departmentName' => 'All',
            'locationName' => 'All',
            'totalEmployee' => $totalEmployee,
            'totalLate' => $lateCount,
            'totalOntime' => $ontimeCount,
            'location_id' => '',
            'department_id' => '',
        ];

        if ($r->department_id) {
            $result['departmentName'] = getDepartmentById($r->department_id)->name;
        }


        if ($r->location_id) {
            $result['locationName'] = getLocationById($r->location_id)->location_name;
        }


        return view('admin.attendance', [
            'attendances' => $attendances, // Make sure $attendances is an instance of LengthAwarePaginator
            'department_id' => $result['department_id'],
            'location_id' => $result['location_id'],
        ]);
        // return view('admin.attendance', $result);
    }

    //show late times
    public function indexLatetime()
    {
        return view('admin.latetime')->with(['latetimes' => Latetime::all()]);
    }

    public static function lateTimeDevice($att_dateTime, Employee $employee)
    {
        $attendance_time = new DateTime($att_dateTime);
        $checkin = new DateTime($employee->schedules->first()->time_in);
        $difference = $checkin->diff($attendance_time)->format('%H:%I:%S');

        $latetime = new Latetime();
        $latetime->emp_id = $employee->id;
        $latetime->duration = $difference;
        $latetime->latetime_date = date('Y-m-d', strtotime($att_dateTime));
        $latetime->save();
    }

    public function captureView($user = '')
    {
        // $attendance = new Attendance();
        $as = new AttendanceServices;
        $data['attendance'] = $as->getAttendanceByIdByDate(Auth::user()->id, date('Y-m-d'));
        // dd($data);
        // $data['attendance'] = $attendance;
        return view('admin.capture_attendance', $data);
    }

    public function create()
    {
        return view('attendances.create');
    }

    public function store(Request $r)
    {
        $as = new AttendanceServices;

        $result = $as->store($r);

        return response()->json($result);
    }

    public function edit(Attendance $attendance)
    {
        return view('attendances.edit', compact('attendance'));
    }

    public function update(Request $r, $id)
    {
        $as = new AttendanceServices;

        $result = $as->update($r, $id);

        return response()->json($result);
    }

    public function delete(Request $r, $id)
    {
        $as = new AttendanceServices;

        $result = $as->delete($r, $id);

        return response()->json($result);
    }

    public function destroy(attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('attendances.index')->with('success', 'Attendance deleted successfully.');
    }

    public function getAttendanceById($id = '')
    {
        $as = new AttendanceServices;

        $result = $as->getAttendanceById($id);

        return response()->json($result);
    }

    public function attendanceList($id = '')
    {
        $as = new AttendanceServices;
        $data['attendances'] = $as->getAllAttendancesByUserId($id, null);

        return view('admin.attendance', $data);
    }

    public function getTodayAttendanceById()
    {
        $as = new AttendanceServices;
        $result = $as->getTodayAttendanceById();

        return response()->json($result);
    }

    public function punchOut(Request $r, $id = '')
    {
        $as = new AttendanceServices;

        $result = $as->punchOut($r, $id);

        return response()->json($result);
    }

    public function captureOutsideView($user = '')
    {
        $as = new AttendanceServices;

        $data['attendance'] = [];

        return view('admin.capture_outside', $data);
    }

    public function storeActivityUser(Request $r)
    {
        $as = new AttendanceServices;

        $result = $as->storeActivityUser($r);

        return response()->json($result);
    }

    public function getActivityOutsideByUserId($id)
    {
        $as = new AttendanceServices;

        $result = $as->getActivityOutsideByUserId($id);

        return response()->json($result);
    }

    public function getActivityOutsideByAttId($id)
    {
        $as = new AttendanceServices;

        $result = $as->getActivityOutsideByAttId($id);

        return response()->json($result);
    }

    public function timeOffView()
    {
        $as = new AttendanceServices;

        $data['timeoff'] = $as->getTimeOffByUserId();


        return view('admin.timeoffView', $data);
    }

    public function storeTimeoff(Request $r)
    {
        $as = new AttendanceServices;

        $result = $as->storeTimeoff($r);

        return response()->json($result);
    }

    public function updateTimeoff(Request $r, $id = '')
    {
        $as = new AttendanceServices;

        $result = $as->updateTimeoff($r, $id);

        return response()->json($result);
    }

    public function timeoffListView(Request $r)
    {
        $as = new AttendanceServices;

        $data['timeoffs'] = $as->getTimeOffByUserId(Auth::user()->id);
        if (isset(Auth::user()->role)) {
            if (in_array(Auth::user()->role->role_id, [2, 3])) {
                $data['timeoffs'] = $as->getAllTimeoff($r);
            } elseif (Auth::user()->role->role_id == 4) {
                $departmentId = Auth::user()->department_id;
                $data['timeoffs'] = $as->getAllTimeoffByDepartment($departmentId);
            } else {
                $data['timeoffs'] = $as->getAllTimeoffByUserId(Auth::user()->id);
            }
        }

        $data['department_id'] = '';
        $data['location_id'] = '';

        return view('admin.timeoffListView', $data);
    }

    public function searchTimeoff(Request $r)
    {
        $as = new AttendanceServices;

        $data['timeoffs'] = $as->getTimeOffByUserId(Auth::user()->id);
        if (isset(Auth::user()->role)) {
            if (in_array(Auth::user()->role->role_id, [2, 3])) {
                $data['timeoffs'] = $as->getAllTimeoff($r);
            } elseif (Auth::user()->role->role_id == 4) {
                $departmentId = Auth::user()->department_id;
                $data['timeoffs'] = $as->getAllTimeoffByDepartment($departmentId);
            } else {
                $data['timeoffs'] = $as->getAllTimeoffByUserId(Auth::user()->id);
            }
        }
        $data['department_id'] = $r->input('department_id');
        $data['location_id'] = $r->input('location_id');

        return view('admin.timeoffListView', $data);
    }


    public function searchAttendanceLog(Request $r)
    {
        $data = [];
        $role = Auth::user()->role->role_id;
        $departmentFilter = $r->input('department_id');
        $locationFilter = $r->input('location_id');
        $as = new AttendanceServices;

        // Retrieve  attendances for admins
        if ($role == '2' || $role == '3') {
            $as = new AttendanceServices;
            $data['attendances'] = $as->getAllAttendances($r);
        }
        // // Retrieve attendances by department
        elseif ($role == '4') {
            $as = new AttendanceServices;
            // $departmentId = Auth::user()->department_id;
            $data['attendances'] = $as->getAllAttendancesByDepartment( $r);
        } else {
            $userId = Auth::user()->id;
            $attendancesQuery = $as->getAllAttendancesByUserId($userId, $r);
            $data['attendances'] = $attendancesQuery->appends($r->except('page'));
        }

        $data['min'] = $r->input('min');
        $data['max'] = $r->input('max');
        $data['department_id'] = $r->input('department_id');
        $data['location_id'] = $r->input('location_id');

        $ontimeCount = 0;
        $lateCount = 0;
        foreach ($data['attendances'] as $attendance) {
            if ($attendance->attendance_timein <= ($attendance->user->schedule->time_in ?? '09:00:00')) {
                $ontimeCount++;
            } else {
                $lateCount++;
            }
        }

        $data['ontimeCount'] = $ontimeCount;
        $data['lateCount'] = $lateCount;

        return view('admin.attendance', $data);
    }

    public function attendanceStore(Request $r)
    {
        $as = new AttendanceServices;

        $result = $as->store($r);
        session()->flash('successMessage', 'Action was successful!');
        return redirect()->to('/captureView');
    }


    protected $attendanceService;

    public function __construct(AttendanceServices $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }
    
    public function downloadPDF(Request $request)
    {
        return $this->attendanceService->downloadPDF($request);
    }

    public function downloadCSV(Request $request)
    {
        return $this->attendanceService->downloadCSV($request);
    }


}
