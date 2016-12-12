<?php

namespace Mcms\Core\Console\Commands;

use Illuminate\Console\Command;


class TranslationsImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'core:translations:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import translations into the Database';

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
        $this->call('translations:import');

        return true;
    }
}
