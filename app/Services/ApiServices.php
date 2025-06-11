<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Timeoff;
use App\Models\User;
use App\Models\UserActivityOutside;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiServices
{
    use HttpResponses;

    protected $at;
    protected $es;

    public function __construct(EmailServices $es, AttendanceServices $at)
    {
        $this->at = $at;
        $this->es = $es;
    }

    public function captureAttendance($r)
    {
        try {
            // checking if user alredy punch in for current date
            $cond[0] = ['uid', Auth::user()->id];

            $input = $r->input();
            $input['uid'] = Auth::user()->id;
            if (!$r->byPass) {
                $att = Attendance::where($cond)->whereDate('created_at', today())->first();

                if ($att) {
                    return $this->error('', 'Already punch in. Please refresh the page', 500);
                }
            }

            // checking has file or not if have then upload img
            if ($r->hasfile('file')) {
                $file = $r->file('file');
                if ($file->getPathName()) {
                    $nameFile = uploadFile($file);
                    $input['attachment'] = $nameFile['filename'];
                }
            }

            // create data for attendance
            unset($input['byPass']);
            unset($input['file']);
            $input['emp_id'] = Auth::user()->id;
            $input['attendance_date'] = date("Y-m-d");
            $input['attendance_timein'] = date('H:i:s');

            $dataAtt = Attendance::create($input);

            return $this->success($dataAtt, 'Success Punch In', 200);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 'Error', 500);
        }
    }

    public function punchOutAttendance($r)
    {
        try {
            $id = $r->att_id;

            $input['attendance_timeout'] = date('H:i:s');

            Attendance::where('id', $id)->update($input);

            $data = Attendance::find($id);

            return $this->success($data, 'Success Punch Out', 200);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 'Error', 500);
        }
    }

    public function captureTimeOff($r)
    {
        try {
            $input = $r->input();

            // check timeoff for this month
            $timeoff = $this->at->getTimeOffByUserId();

            // by pass cond for testing only
            if (!$r->byPass) {
                if ($timeoff) {
                    $data['status'] = config('app.response.error.status');
                    $data['type'] = config('app.response.error.type');
                    $data['title'] = config('app.response.error.title');
                    $data['msg'] = 'Time Off already use for this month';

                    return $data;
                }
            }

            // create time off
            $timeoffData = [
                'lat' => $input['lat'],
                'lng' => $input['lng'],
                'timeoff_start' => date('H:i:s'),
                'remarks' => $input['remarks'],
                'user_id' => Auth::user()->id,
            ];

            $data = Timeoff::create($timeoffData);

            return $this->success($data, 'Success Start Time Off', 200);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 'Error', 500);
        }
    }

    public function punchOutTimeOff(Request $r)
    {
        try {
            $input = $r->input();
            $id = $r->id;

            if (!$id) {
                return $this->error('', 'Plese set input id', 500);
            }

            $timeoffData = [
                'lat' => $input['lat'] ?? '',
                'lng' => $input['lng'] ?? '',
                'timeoff_end' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ];

            // check timeoff more then 2 hours
            Timeoff::where('id', $id)->update($timeoffData);

            $timeoff = Timeoff::with('user')->where('id', $id)->first();


            $timeoff_start = strtotime($timeoff->timeoff_start);
            $timeoff_end = strtotime($timeoff->timeoff_end);

            $duration = ($timeoff_end - $timeoff_start) / 3600;

            if ($duration > 2) {
                $receivers = User::whereHas('role', function ($query) {
                    $query->where('role_id', 2);
                })
                    ->where('company_id', Auth::user()->company_id)
                    ->pluck('email')
                    ->merge([Auth::user()->email]);
                // send email reminder to apply half day to admin and employee
                foreach ($receivers as $receiver) {
                    $this->es->sendReminderHalfDayEmail($receiver, $timeoff);
                }
            }

            return $this->success($timeoff, 'Success End Time Off', 200);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 'Error', 500);
        }
    }

    public function captureOutsideActivity($r)
    {
        try {
            $input = $r->input();

            $currentDate = Carbon::today();
            $attendance = Attendance::where('uid', Auth::user()->id)->whereDate('created_at', $currentDate)->first();

            // check if user already punch out from attendance
            if ($attendance && $attendance->attendance_timeout) {
                return $this->error(['attendance_timeout' => $attendance->attendance_timeout], 'Cannot log activity after punch-out.', 500);
            }

            // check attachments
            if ($r->hasfile('file')) {
                $file = $r->file('file');
                if ($file->getPathName()) {
                    $nameFile = uploadFile($file);
                    $input['attachment'] = $nameFile['filename'];
                }
            } else {
                return $this->error('', 'Attachment is required in order to proceed', 500);
            }

            // check if user already punch in
            $user_id = Auth::user()->id;
            $currentDate = Carbon::today();
            $attendance = Attendance::where('uid', $user_id)
                ->whereDate('created_at', $currentDate)
                ->first();

            if (!$attendance) {
                return $this->error('', 'Please punch in first', 500);
            }

            $input['att_id'] = $attendance->id;
            $input['user_id'] = $user_id;
            $input['punch_in_time'] = date('H:i:s');

            UserActivityOutside::create($input);
            $data = UserActivityOutside::where('user_id', $user_id)->whereDate('created_at', now()->toDateString())->get();

            return $this->success($data, 'Success Log Record.', 200);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 'Error', 500);
        }
    }

    public function captureAttendanceOffline($r)
    {
        try {
            $data = $r->json()->all();
            $userId = Auth::user()->id;
            $dataTimeoff = [];
            $dataAtt = [];
            // punch in attendance
            if (isset($data['att']['punchIn'])) {

                // get attedance data
                foreach ($data['att']['punchIn'] as $punchIn) {
                    $attData = [
                        'lat' => $punchIn['lat'],
                        'lng' => $punchIn['lng'],
                        'uid' => $punchIn['uid'] ?? $userId,
                        'attendance_timein' => $punchIn['attendance_timein'],
                        'attendance_date' => $punchIn['attendance_date'],
                        'emp_id' => $punchIn['uid'] ?? $userId
                    ];

                    // insert attendance data
                    $dataAtt[] = Attendance::create($attData)->toArray();
                }
            }

            // punch out attendance
            // $data['att']['punchOut'] = [
            //     ['attendance_date' => '2023-10-10'], ['attendance_date' => '2023-10-11']
            // ];


            if (isset($data['att']['punchOut'])) {
                // dd($data['att']['punchOut']);
                // get attedance data
                foreach ($data['att']['punchOut'] as $punchOut) {
                    $attData = [
                        'lat' => $punchOut['lat'],
                        'lng' => $punchOut['lng'],
                        'attendance_timeout' => $punchOut['attendance_timeout'],
                    ];

                    // print_r($attData);
                    if (!isset($dataAtt)) {
                        $newAtt = Attendance::where('attendance_date', $punchOut['attendance_date'])->orderBy('id', 'DESC')->first();
                        Attendance::where([['id', $newAtt->id], ['attendance_date', $punchOut['attendance_date']]])->update($attData);
                    } else {
                        // insert attendance data
                        foreach ($dataAtt as $att) {
                            Attendance::where([['id', $att['id']], ['attendance_date', $punchOut['attendance_date']]])->update($attData);
                        }
                    }
                }
            }

            // punch in timeoff

            if (isset($data['timeoff']['punchIn'])) {
                foreach ($data['timeoff']['punchIn'] as $punchIn) {
                    // get timeoff data
                    $timeoffData = [
                        'lat' => $punchIn['lat'],
                        'lng' => $punchIn['lng'],
                        'user_id' => $punchIn['uid'] ?? $userId,
                        'timeoff_start' => $punchIn['timeoff_start'],
                        'timeoff_date' => $punchIn['timeoff_date'],
                        'remarks' => $punchIn['remarks']
                    ];

                    // insert timeoff data
                    $dataTimeoff[] = Timeoff::create($timeoffData);
                }
            }

            // punch out timeoff
            if (isset($data['timeoff']['punchOut'])) {
                foreach ($data['timeoff']['punchOut'] as $punchOut) {
                    // get timeoff data
                    $timeoffData = [
                        'lat' => $punchOut['lat'],
                        'lng' => $punchOut['lng'],
                        'timeoff_end' => $punchOut['timeoff_end'],
                    ];
                    // update timeoff data
                    foreach ($dataTimeoff as $timeoff) {
                        Timeoff::where([['id', $timeoff->id], ['timeoff_date', $punchOut['timeoff_date']]])->update($timeoffData);
                    }
                }
            }

            // punch in outside
            if (isset($data['outside']['punchIn'])) {
                foreach ($data['outside']['punchIn'] as $punchIn) {

                    if (!isset($dataAtt)) {
                        $dataAtt[] = Attendance::where('attendance_date', $punchIn['outside_date'])->orderBy('id', 'DESC')->first();
                    }
                    // get outside data
                    foreach ($dataAtt as $att) {
                        if ($att['attendance_date'] == $punchIn['outside_date']) {
                            $outsideData = [
                                'lat' => $punchIn['lat'],
                                'lng' => $punchIn['lng'],
                                'user_id' => $att['uid'],
                                'punch_in_time' => $punchIn['punch_in_time'],
                                'att_id' => $att['id']
                            ];

                            // insert outside data
                            UserActivityOutside::create($outsideData);
                        }
                    }
                }
            }

            // return data success sync
            return $this->success(["att" => $dataAtt, "timeoff" => $dataTimeoff], 'Success push offline data', 200);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 'Error', 500);
        }
    }

    public function getAllUserByDepartmentIdAndLocId($departId, $locId)
    {
        $cond[0] = ['company_id', Auth::user()->company_id];

        if ($departId) {
            $cond[1] = ['department_id', $departId];
        }
        if ($locId) {
            $cond[2] = ['location_id', $locId];
        }

        $data = User::where($cond)->get();

        return $data;
    }
}
