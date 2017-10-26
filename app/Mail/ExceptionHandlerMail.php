<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExceptionHandlerMail extends Mailable
{

    use Queueable, SerializesModels;

    protected $exception;

    /**
     * ExceptionHandler constructor.
     * @param $exception
     */
    public function __construct($exception)
    {
        $this->exception = $exception;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.error')
            ->with([
                'exception' => $this->exception
            ]);
    }
}
