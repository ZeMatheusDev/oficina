<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendLinkForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    private $code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $code)
    {
        $this->name = $name;
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('raiox@proconph.com.br', env('APP_NAME'))
            ->subject('Recuperação de senha - Sistema Raio-X.')
            ->view('mail.SendLinkForgotPassword', [
                'name' => $this->name,
                'code' => $this->code,
            ]);
    }
}
