<?php

namespace App\Models\Mail;

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendClientProjectPaymentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $project;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Client $user, Project $project)
    {
        $this->user = $user;
        $this->project = $project;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.client_project_payment_notification')->subject('Project Payment Notification - '.env('APP_NAME'));
    }
}
