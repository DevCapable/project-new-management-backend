<?php

namespace App\Models\Mail;

use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectInvoicePayments;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUserProjectRecieptNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $project;
    public $invoicePayments;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Project $project, ProjectInvoicePayments $invoicePayments)
    {
        $this->user = $user;
        $this->project = $project;
        $this->invoicePayments = $invoicePayments;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.user_project_receipt_notification')->subject('Project Receipt Notification - '.env('APP_NAME'));
    }
}
