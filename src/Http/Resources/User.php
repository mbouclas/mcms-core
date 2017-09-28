<?php
namespace Mcms\Core\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Mcms\Core\Models\Permission;
use Mcms\Core\Models\Role;

class User extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
/*            'ACL' => [
                'roles' => Role::with('permissions')->where('level','<=',$this->maxLevel())->get(),
                'permissions' => Permission::all()
            ],*/
        ];
    }

}
