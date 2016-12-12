<?php

namespace Mcms\Core\Installer;


use Mcms\Core\Installer\AfterUpdate\AlterTables;
use Mcms\Core\Installer\AfterUpdate\CreateMissingTable;
use Mcms\Core\Installer\AfterUpdate\PublishMissingConfig;
use Mcms\Core\Installer\AfterUpdate\PublishMissingMigrations;
use Mcms\Core\Installer\AfterUpdate\PublishMissingViews;
use Mcms\Core\UpdatesLog\UpdatesLog;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class ActionsAfterUpdate
{
    protected $command;
    protected $version;

    public function __construct()
    {
        $this->module = 'package-core';
        $this->version = 11;
    }

    public function handle(Command $command)
    {
        /*
         * publish the missing migrations
         * publish the missing config
         * create the missing table media_library
         */
        $actions = [
            'PublishMissingMigrations' => PublishMissingMigrations::class,
            'PublishMissingConfig' => PublishMissingConfig::class,
            'CreateMissingTable' => CreateMissingTable::class,
            'AlterTables' => AlterTables::class,
            'PublishMissingViews' => PublishMissingViews::class,
        ];


        (new UpdatesLog($command, $this->module, $actions, $this->version))->process();
    }

}