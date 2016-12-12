<?php

namespace Mcms\Core\Models;

use Config;
use Mcms\Core\Mailables\ResetPasswordNotification;
use Mcms\Core\QueryFilters\Filterable;
use Mcms\Core\Traits\EntrustUserWithPermissions;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Mcms\Core\Traits\Presentable;
use Illuminate\Notifications\Notifiable;
use Mcms\FrontEnd\Helpers\Sluggable;
use Mcms\Core\Traits\ExtraFields;

/**
 * The User model
 * Class User
 * @package Mcms\Core\Models
 */
class User extends Authenticatable
{
    use Presentable, CanResetPassword, Filterable, EntrustUserWithPermissions, Notifiable, ExtraFields, Sluggable;
    public $route;
    public $config;
    /**
     * Set the presenter class. Will add extra view-model presenter methods
     * @var string
     */
    protected $presenter = 'Mcms\Core\Presenters\UserPresenter';
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'firstName', 'lastName', 'email', 'password', 'settings', 'profile', 'confirmation_code',
        'activated_at', 'active', 'awaits_moderation', 'username'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at', 'confirmation_code',
        'activated_at', 'active', 'awaits_moderation'
    ];
    /**
     * Auto cast variables to types
     * @var array
     */
    protected $casts = [
        'profile' => 'array',
        'settings' => 'array',
        'active' => 'boolean'
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'activated_at'];
    protected $defaultRoute = 'user';

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->config = Config::get('core.user');
        $this->defaultRoute = (isset($this->config['route'])) ? $this->config['route'] : $this->defaultRoute;
    }
    /**
     * Check if i am the owner of this model
     * @param $relation
     * @return bool
     */
    public function owns($relation)
    {
        return $relation->uid == $this->id;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRoles()
    {
        return $this->roles()->get();
    }

    /**
     * Override to change the notification
     *
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $class = null;
        if ( \Config::has('frontEnd.user.mailables.lostPassword.handle')) {
            $class = \Config::get('frontEnd.user.mailables.lostPassword.handle');
        }

        $notificationClass = ($class) ? new $class($token) : new ResetPasswordNotification($token);
        $this->notify($notificationClass);
    }

    public function maxLevel()
    {
        if ( ! isset($this->roles) || ! $this->roles) {
            return 0;
        }

        return $this->roles->pluck('level')->max();
    }

    public function extraFields()
    {
        return $this->hasMany(UserExtraFieldValue::class, 'item_id')
            ->where('model', get_class($this));
    }
}
