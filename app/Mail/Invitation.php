<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Invitation extends Mailable
{
    use Queueable, SerializesModels;

    public $recipientName;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($recipientName, $url)
    {
        $this->recipientName = $recipientName;
        $this->url = $url;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $fromAddress = auth()->user()->email;
        $fromName = auth()->user()->name;
        return $this->view('invitation_confirm')
                    ->subject('Invitation to join our group!')
                    ->from($fromAddress, $fromName)
                    ->with([
                        'recipientName' => $this->recipientName, 
                        'url' => $this->url,
                        'fromName' => $fromName,
                    ]);
    }
    
}
