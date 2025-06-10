<?php

namespace App\Imports;

use App\Models\Schedule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ScheduleImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $scheduleData = [
            'slug' => $row['slug'],
            'time_in' => $row['time_in'] ?? '08:00:00', // Set default value if not provided
            'time_out' => $row['time_out'] ?? '18:00:00', // Set default value if not provided
        ];

        return new Schedule($scheduleData);
    }
}
