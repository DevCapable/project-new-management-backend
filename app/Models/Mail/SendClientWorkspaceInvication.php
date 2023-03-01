<?php

namespace App\Models\Mail;

use App\Models\Client;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendClientWorkspaceInvication extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $workspace;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Client $user,Workspace $workspace)
    {
        $this->user = $user;
        $this->workspace = $workspace;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.workspace_invitation')->subject('New Workspace Invitation - '.env('APP_NAME'));
    }
}
