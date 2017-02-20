<?php

namespace Mcms\Core\Http\Controllers;


use Config;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Mcms\Core\Models\DynamicTable;
use Mcms\Core\Services\DynamicTables\DynamicTablesService;

class DynamicTablesController extends Controller
{

    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $dtService = $this->dtServiceInstance($request->model);

        return response([
            'tables' => $dtService->getTables()
        ]);
    }

    public function show($id)
    {
        $item = DynamicTable::find($id);
        if ( ! $item) {
            $item = new Collection();
        }
        $ancestors = $item->ancestors()->get();
        $item->parent = (count($ancestors) > 0) ? $ancestors[0] : null;
        $item->seoFields = Config::get('seo');

        return response($item);
    }

    public function update(Request $request, $id)
    {
        $dtService = $this->dtServiceInstance($request->model);

        return $dtService->update($id, $request->all());
    }

    public function store(Request $request)
    {
        $dtService = $this->dtServiceInstance($request->model);

        $parentId = ($request->has('parent')) ? $request->parent['id'] : null;
        return $dtService->store($request->all(), $parentId);
    }

    public function destroy(Request $request, $id)
    {
        $dtService = $this->dtServiceInstance($request->model);
        $dtService->destroy($id);

        return $this->index($request);
    }

    public function getTableItems($id)
    {
        //find the table
        $table = DynamicTable::find($id);

        return response([
            'table' => $table,
            'items' => $table
                ->descendants()
                ->defaultOrder()
                ->get()
                ->toTree()
        ]);
    }

    public function rebuild($parentId, Request $request)
    {

        $parent = DynamicTable::find($parentId);
        $tree = $parent->toArray();
        $tree['children'] = $request->all();

        DynamicTable::scoped(['table_id' => $parentId])
            ->rebuildTree([$tree]);

        return $parent
            ->descendants()
            ->defaultOrder()
            ->get()
            ->toTree();
    }

    private function dtServiceInstance($modelName)
    {
        //get the moel from the query
        $model = str_replace('\\\\','\\', $modelName);
        $item = new $model;
        return new DynamicTablesService(new $item->dynamicTablesModel);
    }
}