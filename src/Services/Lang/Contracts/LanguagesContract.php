<?php

namespace Mcms\Core\Services\Lang\Contracts;


/**
 * Language management
 *
 * Interface LanguagesContract
 * @package Mcms\Core\Services\Lang\Contracts
 */
interface LanguagesContract
{
    /**
     * Grab all available languages for one or more locales
     *
     * @param null|string|array $locale
     * @return array
     */
    public function all($locale = null);

    /**
     * Get all translations for a group
     *
     * @param string $group
     * @param int $limit
     * @return mixed
     */
    public function get($group, $limit = 0);

    /**
     * @return array
     */
    public function groups();

    /**
     * Deletes all the translations for this group
     *
     * @param $group
     * @return $this
     */
    public function deleteGroup($group);

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
    public function create($key, $value, $group, $locale, $status = 1);

    /**
     * Update a single translation
     * 
     * @param $key
     * @param $value
     * @param $locale
     * @return $this
     */
    public function update($key, $value, $locale);

    /**
     * Deletes a translation
     *
     * @param $key
     * @return mixed
     */
    public function delete($key);

    /**
     * Disable a single translation
     * 
     * @param string $key
     * @return $this
     */
    public function disable($key);

    /**
     * Enable a single translation
     * 
     * @param $key
     * @return $this
     */
    public function enable($key);

    /**
     * Set the application locales
     *
     * @param $locales
     * @return array
     */
    public function setLocales($locales);

    /**
     * Get all available locales
     *
     * @return array
     */
    public function locales();

    /**
     * Filters the translations based on filters provided
     *
     * @param $filters
     * @return object
     */
    public function filter($filters, array $options = []);

    /**
     * Group the results per variable
     *
     * @param $results
     * @return array
     */
    public function groupResults($results);

    /**
     * Create or update a translation entry from a json string
     *
     * @param string $json
     * @return object Translation
     */
    public function saveFromJson($json);

    /**
     * Enables a locale
     *
     * @param string $locale
     * @return mixed
     */
    public function enableLocale($locale);


    /**
     * Disables a locale
     *
     * @param string $locale
     * @return boolean
     */
    public function disableLocale($locale);

    /**
     * Updates the locale information
     *
     * @param $locale
     * @return mixed
     */
    public function updateLocale($locale);

    /**
     * Set the default locale
     *
     * @param $locale
     * @return mixed
     */
    public function setDefaultLocale($locale);

}