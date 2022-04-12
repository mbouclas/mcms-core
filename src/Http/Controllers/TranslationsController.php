<?php

namespace Mcms\Core\Http\Controllers;

use Mcms\Core\Models\Filters\TranslationFilters;
use Mcms\Core\Services\Lang\Contracts\LanguagesContract;
use Illuminate\Http\Request;
use LaravelLocalization, App, Artisan;
use Illuminate\Routing\Controller;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Responsible for all admin translations API calls
 *
 * Class TranslationsController
 * @package Mcms\Admin\Http\Controllers
 */
class TranslationsController extends Controller
{

    /**
     * @var LanguagesContract
     */
    protected $translations;

    public function __construct(LanguagesContract $translations)
    {
        $this->translations = $translations;
    }

    /**
     * Call this to get the initial data needed to start the translations module in the admin application
     * Should return all the groups, available locales, default lang and the first 20 translations
     */
    public function init(TranslationFilters $filters)
    {
        $translations = $this->translations->filter($filters);
        //grab all the groups
        return [
            'locales' => $this->translations->locales(),
            'groups' => $this->translations->groups(),
            'defaultLang' => App::getLocale(),
            'translations' => $translations
        ];
    }

    /**
     * Grab translations
     *
     * @param TranslationFilters $filters
     * @return object
     */
    public function get(TranslationFilters $filters)
    {
        return $this->translations->filter($filters);
    }


    /**
     * Create a new translation. Requires normalizing
     *
     * @param Request $request
     * @return string
     */
    public function create(Request $request)
    {

        $newEntry = $this->translations
            ->saveFromJson($request->data);

        return response()
            ->json($newEntry);
    }

    /**
     * Update a translation. Requires normalizing
     */
    public function update(Request $request)
    {

        $newEntry = $this->translations
            ->saveFromJson($request->data);

        return response()
            ->json($newEntry);
    }

    /**
     * Delete a translation
     */
    public function delete($id)
    {
        $this->translations->delete($id);
        return response()
            ->json(['success' => true]);
    }

    public function sync()
    {
        $outcome = [];

        $groups = $this->translations->groups();
        $outcome = $groups->map(function ($group) use ($outcome) {
            $process = new Process(['php', 'artisan', 'translations:export', $group]);
            $process->setWorkingDirectory(base_path());
            $process->run();

            if (!$process->isSuccessful()) {
                return $process->getOutput();
            }

            return $process->getOutput();
        });


        return response()
            ->json(['success' => true, 'out' => $outcome->all()]);
    }
}
