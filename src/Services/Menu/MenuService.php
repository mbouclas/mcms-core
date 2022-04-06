<?php

namespace Mcms\Core\Services\Menu;

use Mcms\Core\Helpers\Strings;
use Mcms\Core\Models\Menu;
use Mcms\Core\Models\MenuItem;
use Mcms\Core\Services\Lang\Contracts\LanguagesContract;
use \Illuminate\Support\Str;

/**
 * Handles menus
 *
 * Class MenuService
 * @package Mcms\Core\Services\Menu
 */
class MenuService
{
    /**
     * @var Menu
     */
    protected $menu;
    /**
     * @var MenuItem
     */
    protected $menuItem;
    /**
     * @var Strings
     */
    protected $stringHelpers;
    /**
     * @var Menu
     */
    public $menuModel;
    /**
     * @var MenuItem
     */
    public $menuItemModel;
    /**
     * @var LanguagesContract
     */
    protected $lang;

    /**
     * MenuService constructor.
     * @param Menu $menu
     * @param MenuItem $menuItem
     * @param Strings $strings
     * @param LanguagesContract $languagesContract
     */
    public function __construct(Menu $menu, MenuItem $menuItem,
                                Strings $strings, LanguagesContract $languagesContract)
    {
        $this->menu = $menu;
        $this->menuItem = $menuItem;
        $this->stringHelpers = $strings;
        $this->menuModel = $menu;
        $this->menuItemModel = $menuItem;
        $this->lang = $languagesContract;
    }

    /**
     * @param array $with
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all(array $with = [])
    {
        $menus = $this->menu->with($with)->get();
        foreach ($menus as $menu) {
            $menu->items->ToTree();
        }

        return $menus;
    }

    /**
     * @param array $menu
     * @return static
     */
    public function store(array $menu)
    {
        $menu['slug'] = Str::slug($menu['title'], '-');
        if ( ! isset($menu['settings']) OR ! is_array($menu['settings'])){
            $menu['settings'] = [];
        }

        return $this->menu->create($menu);
    }

    /**
     * Deletes a menu and its items
     *
     * @param $id
     * @return bool
     */
    public function destroy($id)
    {
        $this->menu->find($id)->delete();
        $this->menuItem->where('menu_id',$id)->delete();

        return true;
    }

    /**
     * Leave $parent empty to add a root node
     *
     * @param array $node
     * @param null $parent
     * @return mixed
     * @throws \Mcms\Core\Exceptions\InvalidMenuStructureException
     */
    public function addNode(array $node, $parent = null)
    {
        $validator = new ValidateMenuItem($node);
        //throws exception if somethings is wrong
        $validator->baseCheck();
        $node = (new PermalinkCreator($validator, $node, $this->lang))->handle();
        // Not a custom link
        if ($node['model']) {
            $modelClass = new $node['model'];
            $model = $modelClass->where('id', $node['item_id'])->first();
        }

        $newNode = new $this->menuItem($node);
        $newNode->settings = [
            "item" => $node['settings'],
            "node" => (isset($model)) ? $model->settings : null
        ];
        $newNode->save();

        if ( ! $parent){
            return $newNode;
        }

        if (is_numeric($parent)){
            //some wiseguy passed an id instead of an object
            $parent = $this->menuItemModel->findOrFail($parent);
        }

        // append it to the parent
        $parent->appendNode($newNode);

        return $newNode;
    }

    /**
     * Recreate title/permalink for the menu item
     *
     * @param object $model
     */
    public function sync($model, $mode = 'update'){
        $modelName = get_class($model);
        //find the class name
        $item = $this->menuItem->where([
            'item_id' => $model->id,
            'model' => $modelName
        ])->get()->first();

        if (!$item){
            return $this;
        }

        $this->{$mode . 'Item'}($item, $model);

        return $this;
    }

    private function destroyItem($item){
        $item->delete();
    }

    private function updateItem($item, $model){
        $modelName = get_class($model);
        //form a temporary node
        $node =[
            'model' => $modelName,
            'item_id' => $model->id,
            'slug_pattern' => (method_exists($model, 'getSlug')) ? $model->getSlug() : $item->slug_pattern,
            'titleField' => $item->settings['titleField'] ?? 'title',
            'settings' => [
                "item" => $item->settings,
                "node" => $model->settings
            ]
        ];

        $validator = new ValidateMenuItem($node);

        $newNode = (new PermalinkCreator($validator, $node, $this->lang))->handle();

        $item->update($newNode);
    }

    /**
     * return a menu with all the items
     *
     * @param integer $menuId
     * @return mixed
     */
    public function menuWithItems($menuId)
    {
        $menu = $this->menu->find($menuId);
        $menu->items = $this->menuItem
            ->scoped(['menu_id' => $menu->id])
            ->defaultOrder()
            ->get()
            ->ToTree();


        return $menu;
    }

    /**
     * Delete a single node. Optionally return the entire tree
     *
     * @param $id
     * @param bool $returnMenu
     * @return $this|mixed
     */
    public function destroyNode($id, $returnMenu = false)
    {
        $node = $this->menuItem->find($id);
        $menuId = $node->menu_id;
        $node->delete();

        if ( ! $returnMenu){
            return $this;
        }

        return $this->menuWithItems($menuId);

    }
}
