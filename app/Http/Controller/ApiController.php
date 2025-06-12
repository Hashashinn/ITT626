<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ApiServices;
use App\Services\AttendanceServices;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class ApiController extends Controller
{
    use HttpResponses;

    protected $as;
    protected $asc;

    public function __construct(ApiServices $as, AttendanceServices $asc)
    {
        $this->as = $as;
        $this->asc = $asc;
    }

    public function login(Request $r)
    {
        if (!Auth::attempt($r->only('email', 'password'))) {
            return $this->error('', 'Credential do not match', 401);
        }

        $user = User::with([
            'location',
            'schedule',
            'attendance' => function ($query) {
                $query->whereDate('created_at', now()->toDateString());
                $query->orderBy('created_at', 'DESC');
            },
            'timeoff' => function ($query1) {
                $query1->whereDate('created_at', now()->toDateString());
                $query1->whereNull('timeoff_end');
            },
            'captureOutside' => function ($query2) {
                $query2->whereDate('created_at', now()->toDateString());
            },
             'roles'
        ])->where('email', $r->email)->first();

        $fcm_token = $r->fcm_token;

        if ($fcm_token) {
            $updateData = ['fcm_token' => $fcm_token];
            $user->update($updateData);
        }

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API for ' . $user->name)->plainTextToken
        ]);
    }

    public function register(Request $r)
    {

    try{
        $user = User::create([
            'name' => $r->name,
            'email' => $r->email,
            'department_id' => $r->department,
            'company_id' => $r->company,
            'position'=>$r->position,
            'password' => Hash::make($r->password),
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $user
        ], 200);

    } catch (\Exception $e) {
        // Handle any exceptions
        $response2=[
            'status' => 'error',
            'message' => 'An error occurred while register',
            'error' => $e->getMessage(),
        ];
        Log::error('An error occurred while register',['msg'=>$response2]);
        return response()->json($response2, 500);
    }
    }


    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return $this->success([
            'message' => 'Logout Success.'
        ]);
    }

    public function getDepartments()
    {
        return response()->json('this is my department');
    }

    public function uploadImage(Request $r)
    {
        $input = [];
        if ($r->hasfile('file')) {
            $file = $r->file('file');
            if ($file->getPathName()) {
                $nameFile = uploadFile($file);
                $input['attachment'] = $nameFile['filename'];
            }
        }

        return response()->json($input);
    }

    public function captureAttendance(Request $r)
    {
        $result = $this->as->captureAttendance($r);

        return $result;
    }

    public function punchOutAttendance(Request $r)
    {
        $result = $this->as->punchOutAttendance($r);

        return $result;
    }

    public function captureTimeOff(Request $r)
    {
        $result = $this->as->captureTimeOff($r);

        return $result;
    }

    public function punchOutTimeOff(Request $r)
    {
        $result = $this->as->punchOutTimeOff($r);

        return $result;
    }

    public function captureOutsideActivity(Request $r)
    {
        $result = $this->as->captureOutsideActivity($r);

        return $result;
    }


    public function captureAttendanceOffline(Request $r)
    {
        $result = $this->as->captureAttendanceOffline($r);

        return $result;
    }

    public function attendanceData(Request $r)
    {
        $result['data'] = $this->asc->getAllAttendancesByUserId(Auth::user()->id, null);
        $result['departmentName'] = 'All';
        $result['locationName'] = 'All';
        $result['totalEmployee'] = $this->as->getAllUserByDepartmentIdAndLocId($r->department_id, $r->location_id)->count();


        if (isset(Auth::user()->role)) {
            if (in_array(Auth::user()->role->role_id, [2, 3])) {
                $result['data'] = $this->asc->getAllAttendances($r);
            } elseif (Auth::user()->role->role_id == 4) {
                $departmentId = Auth::user()->department_id;
                $result['departmentName'] = getDepartmentById($departmentId)->name;

                $result['data'] = $this->asc->getAllAttendancesByDepartment($departmentId, $r);
                $result['totalEmployee'] = $this->as->getAllUserByDepartmentIdAndLocId($departmentId, $r->location_id)->count();
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
        foreach ($result['data'] as $attendance) {
            $timein = $attendance->user->schedule->time_in ?? '09:00';
            if ($attendance->attendance_timein <= ($timein . ':59' ?? '09:00:00')) {
                $ontimeCount++;
            } else {
                $lateCount++;
            }
        }

        $result['totalLate'] = $lateCount;
        $result['totalOntime'] = $ontimeCount;

        if ($r->department_id) {
            $result['departmentName'] = getDepartmentById($r->department_id)->name;
        }


        if ($r->location_id) {
            $result['locationName'] = getLocationById($r->location_id)->location_name;
        }

        return response()->json($result);
    }

    public function transferFile()
    {
        $folderPath = storage_path('/app/public/attendance');

        // Check if the folder exists
        if (is_dir($folderPath)) {
            // Get the files
            // $files = Storage::files($folderPath);
            $files = File::files($folderPath);
            // Output the files for debugging
            foreach ($files as $file) {

                $doc = file_get_contents($file);
                $filename = pathinfo($file)['basename'];
                // dd($filename);
                $disk = 'attendance-photo';

                Storage::disk($disk)->put($filename, $doc);

                $data['filename'][] = Storage::disk($disk)->url($filename);
                // $input['attachment'] = $nameFile['filename'];
            }
            dd($data);
        } else {
            // Output a message if the folder doesn't exist
            dd($folderPath);
        }
    }
}