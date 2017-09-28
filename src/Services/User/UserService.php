<?php

namespace Mcms\Core\Services\User;

use Carbon\Carbon;
use Config;
use Hash;
use Mcms\Core\Mailables\NotifyAdminOnNewUser;
use Mcms\Core\Models\Role;
use Mcms\Core\QueryFilters\Filterable;
use Illuminate\Support\Collection;

/**
 * Class UserService
 * @package Mcms\Core\Services\User
 */
class UserService
{
    use Filterable;

    /**
     * @var $model
     */
    public $model;
    /**
     * @var User
     */
    protected $user;

    /**
     * UserService constructor.
     * @param User $user
     */
    public function __construct()
    {
        $userModel = Config::get('auth.providers.users.model');
        $this->user = new $userModel();
        $this->model = $this->user;
    }

    /**
     *
     * @param $filters
     */
    public function filter($filters, array $options = [])
    {
        $results = $this->user->filter($filters);

        $results = (array_key_exists('orderBy', $options)) ? $results->orderBy($options['orderBy']) : $results->orderBy('created_at', 'asc');
        /**
         * find all the roles that are a lower or same level as i am
         * to that, check which of my roles have a lower or equal level
         * compared to the rest
         */
        $roles = \Auth::user()->getRoles();
        $myMaxLevel = $roles->pluck('level')->max();
        $allRoles = Role::all();
        $allRolesMaxLevel = $allRoles->pluck('level')->max();

        //if my level is max, i am a super user
        $checkByRole = ($myMaxLevel >= $allRolesMaxLevel);

        //i am not a super user, so i need to figure out which roles i am looking for
        //so that we can filter out everything higher than mine #MindBlown

        $rolesToLookUp = [];
        if (!$checkByRole) {
            foreach ($allRoles as $role){
                if ($role->level <= $myMaxLevel){
                    $rolesToLookUp[] = $role->name;
                }
            }
        }

        if (isset($options['with'])){
            $results = $results->with($options['with']);
        }

        if (!$checkByRole) {
            $results = $results
                ->where(function ($subQuery) use ($rolesToLookUp) {
                    //nest these queries cause there might have been some direct
                    //filters applied earlier on from the query string
                    return $subQuery->whereNotIn('id', function ($q) {
                        //grab all users with NO roles, plain users basically
                        return $q->select('role_user.user_id')
                            ->from('role_user')
                            ->leftJoin('users', 'users.id', '=', 'role_user.user_id');
                    })
                        ->orWhereHas('roles', function ($q) use ($rolesToLookUp) {
                            //grab all users with the specific roles
                            return $q->whereIn('name', $rolesToLookUp);
                        });
                });
        }

        $limit = ($filters->request->has('limit')) ? $filters->request->input('limit') : 10;
        $results = $results->paginate($limit);

        if (isset($options['unhide'])){
            foreach ($results as $item) {
                $item->makeVisible($options['unhide']);
            }
        }

        return $results;
    }


    /**
     * Updates existing users
     *
     * @param $id
     * @param array $user
     * @return array
     */
    public function update($id, array $user)
    {
        $User = $this->user->find($id);
        if (isset($user['password'])){
            $user['password'] = Hash::make($user['password']);
        }

        $justApproved = false;

        //our user is waiting to be activated by the admin, was disabled and now is enabled.
        if ($User->awaits_moderation && ($user['active'] && ! $User->active)){
            $justApproved = true;
        }

        $User->update($user);

        if (array_key_exists('roles', $user)){
            $User->roles()->sync($this->extractFromUser($user['roles']));
        }

        if (array_key_exists('user_permissions', $user)){
            $User->userPermissions()->sync($this->extractFromUser($user['user_permissions']));
        }

        if (isset($user['extra_fields'])){
            $User->extraFieldValues()->sync($User->sortOutExtraFields($user['extra_fields']));
        }

        if ($justApproved) {
            $User->activated_at = Carbon::now();
            $User->save();
            //we need to send the approved email to the user
            event('user.email.send.approved', $User);
        }

        return $User;
    }

    /**
     * @param $arr
     * @param string $key
     * @return array
     */
    private function extractFromUser($arr, $key = 'id'){
        $collection =  new Collection($arr);
        return $collection->pluck($key)->toArray();
    }

    /**
     * @param array $user
     * @return User
     */
    public function store(array $user, $withActivation = false)
    {
        $user['password'] = Hash::make($user['password']);

        if ($withActivation){
            $user['active'] = true;
            $user['awaits_moderation'] = false;
            $user['activated_at'] = Carbon::now();
        }

        $user['confirmation_code'] = $this->generate_confirmation_token();

        $User = $this->user->create($user);

        if (isset($user['extra_fields'])){
            $User->extraFieldValues()->sync($User->sortOutExtraFields($user['extra_fields']));
        }

        if (array_key_exists('roles', $user)){
            $User->roles()->sync($this->extractFromUser($user['roles']));
        }

        if (array_key_exists('user_permissions', $user)){
            $User->userPermissions()->sync($this->extractFromUser($user['user_permissions']));
        }


        return $User;
    }

    public function generate_confirmation_token($length = 30)
    {
        return str_random($length);
    }

    /**
     * Delete a user
     *
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        return $this->user->find($id)->delete();
    }

    public function notifyAdminOnNewUser($user)
    {
        $message = (new NotifyAdminOnNewUser($user));
        $admin = new User([
            'email' => Config::get('mail.from.address'),
            'name' => Config::get('mail.from.name'),
        ]);

        //send mail
        Mail::to($admin)
            ->queue($message);
    }
}