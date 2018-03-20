<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

use App\Client;

class MainSpecificationChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $affected_client;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Client $affected_client)
    {
        //dd($affected_client);
        $this->affected_client = $affected_client;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = Auth::user()->email;

        return $this//->from("$email")
                    ->view('emails.main_specification_changed');
    }
}
