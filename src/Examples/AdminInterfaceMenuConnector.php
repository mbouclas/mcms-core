<?php

namespace Mcms\Core\Examples;


use Mcms\Core\Models\Filters\UserFilters;
use Mcms\Core\Models\User;
use Mcms\Core\Services\User\UserService;
use Illuminate\Http\Request;
use Mcms\Core\Services\Menu\AdminInterfaceConnector;


class AdminInterfaceMenuConnector extends AdminInterfaceConnector
{
    protected $moduleName = 'Users';
    protected $sections = [];
    protected $user;
    protected $filters;

    public function __construct()
    {
        $this->user = new UserService(new User());
        $this->sections = $this->getSections();

        parent::__construct($this->user->model);

        return $this;
    }

    private function getSections(){
        //extract it to a config file maybe
        return [
            [
                'name' => 'Items',
                'filterService' => 'Mcms\Core\Examples\AdminInterfaceMenuConnector',
                'filterMethod' => 'filterItems',
                'settings' => [
                    'perPage' => 10
                ],
                'filters' => [
                    ['key'=>'id', 'label'=> '#ID'],
                    ['key'=>'email', 'label'=> 'email', 'default' => true],
                    ['key'=>'firstName', 'label'=> 'Name'],
                    ['key'=>'lastName', 'label'=> 'Surname'],
                ],
                'titleField' => [
                    'pattern' => [
                        'en' => 'profile page for %firstName$s %lastName$s',
                        'el' => 'To profil tou xristi %firstName$s %lastName$s'
                    ]
                ],
                'slug_pattern' => '/users/%id$s/%email$s'
            ],
            [
                'name' => 'Categories',
                'model' => 'Mcms/Core/Models/User',
                'endPoint' => '/admin/api/user/menu/items',
                'filters' => [
                    ['key'=>'id', 'label'=> '#ID'],
                    ['key'=>'email', 'label'=> 'email', 'default' => true],

                ],
                'settings' => [
                    'perPage' => 30
                ]
            ]
        ];
    }

    public function filterItems(Request $request, $section){
        $results = $this->user->filter(new UserFilters($request));
        if (count($results) == 0){
            return $results;
        }

        //now formulate the results
        $toReturn = [];

        foreach ($results as $result){

            $toReturn[] = [
                'item_id' => $result->id,
                'title' => "{$result->firstName} {$result->lastName} <{$result->email}>",
                'module' => $this->moduleName,
                'model' => get_class($result),
                'section' => $section
            ];
        }

        $results = $results->toArray();
        $results['data'] = $toReturn;


        return $results;
    }
}