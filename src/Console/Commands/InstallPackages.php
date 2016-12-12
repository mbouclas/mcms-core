<?php

namespace Mcms\Core\Console\Commands;

use Event;
use Illuminate\Console\Command;
use \Installer;


class InstallPackages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'core:install-packages {provisionScript?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs the CMS';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Beginning installation');
        Event::listen('installer.package.run.before', function ($msg, $type = 'comment'){
            $this->{$type}($msg);
        });

        Event::listen('installer.package.run.after', function ($msg, $type = 'comment'){
            $this->{$type}($msg);
        });

        $installers = Installer::get();
        /*        print_r($installers->toArray());
                return;*/
        $bar = $this->output->createProgressBar($installers->count());
        Installer::prepare($this)
            ->execute($this->readProvisionScript($this->argument('provisionScript')), $bar);

        $this->line('');
        $this->info('Build something great!!!');
    }

    /**
     * Read a provision script and parse it to get module parameters
     * @param string $provisionScript
     */
    private function readProvisionScript($provisionScript)
    {
        $args = [];
        if (!$provisionScript){
            return $args;
        }

        $data = \File::get($provisionScript);
        $data = json_decode($data, true);

        foreach ($data['packages'] as $package){
            $args[$package['name']] = $package;
        }

        return $args;
    }

}
