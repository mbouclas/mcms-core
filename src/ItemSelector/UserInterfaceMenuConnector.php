<?php

namespace Mcms\Core\ItemSelector;

use Config;
use Mcms\Core\Models\Filters\UserFilters;
use Mcms\Core\Models\User;
use Mcms\Core\Services\Menu\AdminInterfaceConnector;
use Illuminate\Http\Request;


class UserInterfaceMenuConnector extends AdminInterfaceConnector
{
    /**
     * @var string
     */
    protected $moduleName = 'Users';
    /**
     * @var array
     */
    protected $sections = [];
    /**
     * @var User
     */
    protected $model;

    protected $filters;

    protected $type = 'generic';

    protected $order = 200;

    public function __construct()
    {
        $userModel = Config::get('auth.providers.users.model');
        $this->model = new $userModel();
        $this->sections = $this->getSections();

        parent::__construct($this->model);

        return $this;
    }

    /**
     * Setup the sections needed for the admin interface to render the menu selection
     *
     * @return array
     */
    private function getSections(){
        //extract it to a config file maybe
        return [
            [
                'name' => 'Items',
                'filterService' => 'Mcms\Core\ItemSelector\UserInterfaceMenuConnector',
                'filterMethod' => 'filterItems',
                'settings' => [
                    'preload' => true,
                    'filter' => true
                ],
                'filters' => [
                    ['key'=>'id', 'label'=> '#ID', 'default' => true],
                    ['key'=>'email', 'label'=> 'email'],
                    ['key'=>'name', 'label'=> 'Name'],
                ],
                'titleField' => 'title',
                'slug_pattern' => null
            ],
        ];
    }

    public function filterItems(Request $request, $section){
        $results = $this->model->filter(new UserFilters($request))->get();

        if (count($results) == 0){
            return ['data' => []];
        }

        //now formulate the results
        $toReturn = [];

        foreach ($results as $result){

            $toReturn[] = [
                'item_id' => $result->id,
                'title' => "{$result->firstName} {$result->lastName} ({$result->email})",
                'module' => $this->moduleName,
                'model' => get_class($result),
                'section' => $section
            ];
        }

        $results = $results->toArray();
        $results['data'] = $toReturn;


        return ['data' => $toReturn];
    }
}