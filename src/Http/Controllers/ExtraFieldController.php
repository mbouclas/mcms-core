<?php

namespace Mcms\Core\Http\Controllers;


use Mcms\Core\ExtraFields\ExtraFields;
use Mcms\Core\Models\Filters\ExtraFieldFilters;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ExtraFieldController extends Controller
{
    protected $extraFieldService;

    public function __construct()
    {
        $this->extraFieldService = new ExtraFields();
    }

    public function index(ExtraFieldFilters $filters)
    {
/*        \DB::listen(function ($query) {
            print_r($query->sql);
            print_r($query->bindings);
            // $query->time
        });*/
        $fields = [];
        if ($filters->request->has('model')){
            $m = str_replace('\\\\','\\', $filters->request->input('model'));
            $model = new $m;
            if (property_exists($model, 'config') && isset($model->config['extraFields'])){
                $fields = $model->config['extraFields'];
            }
        }

        return [
            'fields' => $this->extraFieldService->model->filter($filters)->get(),
            'customizations' => $fields
        ];
    }

    public function show($id)
    {
        return $this->extraFieldService->model->find($id);
    }


    public function store(Request $request)
    {
        return $this->extraFieldService->store($request->all());
    }

    public function update(Request $request, $id)
    {
        return $this->extraFieldService->update($id, $request->all());
    }

    public function destroy($id)
    {
        $result = $this->extraFieldService->destroy($id);
        return response(['success' => $result]);
    }
}