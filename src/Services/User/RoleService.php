<?php

namespace Mcms\Core\Services\User;

use Illuminate\Http\Request;
use Mcms\Core\Models\Role;

class RoleService
{

    protected $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }
    
    public function all()
    {
        return $this->role->all();
    }

    /**
     * Update a role in the DB
     *
     * @param $id
     * @param array $role
     * @return mixed
     */
    public function update($id, array $role)
    {
        $Role = $this->role->find($id);

        if (isset($role['permissions']) && is_array($role['permissions'])){
            $toAttach = [];
            foreach ($role['permissions'] as $permission) {
                $toAttach[] = $permission['id'];
            }

            $Role->permissions()->sync($toAttach);
        }

        return $Role->update($role);
    }

    /**
     * Create a new role in the DB
     *
     * @param array $role
     */
    public function store(array $role)
    {
        $Role = $this->role->create($role);
        if (isset($role['permissions']) && is_array($role['permissions'])){
            $toAttach = [];
            foreach ($role['permissions'] as $permission) {
                $toAttach[] = $permission['id'];
            }

            $Role->permissions()->sync($toAttach);
        }

        return $Role;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        return $this->role->find($id)->delete();
    }
}