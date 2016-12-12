<?php

namespace Mcms\Core\Http\Controllers;


use Config;
use Mcms\Core\Services\Lang\Contracts\LanguagesContract;
use Illuminate\Http\Request;
use App;
use Illuminate\Routing\Controller;

class LocalesController extends Controller
{
    /**
     * @var LanguagesContract
     */
    protected $translations;

    public function __construct(LanguagesContract $translations)
    {
        $this->translations = $translations;
    }

    public function init()
    {
        return response()->json([
            'locales' => $this->translations->locales(),
            'localesAvailable' => Config::get('locales'),
            'defaultLang' => App::getLocale()
        ]);
    }

    public function get()
    {
        return $this->translations->locales();
    }

    public function enable(Request $request)
    {
        $this->translations->enableLocale($request->code);

        return response()->json(['success'=>true]);
    }

    public function disable(Request $request)
    {
        $this->translations->disableLocale($request->code);

        return response()->json(['success'=>true]);
    }

    public function update(Request $request)
    {
        $this->translations->updateLocale($request->data);

        return response()->json(['success'=>true]);
    }

    public function setDefault(Request $request)
    {

        $this->translations->setDefaultLocale($request->code);
        return response()->json(['success'=>$request->all()]);
    }
}