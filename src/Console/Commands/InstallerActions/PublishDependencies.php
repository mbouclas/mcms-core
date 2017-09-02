<?php

namespace Mcms\Core\Console\Commands\InstallerActions;



use Mcms\Core\Helpers\FileSystem;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem as FS;
use LaravelLocalization;

class PublishDependencies
{
    public function handle(Command $command)
    {

        //Multilingual
        $command->call('vendor:publish', [
            '--provider' => 'Themsaid\Multilingual\MultilingualServiceProvider',
            '--tag' => ['config'],
        ]);

        //widgets
        $command->call('vendor:publish', [
            '--provider' => 'Arrilot\Widgets\ServiceProvider',
        ]);

        //translation manager
        $command->call('vendor:publish', [
            '--provider' => 'Barryvdh\TranslationManager\ManagerServiceProvider',
            '--tag' => ['migrations'],
        ]);

        $command->call('vendor:publish', [
            '--provider' => 'Barryvdh\TranslationManager\ManagerServiceProvider',
            '--tag' => ['config'],
        ]);

        //Tags
        $command->call('vendor:publish', [
            '--provider' => 'Conner\Tagging\Providers\TaggingServiceProvider',
        ]);

        $command->call('vendor:publish', [
            '--provider' => 'Conner\Likeable\LikeableServiceProvider',
            '--tag' => ['migrations'],
        ]);

        $command->call('vendor:publish', [
            '--provider' => 'ymon\JWTAuth\Providers\JWTAuthServiceProvider',
        ]);

        $command->call('jwt:generate', [
        ]);

        //make sure we create a copy of the lang folder for all languages
        $locales = LaravelLocalization::getSupportedLocales();
        $defaultLocaleDirectory = resource_path("lang/".LaravelLocalization::getDefaultLocale());
        $fs = new FileSystem(new FS());
        foreach ($locales as $code => $locale){
            if ( ! isset($locale['default']) || ! $locale['default']){
                //copy as it is not default
                $localeDirectory = resource_path("lang/{$code}");
                $fs->fs->copyDirectory($defaultLocaleDirectory, $localeDirectory);
            }
        }

        $command->call('migrate');

        $command->comment("* Dependencies published");
    }
}