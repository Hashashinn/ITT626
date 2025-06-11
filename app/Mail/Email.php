<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Email extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->data;

        if ($data['typeEmail'] == 'registerUser') {
            $view = 'email.registerUser';
        }

        if ($data['typeEmail'] == 'resetPassword') {
            $view = 'email.resetPassword';
        }

        if ($data['typeEmail'] == 'reminderHalfDay') {
            $view = 'email.reminderHalfDay';
        }

        if ($data['typeEmail'] == 'punchOutNotification') {
            $view = 'email.punchOutNotification';
        }


        if (isset($data['file'])) {
            return $this->view($view)
                ->from($data['from'], $data['nameFrom'])
                ->subject($data['subject'])
                ->cc($data['cc'])
                ->attach($data['file'], ['mime' => $data['typeAttachment']])
                ->with($data);
        } else {
            return $this->view($view)
                ->from($data['from'], $data['nameFrom'])
                ->subject($data['subject'])
                ->cc($data['cc'] ?? null)
                ->with($data);
        }
    }
}