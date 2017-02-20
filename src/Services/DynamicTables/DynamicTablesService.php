<?php

namespace Mcms\Core\Services\DynamicTables;


use App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Mcms\Core\Models\DynamicTable;

class DynamicTablesService
{
    public $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function update($id, array $item)
    {
        $Item = $this->model->find($id);

        $Item->update($item);

        return $Item;
    }

    /**
     * @param array $item
     * @param null $parentId
     * @return DynamicTable
     */
    public function store(array $item, $parentId = null)
    {
        $item['slug'] = $this->setSlug($item);
        $item['model'] = $this->model->itemModel;
        $item['user_id'] = ( ! isset($item['user_id']) || is_null($item['user_id'])) ? \Auth::user()->id : $item['user_id'];

        $newNode = new $this->model($item);
        //check for parent. If no parent given, this is a root item
        if ( ! $parentId){
            $newNode->table_id = 0;//set it until we have an id
            $newNode->save();
            $newNode->table_id = $newNode->id;
            $newNode->save();
            return $newNode;
        }

        //find the parent
        $parent = $this->model->find($parentId);
        $table = $parent->ancestors()->get();
        $newNode->table_id = (count($table) > 0) ? $table[0]->id : $parentId;
        /*
         * as this is a scoped model, we always need the table_id. This is the reason why we update it
         * before saving
         */

        $newNode->appendToNode($parent)->save();
//        $parent->appendNode($newNode);

        return $newNode;
    }

    public function destroy($id)
    {
        $item = $this->model->find($id);
        return $item->delete();
    }

    /**
     * @return DynamicTable
     */
    public function getTables()
    {
        return $this->model
            ->where('model', $this->model->itemModel)
            ->where('parent_id', null)
            ->orderBy('_lft', 'ASC')
            ->get();
    }

    /**
     * @param $tableId
     * @return \Kalnoy\Nestedset\Collection
     */
    public function getTableItems($tableId)
    {
        $table = $this->model->find($tableId);
        return $table
            ->descendants()
            ->defaultOrder()
            ->get()
            ->toTree();
    }

    /**
     * Grab all tables including all table items
     *
     * @return DynamicTable
     */
    public function all()
    {
        $tables = $this->getTables();
        foreach ($tables as $index => $table) {
            $tmp = $table;
            $items = $this->htmlTree($table->id);
            $tmp->children = (count($items) > 0) ? $items : new Collection();
            $tables[$index] = $tmp;
        }

        return $tables;
    }

    public function sync(array $items)
    {
        $ret = [];
        foreach ($items as $item) {
            $ret[$item['id']] = ['model' => $this->model->itemModel];
        }

        return $ret;
    }

    private function setSlug($item){
        if ( ! isset($item['slug']) || ! $item['slug']){
            return str_slug($item['title'][App::getLocale()]);
        }

        return $item['slug'];
    }

    public function htmlTree($tableId)
    {
        $leafs = new Collection();
        $table = $this->model->find($tableId);
        $results = $table
            ->descendants()
            ->defaultOrder()
            ->get()
            ->toTree();

        $traverse = function ($categories, $prefix = '-') use (&$traverse, $leafs) {
            foreach ($categories as $category) {
                $space = '';
                for ($i = 0; strlen($prefix) > $i; $i++) {
                    $space .= '&nbsp;&nbsp;';
                }

                $leafs->push([
                    'id' => $category->id,
                    'label' => $space . ' ' . $prefix . ' ' . $category->title,
                    'title' => $category->title
                ]);

                $traverse($category->children, $prefix . '-');
            }

            return $leafs;
        };

        $tree = $traverse($results);

        return $tree;
    }
}