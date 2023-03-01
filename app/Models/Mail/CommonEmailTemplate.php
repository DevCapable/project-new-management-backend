<?php

namespace App\Models\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CommonEmailTemplate extends Mailable
{
    use Queueable, SerializesModels;

    public $template;
    public $settings;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($template, $settings)
    {
        $this->template = $template;
        $this->settings = $settings;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $from = !empty($this->settings['company_email_from_name']) ? $this->settings['company_email_from_name'] : $this->template->from;

      $data =  $this->from($this->settings['company_email'], $from)->markdown('email.common_email_template')->subject($this->template->subject)->with(
            [
                'content' => $this->template->content,
                'mail_header' => (!empty($this->settings['company_name'])) ? $this->settings['company_name'] : env('APP_NAME'),
            ]
        );
      
      return $data;
    }
}
