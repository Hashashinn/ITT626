<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Timeoff;
use App\Models\User;
use App\Models\UserActivityOutside;
use App\Models\Location;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

// Get the current date
class AttendanceServices
{
    public function getAttendanceById($id = '')
    {
        $data = Attendance::find($id);

        return $data;
    }

    public function store($r, $type = '')
    {
        $input = $r->input();

        $attData = Attendance::where('uid', Auth::user()->id)->whereDate('created_at', today())->first();

        if ($attData) {
            $data['status'] = config('app.response.error.status');
            $data['type'] = config('app.response.error.type');
            $data['title'] = config('app.response.error.title');
            $data['msg'] = 'Already punch in. Please refresh the page';

            return $data;
        }

        unset($input['_token']);

        if ($type == 'punchOut') {
            $input['attendance_timeout'] = date("H:i:s");;
        } else {
            $input['attendance_timein'] = date("H:i:s");
        }

        if ($r->hasfile('file')) {
            $file = $r->file('file');
            if ($file->getPathName()) {
                $nameFile = uploadFile($file);
                $input['attachment'] = $nameFile['filename'];
            }
        }

        $input['attendance_date'] = date("Y-m-d");

        $input['emp_id'] = 0;
        $input['uid'] = Auth::user()->id;
        unset($input['file']);

        Attendance::create($input);

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Success.';

        return $data;
    }

    public function update($r, $id)
    {
        $input['attendance_date'] = date("Y-m-d");
        $input['attendance_timeout'] = date("H:i:s");

        Attendance::where('id', $id)->update($input);

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Success Update.';


        return $data;
    }

    public function delete($r, $id)
    {
        // $input = $r->input();
        // unset($input['_token']);
        Attendance::where('id', $id)->delete();

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Success Delete.';


        return $data;
    }

    public function getAttendanceByIdByDate($id = '', $date = '')
    {
        $cond[0] = ['uid', $id];
        $cond[1] = ['attendance_date', $date];

        $data = Attendance::where($cond)->first();

        return $data;
    }

    public function getAllAttendancesByUserId($id = '', $r)
    {
        $input = [];
        if ($r) {
            $input = $r->input();
        }
        $cond[0] = ['uid', $id];

        $data = Attendance::with('location', 'user', 'department', 'schedule')->where(function ($query) use ($input, $cond) {
            if (isset($input['max']) && isset($input['min'])) {
                $query->where('created_at', '>=', $input["min"])
                    ->where('created_at', '<=', $input["max"] . ' 23:59:59');
            }
            $query->where($cond);
        })->paginate(5);

        return $data;
    }

    public function getAllAttendancesByDepartment(Request $r)
    {
        $input = [];
        if ($r) {
            $input = $r->input();
        }
        $companyId = Auth::user()->company->id;
        $roleId = Auth::user()->role->role_id;
    
        $departmentId = $r->input('department_id');
        $locationId = $r->input('location_id');
    
        $baseQuery = Attendance::with('location', 'user', 'department', 'schedule')
            ->where(function ($query) use ($input) {
                if (isset($input['max']) && isset($input['min'])) {
                    $query->where('created_at', '>=', $input["min"])
                        ->where('created_at', '<=', $input["max"] . ' 23:59:59');
                }
            })
            ->whereHas('user', function ($query) use ($companyId, $roleId, $departmentId, $locationId) {
                if ($roleId != 3) {
                    $query->where('company_id', $companyId);
                }
    
                if ($departmentId) {
                    $query->where('department_id', $departmentId);
                }

                if ($locationId) {
                    $query->where('location_id', $locationId);
                }
            })
            ->orderBy('created_at', 'DESC');
    
        // Execute the base query to get all results
        $data = $baseQuery->get();
    
        // Paginate the results
        $perPage = 5; // You can adjust the number of records per page
        $currentPage = Paginator::resolveCurrentPage('page');
        $currentPageItems = $baseQuery->skip(($currentPage - 1) * $perPage)->take($perPage)->get();
    
        $data = new LengthAwarePaginator($currentPageItems, $data->count(), $perPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
        // dd($data);
        return $data;
    }
    


    public function getAllAttendancesByUserIdByMonth($id = '', $type = '')
    {
        $cond[0] = ['uid', $id];

        $currentMonth = date('m');
        $time = '09:00:00';

        if ($type == 'late') {
            $compare = '>';
        } else {
            $compare = '<=';
        }

        $companyId = Auth::user()->company_id;

        $data = Attendance::with('location', 'user')
            ->whereHas('user', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->where($cond)
            ->whereMonth('created_at', $currentMonth)
            ->whereTime('attendance_timein', $compare, $time)
            ->get();

        return $data;
    }

    public function getAllAttendancesByMonth($type = '', $department = '')
    {

        $cond[0] = ['id', '!=', NULL];


        if ($department) {
            $cond[2] = ['department_id', $department];
        }

        $currentMonth = date('m');
        $time = '09:00:00';

        if ($type == 'late') {
            $compare = '>';
        } else {
            $compare = '<=';
        }

        $companyId = Auth::user()->company_id;

        $data = Attendance::with('location', 'user')
            ->whereHas('user', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->where($cond)
            ->whereMonth('created_at', $currentMonth)
            ->whereTime('attendance_timein', $compare, $time)
            ->get();

        return $data;
    }

    public function getAllAttendancesByMonthAndDepartment($type = '', $departmentId = '')
    {
        $cond[0] = ['id', '!=', NULL];

        // get user with department id
        $users = User::where('department_id', $departmentId)->get();

        foreach ($users as $user) {
            $userIds[] = $user->id;
        }

        $currentMonth = date('m');
        $time = '09:00:00';

        if ($type == 'late') {
            $compare = '>';
        } else {
            $compare = '<=';
        }
        $companyId = Auth::user()->company_id;

        $data = Attendance::with('location', 'user')
            ->whereHas('user', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->whereIn('uid', $userIds)
            ->whereMonth('created_at', $currentMonth)
            ->whereTime('attendance_timein', $compare, $time)
            ->get();

        return $data;
    }

    public function getTodayAttendanceById()
    {
        $currentDate = Carbon::today();

        $data = Attendance::whereDate('created_at', $currentDate)->first();

        return $data;
    }

    public function punchOut($r, $id = '')
    {
        $input = $r->input();
        unset($input['_token']);

        $input['attendance_timeout'] = date('H:i:s');

        // dd($input);
        Attendance::where('id', $id)->update($input);

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Success';


        return $data;
    }

    public function getAllAttendances(Request $r)
    {
        $input = [];
        if ($r) {
            $input = $r->input();
        }
        $companyId = Auth::user()->company->id;
        $roleId = Auth::user()->role->role_id;

        $departmentFilter = $r->input('department_id');
        $locationFilter = $r->input('location_id');

        $baseQuery = Attendance::with('location', 'user', 'department', 'schedule')
            ->where(function ($query) use ($input) {
                if (isset($input['max']) && isset($input['min'])) {
                    $query->where('created_at', '>=', $input["min"])
                        ->where('created_at', '<=', $input["max"] . ' 23:59:59');
                }
            })
            ->whereHas('user', function ($query) use ($companyId, $roleId, $departmentFilter, $locationFilter) {
                if ($roleId != 3) {
                    $query->where('company_id', $companyId);
                }

                if ($departmentFilter) {
                    $query->where('department_id', $departmentFilter);
                }

                if ($locationFilter) {
                    $query->where('location_id', $locationFilter);
                }
            })
            ->orderBy('created_at', 'DESC');

        // Execute the base query to get all results
        $data = $baseQuery->get();

        // Paginate the results
        $perPage = 5; // You can adjust the number of records per page
        $currentPage = Paginator::resolveCurrentPage('page');
        $currentPageItems = $baseQuery->skip(($currentPage - 1) * $perPage)->take($perPage)->get();

        $data = new LengthAwarePaginator($currentPageItems, $data->count(), $perPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
        // dd($data);
        return $data;
    }

    public function storeActivityUser($r, $type = '')
    {
        $input = $r->input();
        unset($input['_token']);

        $currentDate = Carbon::today();
        $attendance = Attendance::where('uid', Auth::user()->id)->whereDate('created_at', $currentDate)->first();

        if ($attendance && $attendance->attendance_timeout) {
            $data['status'] = config('app.response.error.status');
            $data['type'] = config('app.response.error.type');
            $data['title'] = config('app.response.error.title');
            $data['msg'] = 'Cannot log activity after punch-out.';
            $data['data'] = ['attendance_timeout' => $attendance->attendance_timeout];

            return $data;
        }

        if ($r->hasfile('file')) {
            $file = $r->file('file');
            if ($file->getPathName()) {
                $nameFile = uploadFile($file);
                $input['attachment'] = $nameFile['filename'];
            }
        } else {
            $data['status'] = config('app.response.error.status');
            $data['type'] = config('app.response.error.type');
            $data['title'] = config('app.response.error.title');
            $data['msg'] = 'Attachment is required in order to proceed';

            return $data;
        }

        // find att_id by user_id with condition created_at = today
        $user_id = Auth::user()->id;
        $currentDate = Carbon::today();
        $attendance = Attendance::where('uid', $user_id)
            ->whereDate('created_at', $currentDate)
            ->first();
        // if att data nor exst for current date then prompt Please punch in first
        if (!$attendance) {
            // Attendance for today does not exist
            $data['status'] = config('app.response.error.status');
            $data['type'] = config('app.response.error.type');
            $data['title'] = config('app.response.error.title');
            $data['msg'] = 'Please punch in first';
            return $data;
        }
        // define input['att_id'] = att id php
        $input['att_id'] = $attendance->id;
        $input['user_id'] = $user_id;
        $input['punch_in_time'] = date('H:i:s');
        // $input['punch_out_time'] = Auth::user()->id;

        UserActivityOutside::create($input);

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Success Log Record.';

        return $data;
    }

    public function getActivityOutsideByUserId($id)
    {
        $data = UserActivityOutside::where('user_id', $id)->get();

        return $data;
    }

    public function getActivityOutsideByAttId($id)
    {
        $data = UserActivityOutside::where('att_id', $id)->get();

        return $data;
    }

    public function getTimeOffByUserId()
    {
        // Get all time off records for the authenticated user for the current month
        $data = Timeoff::where('user_id', Auth::user()->id)->whereMonth('created_at', date('m'))->first();

        return $data;
    }

    public function storeTimeoff($r)
    {
        $input = $r->input();

        $timeoff = $this->getTimeOffByUserId();

        if ($timeoff) {
            $data['status'] = config('app.response.error.status');
            $data['type'] = config('app.response.error.type');
            $data['title'] = config('app.response.error.title');
            $data['msg'] = 'Time Off already use for this month';

            return $data;
        }

        $timeoffData = [
            'lat' => $input['lat'],
            'lng' => $input['lng'],
            'timeoff_start' => date('H:i:s'),
            'remarks' => $input['remarks'],
            'user_id' => Auth::user()->id,
        ];

        Timeoff::create($timeoffData);

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Success Start Time Off.';

        return $data;
    }

    public function updateTimeoff($r, $id = '')
    {
        $input = $r->input();

        $timeoffData = [
            'lat' => $input['lat'],
            'lng' => $input['lng'],
            'timeoff_end' => date('H:i:s'),
            // 'remarks' => $input['remarks'],
            'user_id' => Auth::user()->id,
        ];

        Timeoff::find($id)->update($timeoffData);

        $timeoff = Timeoff::with('user')->find($id);


        $timeoff_start = strtotime($timeoff->timeoff_start);
        $timeoff_end = strtotime($timeoff->timeoff_end);

        $duration = ($timeoff_end - $timeoff_start) / 3600;

        // more than 2 hours
        if ($duration > 2) {
            // send email reminder to apply half day to admin and employee
            $es = new EmailServices;
            $receivers = User::whereHas('role', function ($query) {
                $query->where([['role_id', 2], ['department_id', Auth::user()->department_id], ['company_id', Auth::user()->company_id]]);
            })
                ->where('company_id', Auth::user()->company_id)
                ->pluck('email')
                ->merge([Auth::user()->email]);
            foreach ($receivers as $receiver) {
                $es->sendReminderHalfDayEmail($receiver, $timeoff);
            }
        }

        $data['status'] = config('app.response.success.status');
        $data['type'] = config('app.response.success.type');
        $data['title'] = config('app.response.success.title');
        $data['msg'] = 'Success End Time Off.';

        return $data;
    }

    public function getAllTimeoff(Request $r)
    {
        $companyId = Auth::user()->company->id;
        $roleId = Auth::user()->role->role_id;

        $departmentFilter = $r->input('department_id');
        $locationFilter = $r->input('location_id');

        $data = Timeoff::with('user')->whereHas('user', function ($query) use ($companyId, $roleId, $departmentFilter, $locationFilter) {
            if ($roleId != 3) {
                $query->where('company_id', $companyId);
            }
            if ($departmentFilter) {
                $query->where('department_id', $departmentFilter);
            }

            if ($locationFilter) {
                $query->where('location_id', $locationFilter);
            }
        });

        return $data;
    }

    public function getAllTimeoffByUserId()
    {
        $data = Timeoff::where('user_id', Auth::user()->id)->get();

        return $data;
    }

    public function getAllTimeoffByDepartment()
    {
        $companyId = Auth::user()->company->id;

        $users = User::where([['department_id', Auth::user()->department_id], ['company_id', $companyId]])->get();

        foreach ($users as $user) {
            $user_ids[] = $user->id;
        }
        $data = Timeoff::with('user')->whereIn('user_id', $user_ids)->get();

        return $data;
    }

    public function search($id, $r)
    {
        $data = [
            'min' => $r->input('min'),
            'max' => $r->input('max'),
        ];
        // Retrieve attendances for administrators
        if (Auth::user()->role == 'admin') {
            $departmentId = null;
            $locationId = null;
            // Assuming $r is an instance of Illuminate\Http\Request
            if ($r->has('department_id')) {
                $departmentId = $r->input('department_id');
            }
            if ($r->has('location_id')) {
                $locationId = $r->input('location_id');
            }

            $query = Attendance::whereHas('user', function ($query) use ($departmentId, $locationId) {
                if ($departmentId !== null) {
                    $query->where('department_id', $departmentId);
                }
                if ($locationId !== null) {
                    $query->where('location_id', $locationId);
                }
            });
        }
        // Retrieve attendances for department
        elseif (Auth::user()->role == 'department') {
            dd('ss');
            $query = Attendance::whereHas('user', function ($query) use ($id) {
                $query->where('department_id', $id);
            });
            if ($data['min'] && $data['max']) {
                $query->whereDate('created_at', '>=', $data['min'])
                    ->whereDate('created_at', '<=', $data['max']);
            }
            $data['attendances'] = $query->get();
        }
        // Retrieve attendances for other users
        else {
            $userId = Auth::user()->id;
            $query = Attendance::where('uid', $userId);

            if ($data['min'] && $data['max']) {
                $query->whereDate('created_at', '>=', $data['min'])
                    ->whereDate('created_at', '<=', $data['max']);
            }

            if ($r->has('department_id')) {
                $query->where('department_id', $r->input('department_id'));
            }

            if ($r->has('location_id')) {
                $query->where('location_id', $r->input('location_id'));
            }
        }
        $data['attendances'] = $query->get();

        return $data;
    }


    function generatePDFContent($attendances, $departmentFilter, $locationFilter)
    {

        // Count total late instances
        // $totalLate = Attendance::where('attendance_timein', '>=', '08:30:00')->count();

        // // Count total on-time instances
        // $totalOnTime = Attendance::where('attendance_timein', '<', '08:30:00')->count();
        // $totalEmployees = Attendance::distinct('uid')->count('uid');
        
        // Title
        $html = '<h1 style="text-align:center;">AMS - NADI BINARI</h1>';

        // Divider
        $html .= '<hr style="border-top: 2px solid black; margin-bottom: 20px;">';
        // foreach ($attendances as $attendance) {
        //     $location = optional($attendance->location)->location_name ?? "-";
        //     $department=optional($attendance->department)->name ?? "-";
        // }
        $html .= 'Date Start: ' . $attendances->first()->attendance_date . '<br>';
        $html .= 'Date End :' . $attendances->last()->attendance_date . '<br>';
        $html .= 'Department :' . $departmentFilter . '<br>';
        $html .= 'Location : ' . $locationFilter . '<br>';
        // $html .= 'Total Late :' . $totalLate . '<br>';
        // $html .= 'Total On Time : ' . $totalOnTime . '<br>';
        // $html .= 'Total Employee :' . $totalEmployees . '<br>';

        $html .= '<hr style="border-top: 2px solid black; margin-bottom: 20px;">';


        $html .= '<table style="border-collapse: collapse; width: 100%; border: 1px solid black;">'; // Add border-collapse and width style
        $html .= '<thead><tr><th style="border: 1px solid black;">Date</th>';
        $html .= '<th style="border: 1px solid black;">Location</th>';
        $html .= '<th style="border: 1px solid black;">Department</th>';
        $html .= '<th style="border: 1px solid black;">Name</th>';
        $html .= '<th style="border: 1px solid black;">Punch In</th>';
        $html .= '<th style="border: 1px solid black;">Punch Out</th>';
        $html .= '<th style="border: 1px solid black;">Status</th>';
        $html .= '<th style="border: 1px solid black;">Lat</th>';
        $html .= '<th style="border: 1px solid black;">Long</th></tr></thead>';
        $html .= '<tbody>';
        foreach ($attendances as $attendance) {


            $html .= '<tr><td style="border: 1px solid black;">' . $attendance->attendance_date . '</td>';
            $html .= '<td style="border: 1px solid black;">' . optional($attendance->location)->location_name ?? '-' . '</td>';
            $html .= '<td style="border: 1px solid black;">' . optional(optional($attendance->user)->department)->name ?? '-' . '</td>';
            $html .= '<td style="border: 1px solid black;">' . optional($attendance->user)->name ?? '-' . '</td>';
            $html .= '<td style="border: 1px solid black;">' . $attendance->attendance_timein . '</td>';
            $html .= '<td style="border: 1px solid black;">' . $attendance->attendance_timeout . '</td>';
            $attendanceTime = strtotime($attendance->attendance_timein);
            $eightThirtyAM = strtotime('08:30:00');
            if ($attendanceTime >= $eightThirtyAM) {
                $html .= '<td style="border: 1px solid black;">Late</td>'; 
            } else {
                $html .= '<td style="border: 1px solid black;">On Time</td>'; 
            }
            $html .= '<td style="border: 1px solid black;">' . $attendance->lat ?? "-" . '</td>';
            $html .= '<td style="border: 1px solid black;">' . $attendance->lng  ?? "-" . '</td></tr>';
        }
        $html .= '</tbody></table>';
        return $html;
    }


    public function downloadPDF(Request $request)
    {
        
        $input = $request->input();
        $companyId = Auth::user()->company->id;
        $roleId = Auth::user()->role->role_id;

        $departmentFilter = $request->input('department_id');
        $locationFilter = $request->input('location_id');

        $location = Location::find($locationFilter);
        $department = Department::find($departmentFilter);

        if ($department) {
            $departmentName = $department->name;
        } else {
            $departmentName = "-";
        }
        if ($location) {
            $locationName = $location->location_name;
        } else {
            $locationName = "-";
        }
        
        if ($roleId == '2' || $roleId == '3' ){
            ///getAllAttendances
            $baseQuery = Attendance::with('location', 'user', 'department', 'schedule')
            ->where(function ($query) use ($input) {
                if (isset($input['max']) && isset($input['min'])) {
                    $query->where('created_at', '>=', $input["min"])
                        ->where('created_at', '<=', $input["max"] . ' 23:59:59');
                }
            })
            ->whereHas('user', function ($query) use ($companyId, $roleId, $departmentFilter, $locationFilter) {
                if ($roleId != 3) {
                    $query->where('company_id', $companyId);
                }

                if ($departmentFilter) {
                    $query->where('department_id', $departmentFilter);
                }

                if ($locationFilter) {
                    $query->where('location_id', $locationFilter);
                }
            })
            ->orderBy('created_at', 'DESC');
        }else if( $roleId == '4'){
            //getAllAttendancesByDepartment
            $baseQuery = Attendance::with('location', 'user', 'department', 'schedule')
            ->where(function ($query) use ($input) {
                if (isset($input['max']) && isset($input['min'])) {
                    $query->where('created_at', '>=', $input["min"])
                        ->where('created_at', '<=', $input["max"] . ' 23:59:59');
                }
            })
            ->whereHas('user', function ($query) use ($companyId, $roleId, $departmentFilter, $locationFilter) {
                if ($roleId != 3) {
                    $query->where('company_id', $companyId);
                }
    
                if ($departmentFilter) {
                    $query->where('department_id', $departmentFilter);
                }

                if ($locationFilter) {
                    $query->where('location_id', $locationFilter);
                }
            })
            ->orderBy('created_at', 'DESC');
        }else {
            //getAllAttendancesByUserId
            $userId = Auth::user()->id;
            $cond[0] = ['uid', $userId];
            $baseQuery = Attendance::with('location', 'user', 'department', 'schedule')->where(function ($query) use ($input, $cond) {
                if (isset($input['max']) && isset($input['min'])) {
                    $query->where('created_at', '>=', $input["min"])
                        ->where('created_at', '<=', $input["max"] . ' 23:59:59');
                }
                $query->where($cond);
            });
        }

        $attendances = $baseQuery->get();

            $mpdf = new \Mpdf\Mpdf();
            
    
        if ($attendances->count() > 0) {

            $attendancesChunks = $attendances->chunk(1000);

            $pdfContents = [];

            foreach ($attendancesChunks as $index => $attendancesChunk) {

                $mpdf = new \Mpdf\Mpdf();

            
                $html = $this->generatePDFContent($attendancesChunk, $departmentName, $locationName);

        
                $mpdf->WriteHTML($html);


                $pdfContents[] = $mpdf->Output('', 'S');
            }

         
            $headers = [
                'Content-Type' => 'application/zip',
                'Content-Disposition' => 'attachment; filename="attendances.zip"',
            ];

  
            $zipFilePath = tempnam(sys_get_temp_dir(), 'attendances') . '.zip';
            $zip = new \ZipArchive();
            $zip->open($zipFilePath, \ZipArchive::CREATE);

           
            foreach ($pdfContents as $index => $pdfContent) {
                $zip->addFromString('attendances_set_' . ($index + 1) . '.pdf', $pdfContent);
            }

            $zip->close();

           
            return response()->file($zipFilePath, $headers)->deleteFileAfterSend(true);
        }

        
        return '<p>No attendance records found</p>';
    }

    public function downloadCSV(Request $request)
    {
        $input = $request->input();
        $companyId = Auth::user()->company->id;
        $roleId = Auth::user()->role->role_id;

        $departmentFilter = $request->input('department_id');
        $locationFilter = $request->input('location_id');

        if ($roleId == '2' || $roleId == '3' ){
            ///getAllAttendances
            $baseQuery = Attendance::with('location', 'user', 'department', 'schedule')
            ->where(function ($query) use ($input) {
                if (isset($input['max']) && isset($input['min'])) {
                    $query->where('created_at', '>=', $input["min"])
                        ->where('created_at', '<=', $input["max"] . ' 23:59:59');
                }
            })
            ->whereHas('user', function ($query) use ($companyId, $roleId, $departmentFilter, $locationFilter) {
                if ($roleId != 3) {
                    $query->where('company_id', $companyId);
                }

                if ($departmentFilter) {
                    $query->where('department_id', $departmentFilter);
                }

                if ($locationFilter) {
                    $query->where('location_id', $locationFilter);
                }
            })
            ->orderBy('created_at', 'DESC');
        }else if( $roleId == '4'){
            //getAllAttendancesByDepartment
            $baseQuery = Attendance::with('location', 'user', 'department', 'schedule')
            ->where(function ($query) use ($input) {
                if (isset($input['max']) && isset($input['min'])) {
                    $query->where('created_at', '>=', $input["min"])
                        ->where('created_at', '<=', $input["max"] . ' 23:59:59');
                }
            })
            ->whereHas('user', function ($query) use ($companyId, $roleId, $departmentFilter, $locationFilter) {
                if ($roleId != 3) {
                    $query->where('company_id', $companyId);
                }
    
                if ($departmentFilter) {
                    $query->where('department_id', $departmentFilter);
                }

                if ($locationFilter) {
                    $query->where('location_id', $locationFilter);
                }
            })
            ->orderBy('created_at', 'DESC');
        }else {
            //getAllAttendancesByUserId
            $userId = Auth::user()->id;
            $cond[0] = ['uid', $userId];
            $baseQuery = Attendance::with('location', 'user', 'department', 'schedule')->where(function ($query) use ($input, $cond) {
                if (isset($input['max']) && isset($input['min'])) {
                    $query->where('created_at', '>=', $input["min"])
                        ->where('created_at', '<=', $input["max"] . ' 23:59:59');
                }
                $query->where($cond);
            });
        }

        $attendances = $baseQuery->get();

        $csv = [];

        // Add header row
        $csv[] = ['Date', 'Location', 'User', 'Department', 'Time In', 'Time Out', 'Status', 'Lat', 'Long']; // Adjust column names as needed

        // Add data rows
        foreach ($attendances as $attendance) {
            if ($attendance->attendance_timein >= strtotime('08:30:00')) {
                $status = 'Late';
            } else {
                $status = 'On Time';
            }
            $csv[] = [
                $attendance->attendance_date,
                optional($attendance->location)->location_name,
                optional($attendance->user)->name,
                optional(optional($attendance->user)->department)->name,
                $attendance->attendance_timein,
                $attendance->attendance_timeout,
                $status,
                $attendance->lat,
                $attendance->lng
            ];
        }

        // Generate CSV file
        $file = fopen('php://temp', 'w');
        foreach ($csv as $row) {
            fputcsv($file, $row);
        }
        rewind($file);
        $csvContent = stream_get_contents($file);
        fclose($file);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="attendances.csv"',
        ];
        return response($csvContent, 200, $headers);
    }
}
