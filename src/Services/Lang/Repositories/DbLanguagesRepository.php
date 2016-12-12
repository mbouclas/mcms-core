<?php

namespace Mcms\Core\Services\Lang\Repositories;



use App;
use Artisan;
use Config;
use Mcms\Core\Helpers\ConfigFiles;
use Mcms\Core\Helpers\FileSystem;
use Mcms\Core\Models\Translation;
use Mcms\Core\Services\Lang\Contracts\LanguagesContract;
use Mcms\Core\Services\Lang\DeNormalizeOutput;
use Mcms\Core\Services\Lang\NormalizeInputToSave;
use Mcms\Core\Services\Lang\ValidateInput;
use Illuminate\Support\Collection;
use LaravelLocalization;

/**
 * Manage languages using a mix of DB for storing translations for easy management and php files
 * for keeping default laravel API
 *
 * Class DbLanguagesRepository
 * @package Mcms\Core\Services\Lang
 */
class DbLanguagesRepository implements LanguagesContract
{
    protected $translation;
    protected $fs;

    public function __construct(Translation $translation, FileSystem $fileSystem)
    {
        $this->translation = $translation;
        $this->fs = $fileSystem;
    }

    /**
     * Grab all available languages for one or more locales
     *
     * @param null|string|array $locale
     * @return array
     */
    public function all($locale = null)
    {
        // TODO: Implement all() method.
    }

    /**
     * Get all translations for a group
     *
     * @param string $group
     * @return mixed
     */
    public function get($group = null, $limit = 0)
    {

        return [];
    }

    /**
     * Deletes all the translations for this group
     *
     * @param $group
     * @return $this
     */
    public function deleteGroup($group)
    {
        // TODO: Implement deleteGroup() method.
    }

    /**
     * Creates a new translation variable
     *
     * @param string $key
     * @param string $value
     * @param string $group
     * @param string $locale
     * @param int $status
     * @return $this
     */
    public function create($key, $value, $group, $locale, $status = 1)
    {
        // TODO: Implement create() method.
    }

    /**
     * Update a single translation
     *
     * @param $key
     * @param $value
     * @param $locale
     * @return $this
     */
    public function update($key, $value, $locale)
    {
        // TODO: Implement update() method.
    }

    /**
     * Disable a single translation
     *
     * @param string $key
     * @return $this
     */
    public function disable($key)
    {
        // TODO: Implement disable() method.
    }

    /**
     * Enable a single translation
     *
     * @param $key
     * @return $this
     */
    public function enable($key)
    {
        // TODO: Implement enable() method.
    }

    /**
     * Set the application locales
     *
     * @param $locales
     * @return array
     */
    public function setLocales($locales)
    {
        // TODO: Implement setLocales() method.
    }

    /**
     * Get all available locales
     *
     * @return array
     */
    public function locales()
    {
        return LaravelLocalization::getSupportedLocales();
    }

    /**
     * @return array
     */
    public function groups()
    {
        return $this->translation->groupBy('group')->select('group')->get()->pluck('group');
    }

    /**
     * Filters the translations based on filters provided
     *
     * @param $filters
     */
    public function filter($filters, array $options = [])
    {
        $results = $this->translation->filter($filters);
        $results = (array_key_exists('orderBy',$options)) ? $results->orderBy($options['orderBy']) : $results->orderBy('key','asc');
        $results = (array_key_exists('select',$options)) ? $results->select($options['select']) : $results->select(['id','key']);
        $results = (array_key_exists('locale',$options)) ? $results->where('locale',$options['locale']) : $results->where('locale',App::getLocale());

        $results = $results->paginate();

        //The results contain only the default locale, so we need to go to the db and fetch all the rest as well
        //group the results per var -> language
        // {failed : {en : 'failed', el : 'failed-el'}}
        $groupedResults =  $this->groupResults($results);

        return [
            'data' => $groupedResults,
            'pagination' => [
                'count' => $results->count(),
                'currentPage' => $results->currentPage(),
                'firstItem' => $results->firstItem(),
                'hasMorePages' => $results->hasMorePages(),
                'lastItem' => $results->lastItem(),
                'lastPage' => $results->lastPage(),
                'nextPageUrl' => $results->nextPageUrl(),
                'perPage' => $results->perPage(),
                'previousPageUrl' => $results->previousPageUrl(),
                'total' => $results->total(),
            ]
        ];
    }

    /**
     * Group the results per variable
     *
     * @param $results
     */
    public function groupResults($results){
        $groupedResults = [];
        $ids = [];

        foreach ($results as $result){
            $ids[] = $result->key;
        }

        //Now fetch everything for these ids
        $found = $this->translation->whereIn('key',$ids)->get();


        foreach ($found as $result){
            $groupedResults[$result->key]['key'] = $result->key;
            $groupedResults[$result->key]['group'] = $result->group;
            $groupedResults[$result->key][$result->locale] = $result->toArray();
        }

        return $groupedResults;
    }

    public function createFromJson($json)
    {
        /**
         * 1. validate
         * 2. normalize
         * 3. save
         * 4. return de-normalized entries
         */


        /**
         * Throws exception if some check fails
         */
        (new ValidateInput($this->locales()))->handle($json);

        /**
         * Will take in the input and convert it to something our Model likes
         */
        $normalizedInput = (new NormalizeInputToSave($this->locales()))->handle($json);

        $newEntries = new Collection();

        foreach ($normalizedInput as $locale) {
            $newEntries->push($this->translation->create($locale));//add to DB
        }

        /**
         * Will take in the just created models and mess them up into something
         * more readable as a json
         */
        $deNormalizedOutput = (new DeNormalizeOutput())->handle($newEntries);

        return $deNormalizedOutput;
    }

    /**
     * Update a translation entry from a json string
     *
     * @param string $json
     * @return object Translation
     */
    public function saveFromJson($json)
    {
        /**
         * 1. validate
         * 2. normalize
         * 3. save
         * 4. return de-normalized entries
         */


        /**
         * Throws exception if some check fails
         */
        (new ValidateInput($this->locales()))->handle($json);

        /**
         * Will take in the input and convert it to something our Model likes
         */
        $normalizedInput = (new NormalizeInputToSave($this->locales()))->handle($json);

        $newEntries = new Collection();

        foreach ($normalizedInput as $locale) {
            //updating a row

            if ( ! array_key_exists('id', $locale)){
                $newEntries->push($this->translation->create($locale));//add to DB
            }
            else {
                $trans = $this->translation->find($locale['id']);
                $trans->update($locale);
                //refetch as we need an eloquent model to denormalize
                $newEntries->push($trans);//update DB
            }
        }

        /**
         * Will take in the just created models and mess them up into something
         * more readable as a json
         */
        $deNormalizedOutput = (new DeNormalizeOutput())->handle($newEntries);

        return $deNormalizedOutput;
    }

    /**
     * Deletes a translation
     *
     * @param $key
     * @return mixed
     */
    public function delete($key)
    {
        $this->translation->where('key',$key)->delete();

        return $this;
    }

    /**
     * Enables a locale. This is a tricky one as we have to look for the laravel
     * config file and remove a comment
     *
     * @param string $locale
     * @return mixed
     */
    public function enableLocale($localeCode)
    {
         //find locale in available locales
        $locales = Config::get('locales');
        $locale = $locales[$localeCode];
        $locale['code'] = $localeCode;
        $locale['default'] = false;
        $config = new ConfigFiles('laravellocalization');
        $config->contents['supportedLocales'][$localeCode] = $locale;
        $config->save();

        $config = new ConfigFiles('multilingual');
        $config->contents['locales'][] = $localeCode;
        $config->save();

        //now we need to enable all the translations with this code
        $this->translation->where('locale', $localeCode)->update(['status'=>true]);
        //create a folder for the new locale, include all files from default lang
        $localeDirectory = resource_path("lang/{$localeCode}");
        $defaultLocaleDirectory = resource_path("lang/".LaravelLocalization::getDefaultLocale());
        if ( ! $this->fs->fs->isDirectory($localeDirectory)){
            $this->fs->fs->copyDirectory($defaultLocaleDirectory, $localeDirectory);
        }
        //now we need to import this to the db
       Artisan::call('translations:import', []);

        return $this;
    }



    /**
     * Disables a locale
     *
     * @param string $locale
     * @return boolean
     */
    public function disableLocale($localeCode)
    {
        $config = new ConfigFiles('laravellocalization');
        unset($config->contents['supportedLocales'][$localeCode]);
        $config->save();

        $config = new ConfigFiles('multilingual');
        unset($config->contents['locales'][$localeCode]);
        $config->save();

        //now we need to disable all the translations with this code
        $this->translation->where('locale', $localeCode)->update(['status'=>false]);
        return $this;
    }

    /**
     * Updates the locale information
     *
     * @param $locale
     * @return $this
     */
    public function updateLocale($locale)
    {
        $config = new ConfigFiles('laravellocalization');
        $config->contents['supportedLocales'][$locale['code']] = $locale;
        $config->save();
        return $this;
    }

    /**
     * Set the default locale for the application
     *
     * @param $code
     * @return $this
     */
    public function setDefaultLocale($code)
    {
        $config = new ConfigFiles('laravellocalization');
        $allLocales = $config->contents['supportedLocales'];
        //reset all locales
        foreach ($allLocales as $index => $locale){
            $allLocales[$index]['default'] = false;
            if ($index == $code){
                $allLocales[$index]['default'] = true;
            }
        }
        $config->contents['supportedLocales'] = $allLocales;

        $config->save();
        //now app.php
        $config = new ConfigFiles('app', true);
        $config->addChange('locale', $code);
        $config->save();
        return $this;
    }
}