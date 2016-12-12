<?php

namespace Mcms\Core\Services\SettingsManager;
use Mcms\Core\Helpers\ConfigFiles;


/**
 * Sets some of the basic site details
 *
 * Class SiteSettings
 * @package Mcms\Core\Services\SettingsManager
 */
class SiteSettings
{
    public function update(array $settings)
    {
        $this->updateCore(array_merge($settings['general'], $settings['images']));
        $this->updateMail($settings['mail']);
        $this->updateRedactor($settings['redactor']);
    }

    public function updateCore($settings)
    {
        $this->updateFile('core', $settings);
    }

    public function updateGeneral($settings)
    {
        $this->updateFile('core', $settings);
    }

    public function updateMail($settings)
    {
        $config = new ConfigFiles('mail', true);
        $config->addChange('from', $settings);

        $config->save();
    }

    public function updateRedactor($settings)
    {
        $this->updateFile('redactor',$settings);
    }

    public function updateImages($settings)
    {
        $this->updateFile('core',['images' => $settings]);
    }

    private function updateFile($file, $settings){

        $config = new ConfigFiles($file);

        foreach ($settings as $key => $setting) {
            $config->contents[$key] = $setting;
        }

        $config->save();
    }
}