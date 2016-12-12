<?php

namespace Mcms\Core\Http\Controllers;


use Mcms\Core\Models\Filters\MailLogFilters;
use Mcms\Core\Models\MailLog;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;;

class MailLogController extends Controller
{
    protected $model;

    public function __construct(MailLog $model)
    {
        $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MailLogFilters $filters, Request $request)
    {
        $limit = ($request->has('limit')) ? (int) $request->input('limit') : 10;
        return $this->model->filter($filters)->paginate($limit);
    }

    public function show($id)
    {
        return view('core::maillog.show')->with('item', $this->model->find($id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response($this->model->store($request->toArray()));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = $this->model->update($id, $request->toArray());
        return response(['success' => $result]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->model->destroy($id);
        return response(['success' => $result]);
    }
}