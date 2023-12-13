<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SuggestionFormMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @param array $data
     */
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
        return $this->from($this->data["email"])
            ->subject("New Suggestion from " . $this->data["name"])
            ->view("emails.suggestion")
            ->with([
                "suggestion" => $this->data["suggestion"],
            ]);
    }
}
