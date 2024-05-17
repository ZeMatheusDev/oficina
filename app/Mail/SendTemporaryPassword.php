<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendTemporaryPassword extends Mailable
{
    use Queueable, SerializesModels;

    private $name;
    private $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $password)
    {
        $this->name = $name;
        $this->password = $password;
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
            ->subject('Bem vindo(a)! Aqui está a sua senha temporária de acesso.')
            ->view('mail.SendTemporaryPassword', [
                'name' => $this->name,
                'password' => $this->password,
            ]);
    }
}
