<?php

namespace App\Mail;

use App\Enquiry as AppEnquiry;
use App\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentDetailsToAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $enquiry;

    /**
     * Create a new message instance.
     *
     * @param Enquiry $enquiry
     */
    public function __construct(Enquiry $enquiry)
    {
        $this->enquiry = $enquiry;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('booking@afroasiantravel.com', 'Afro Asian Travel')
                    ->subject('Upcoming Enquiry Details for ' . $this->enquiry->name)
                    ->view('emails.appointmentDetailsToAdmin');
    }
}
