<?php

namespace Mcms\Core\Http\Controllers\Api;

use Auth;
use Config;
use ItemConnector;
use Lang;
use LaravelLocalization;
use Mcms\Core\Models\Permission;
use Mcms\Core\Models\Role;
use Mcms\Core\Services\Lang\Contracts\LanguagesContract;
use Mcms\Core\Services\User\GateKeeper;

class Boot
{
    protected $translations;

    public function __construct(LanguagesContract $translations)
    {
        $this->translations = $translations;
    }

    public function index()
    {
        $user = Auth::user()->load(['permissions','roles','extraFields']);
        $maxLevel = $user->maxLevel();

        return [
            'user' => $user,
            'userModel' => get_class($user),
            'currentLocale' => LaravelLocalization::getCurrentLocale(),
            'translations' => ['en' => array_dot(Lang::get('admin'))],
            'locales' => $this->translations->locales(),
            'Settings' => [
                'core' => Config::get('core'),
                'site' => [
                    'url' => url()->to('/')
                ]
            ],
            'ItemSelector' => [
                'connectors' => ItemConnector::connectors(),
            ],
            'ACL' => [
                'roles' => ($user) ? Role::with('permissions')->where('level','<=',$maxLevel)->get() : [],
                'permissions' => ($user) ? Permission::all() : [],
                'Gates' => GateKeeper::gates()->pluck('gate'),
                'maxLevel' => $maxLevel
            ],
        ];
    }
}