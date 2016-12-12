<?php

namespace Mcms\Core\Console\Commands;


use Mcms\Core\Exceptions\ErrorDuringUpdateException;
use Mcms\Core\Installer\ActionsAfterUpdate;
use Illuminate\Console\Command;

class RefreshAssets extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'core:refreshAssets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes all old assets and copies the new ones';

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
        $this->info('Removing old assets');

        try {
            (new ActionsAfterUpdate())->handle($this);
        } catch (ErrorDuringUpdateException $e) {

        }


        return true;
    }
}
