<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveApplicationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $duration;
    public $halfDay;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($duration, $halfDay)
    {
        $this->duration = $duration;
        $this->halfDay = $halfDay;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.leave-application')
                    ->subject('Leave Application')
                    ->with([
                        'duration' => $this->duration,
                        'halfDay' => $this->halfDay,
                    ]);
    }
}
