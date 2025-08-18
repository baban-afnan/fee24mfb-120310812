<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class CustomEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $title;
    public $subjectLine;
    public $content;
    public $buttonUrl;
    public $buttonText;

    public function __construct($subjectLine, $title, $content, $buttonUrl = null, $buttonText = null)
    {
        $this->subjectLine = $subjectLine;
        $this->title = $title;
        $this->content = $content;
        $this->buttonUrl = $buttonUrl;
        $this->buttonText = $buttonText;
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
                    ->view('emails.custom');
    }
}
