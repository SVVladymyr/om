<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;

class UserCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The request instance.
     *
     * @var UserRequest
     */
    public $request;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(UserRequest $request)
    {
        $this->request = $request;
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
                    ->view('emails.user_created');
    }
}
