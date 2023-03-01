<?php

namespace App\Models\Mail;

use App\Models\Client;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class passwordReset extends Mailable
{
    use Queueable, SerializesModels;
    public $action_link;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($action_link)
    {
        $this->action_link = $action_link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.password_resets')->subject('Password resets details - '.env('APP_NAME'));
    }
}
