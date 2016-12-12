<?php

namespace Mcms\Core\Mailables;


use Config;
use Mcms\Core\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Lang;

class NotifyAdminOnNewUser extends Mailable
{
    use Queueable, SerializesModels;


    public $user;
    public $model;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        if ( ! is_object($id)){
            $userModel = Config::get('auth.providers.users.model');
            $this->model = new $userModel();
            $this->user = $this->model->find($id);
        }
        else {
            $this->user = $id;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $from = new User([
            'email' => Config::get('mail.from.address'),
            'name' => Config::get('mail.from.name'),
        ]);

        $config = Config::get('frontEnd.user.mailables.NotifyAdminOnNewUser');

        return $this
            ->subject(Lang::get($config['subject'], $this->user->toArray()))
            ->with('user', $this->user)
            ->from($from)
            ->view($config['view']);
    }
}