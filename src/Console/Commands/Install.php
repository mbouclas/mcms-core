<?php

namespace Mcms\Core\Console\Commands;

/**
 * What i do :
 * 1. Publish my settings
 * 2. Publish my assets
 * 3. Publish my views
 * 5. Migrate my DB
 * 6. Seed my DB with defaults
 *
 * If you provide me with a provision file, i will make some changes to the config file of the application
 */

use Mcms\Core\Console\Commands\InstallerActions\ApplyProvisionSettings;
use Mcms\Core\Helpers\Composer;
use Mcms\Core\Helpers\ConfigFiles;
use Illuminate\Console\Command;
use Event;


/**
 * Class Install
 * @package Mcms\Core\Console\Commands
 */
class Install extends Command
{
    /**
     * @var array
     */
    protected $actions = [
        'files' => \Mcms\Core\Console\Commands\InstallerActions\FileOperations::class,
        'settings' => \Mcms\Core\Console\Commands\InstallerActions\PublishSettings::class,
        'assets' => \Mcms\Core\Console\Commands\InstallerActions\PublishAssets::class,
        'views' => \Mcms\Core\Console\Commands\InstallerActions\PublishViews::class,
        'migrate' => \Mcms\Core\Console\Commands\InstallerActions\MigrateDataBase::class,
        'dependencies' => \Mcms\Core\Console\Commands\InstallerActions\PublishDependencies::class,
        'seed' => \Mcms\Core\Console\Commands\InstallerActions\SeedDataBase::class,
        'editConfig' => \Mcms\Core\Console\Commands\InstallerActions\EditConfigFiles::class,
    ];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'core:install {provisionFile?} {--action=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs this module';

    protected $app;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(\Illuminate\Contracts\Foundation\Application $app)
    {
        parent::__construct();
        $this->app = $app;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Core package is starting the installation');
        $this->line('--------------');

        //load composer
        $composer = new Composer();
        $command = "php artisan core:refreshAssets";

        if ( ! in_array($command, $composer->contents['scripts']['post-update-cmd'])){
            $this->comment("adding command into composer");
            $composer->contents['scripts']['post-update-cmd'][] = $command;
            $composer->save();
            $this->info('composer updated');
        }

        $this->registerEvents();
        $this->info('Core package is starting the installation');
        $actions = array_keys($this->actions);

        //Run selective actions. Must be in the format --action="migrate seed assets"
        if ($this->option('action')){
            $actions = explode(" ", $this->option('action'));
        }

        /**
         * Run all actions
         */
        foreach ($actions as $action){
            (new $this->actions[$action]())->handle($this, $this->app);
        }


        if ($this->argument('provisionFile')){
            (new ApplyProvisionSettings())->handle($this,$this->argument('provisionFile'));
        }
        //set the site name
        $siteName = $this->ask('What should the site be called?');
        $config = new ConfigFiles('core');
        $config->contents['siteName'] = $siteName ?: 'My cool site';
        $config->save();

        $this->info('Core, All Done');
        $this->line('--------------');
        $this->line('');

        return true;
    }

    /**
     *
     */
    private function registerEvents()
    {
        Event::listen('installer.admin.run.before', function ($msg, $type = 'comment'){
            $this->{$type}($msg);
        });

        Event::listen('installer.admin.run.after', function ($msg, $type = 'comment'){
            $this->{$type}($msg);
        });
    }
}
