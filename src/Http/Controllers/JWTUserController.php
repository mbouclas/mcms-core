<?php

namespace Mcms\Core\Http\Controllers;


use Hash;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use JWTAuth;
use Mcms\Core\Http\Controllers\Api\Boot;
use Mcms\Core\Models\User;
use Mcms\Core\Services\User\UserService;
use function MongoDB\BSON\toJSON;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class JWTUserController extends Controller
{
    protected $user;
    protected $boot;

    public function __construct(UserService $userService, Boot $boot)
    {
        $this->user = $userService;
        $this->boot = $boot;
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $bootData = $this->boot->index();
        $bootData['token'] = $token;
        return $bootData;
    }

    public function register(Request $request)
    {
        $user = $this->user->store($request->toArray(), true);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }

    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'));
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(true);
    }

    public function refreshToken()
    {
        return JWTAuth::refresh(JWTAuth::getToken());
    }

    public function checkEmailNotTaken(Request $request)
    {
        $found = $this->user->model->where('email', $request->input('email'))->first();

        return response()->json(['taken' => !!$found]);
    }
}