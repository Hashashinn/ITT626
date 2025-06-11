<?php

namespace App\Services;

use App\Models\Schedule;

class ApiScheduleServices
{
    public function getAllSchedule()
    {
        $schedule = Schedule::all();
        $schedules = [];
        $schedules[] = $schedule;

        return $schedules;
    }

    public function addSchedule(array $data)
    {
        $schedules = [];
        
        foreach ($data as $scheduleData) {
            $schedule = new Schedule();
            $schedule->fill($scheduleData);
            $schedule->save();
            $schedules[] = $schedule;
        }

        return $schedules;
    }

    public function editSchedule(array $data)
    {
        $schedules = [];
        
    if (is_array($data)) {
        foreach ($data as $scheduleData) {
            if (isset($scheduleData['id'])) {
                $schedule = Schedule::find($scheduleData['id']);
                
                if ($schedule) {
                    $schedule->update([
                        'company_id' => $scheduleData['company_id'] ?? $schedule->company_id,
                        'slug' => $scheduleData['slug'] ?? $schedule->location_name,
                        'time_in' => $scheduleData['time_in'] ?? $schedule->lat,
                        'time_out' => $scheduleData['time_out'] ?? $schedule->lng,
                        'created_at' => $scheduleData['created_at'] ?? $schedule->created_at,
                        'updated_at' => $scheduleData['updated_at'] ?? $schedule->updated_at,
                    ]);
                    $schedules[] = $schedule;
                }
            }
        }
        return $schedules;
    }
    return response()->json(['error' => 'Invalid JSON array'], 400);
    }

    public function deleteSchedule($data)
    {   
        $data2 = Schedule::where('id', $data[0]['id'])->delete();

        return $data2;
    }
}
