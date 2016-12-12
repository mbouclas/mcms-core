<?php


namespace Mcms\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ItemConnector;

class ItemSelectorController extends Controller
{

    public function filter(Request $request)
    {
        $section = ItemConnector::findConnector([
            'name' => $request->input('connector')
        ], $request->input('section'))->section;

        $filter = new $section['filterService'];

        //instantiate the filter class and call the filter method
        return $filter->{$section['filterMethod']}($request, $section);
    }
}