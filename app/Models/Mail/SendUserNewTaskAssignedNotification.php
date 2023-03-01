<?php

namespace App\Models\Mail;

use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUserNewTaskAssignedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $task;  /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user,Task $task)  {
        $this->user = $user;
        $this->task = $task;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.new_task_assigned_notification')->subject('New Task Assigned - '.env('APP_NAME'));
    }
}
