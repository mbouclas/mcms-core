<?php

namespace Mcms\Core\Services\User;


use Mcms\Core\Models\Permission;

class PermissionService
{

    protected $permission;

    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }
    
    public function all()
    {
        return $this->permission->all();
    }

    /**
     * Update a permission in the DB
     *
     * @param $id
     * @param array $permission
     * @return mixed
     */
    public function update($id, array $permission)
    {
        return $this->permission->find($id)->update($permission);
    }

    /**
     * Create a new permission in the DB
     *
     * @param array $permission
     */
    public function store(array $permission)
    {
        return $this->permission->create($permission);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        return $this->permission->find($id)->delete();
    }
}