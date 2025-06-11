<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\EmailServices;
use Kutia\Larafirebase\Facades\Larafirebase;
use Illuminate\Console\Command;

class LogoutNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logoutNotify:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $es;
    public function __construct(EmailServices $emailService)
    {
        parent::__construct();

        $this->es = $emailService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get all employees whose punch-out time is within the next 30 minutes
        $employees = User::whereHas('attendance', function ($query) {
            $query->whereDate('created_at', now())->whereNotNull('schedule_id');
        })->whereHas('schedule', function ($query) {
            $query->whereTime('time_out', '>=', now())
                ->whereTime('time_out', '<=', now()->addMinutes(30));
        })->where(function ($query) {
            $query->where('notify_logout', false)->orWhereNull('notify_logout');
        })->get();

        // dd($employees);
        if (!empty($employees)) {
            foreach ($employees as $employee) {
                // Calculate the time difference in minutes
                $minutesUntilPunchOut = now()->diffInMinutes($employee->schedule->time_out);

                // send notification fcm
                $fcm_token = $employee->fcm_token;

                // cond if have then run fcm notification
                if ($fcm_token) {
                    Larafirebase::withTitle('Clockr - Punch Out Reminder')
                        ->withBody('30 minutes left. Please complete tasks promptly. Thank you.')
                        ->sendNotification($fcm_token);
                }

                // Send email notifications
                $this->es->PunchOutNotification($employee, $minutesUntilPunchOut);
                $employee->update(['notify_logout' => true]);
            }
            $this->info('Punch-out notifications sent successfully.');
        } else {
            $this->info('all send no employee left.');
        }
    }
}
