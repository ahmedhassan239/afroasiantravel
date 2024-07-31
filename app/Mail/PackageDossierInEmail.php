<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PackageDossierInEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $tripdossier;
    public $email;


    public function __construct($tripdossier , $email)
    {
        //
        $this->tripdossier = $tripdossier;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->email)
                    ->from('enquiry@ask-aladdin.com')
                    ->view('package.mail')
                    ->subject('Ask-alaadin')
                    ->with($this->tripdossier);

    }
}
