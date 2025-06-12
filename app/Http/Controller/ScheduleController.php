<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Http\Requests\ScheduleEmp;
use App\Services\AdminServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{

    public function index()
    {
        $cond[0] = ['id', '!=', ''];

        if (Auth::user()->role->role_id != 3) {
            $cond[1] = ['company_id', Auth::user()->company_id];
        }

        return view('admin.schedule')->with('schedules', Schedule::where($cond)->get());
    }


    public function store(ScheduleEmp $request)
    {
        $request->validated();

        $schedule = new schedule;
        $schedule->slug = $request->slug;
        $schedule->time_in = $request->time_in;
        $schedule->time_out = $request->time_out;
        $schedule->save();




        flash()->success('Success', 'Schedule has been created successfully !');
        return redirect()->route('schedule.index');
    }

    public function update(ScheduleEmp $request, Schedule $schedule)
    {
        $request['time_in'] = str_split($request->time_in, 5)[0];
        $request['time_out'] = str_split($request->time_out, 5)[0];

        $request->validated();

        $schedule->slug = $request->slug;
        $schedule->time_in = $request->time_in;
        $schedule->time_out = $request->time_out;
        $schedule->save();
        flash()->success('Success', 'Schedule has been Updated successfully !');
        return redirect()->route('schedule.index');
    }


    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        flash()->success('Success', 'Schedule has been deleted successfully !');
        return redirect()->route('schedule.index');
    }

    public function scheduleStore(Request $r)
    {
        $ls = new AdminServices;

        $result = $ls->scheduleStore($r);

        return response()->json($result);
    }

    public function scheduleUpdate(Request $r, $id)
    {
        $ls = new AdminServices;

        $result = $ls->scheduleUpdate($r, $id);

        return response()->json($result);
    }

    public function scheduleDelete(Request $r, $id)
    {
        $ls = new AdminServices;

        $result = $ls->scheduleDelete($r, $id);

        return response()->json($result);
    }


    public function getScheduleById($id = '')
    {
        $as = new AdminServices;

        $result = $as->getScheduleById($id);

        return response()->json($result);
    }
}