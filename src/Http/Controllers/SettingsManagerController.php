<?php

namespace Mcms\Core\Http\Controllers;


use Config;
use Mcms\Core\Services\SettingsManager\SettingsManagerService;
use Mcms\Core\Services\SettingsManager\SiteSettings;
use Mcms\Core\SettingsManager\SettingsManager;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;;

class SettingsManagerController extends Controller
{
    protected $SettingsManagerService;

    public function __construct()
    {
        $this->SettingsManagerService = new SettingsManager();
    }

    public function index(Request $request)
    {
//        $mail = \Config::get('mail.')
        return [
            'core' => Config::get('core'),
            'mail' => Config::get('mail.from'),
            'redactor' => Config::get('redactor'),
            'seoFields' => Config::get('seo')
        ];
    }

    public function show($slug)
    {
        return $this->SettingsManagerService->model->where('slug', $slug)->first();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    echo 1;
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
        $this->SettingsManagerService->update($id, $request->all());
        return response($request->all());
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