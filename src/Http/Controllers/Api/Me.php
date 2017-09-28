<?php

namespace Mcms\Core\Http\Controllers\Api;


use JWTAuth;

class Me
{
    public function index()
    {

        return new \Mcms\Core\Http\Resources\User($this->show());
    }

    public function show() {
        $user = JWTAuth::parseToken()->toUser(); // This does not present the user model properly. Hidden properties don't work

        return \Mcms\Core\Models\User::with(['roles','permissions'])->find($user->id);
    }
}