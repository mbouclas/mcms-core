<?php

namespace Mcms\Core\UpdatesLog;


use Mcms\Core\Console\Commands\InstallerActions\MigrateDataBase;
use Mcms\Core\Models\UpdatesLog as Model;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class UpdatesLog
{
    protected $registry;
    protected $command;
    protected $module;
    protected $id;
    protected $actions;

    public function __construct(Command $command, $module, array $actions, $version = 1)
    {
        $this->module = $module;
        $this->command = $command;
        $this->actions = new Collection($actions);
        $this->id = md5($this->actions->toJson() . $version);

        return $this;
    }

    public function check()
    {
        //check if the table is there, otherwise we need to migrate it
        try {
            Model::first();
        } catch (\PDOException $e){
            if (strstr($e->getMessage(), 'SQLSTATE[42S02]')){
                //migrate
                (new MigrateDataBase())->handle($this->command);
            }

        }
        $this->registry = Model::where('module',$this->module)->where('operation_id', $this->id)->get();

        return $this;
    }

    public function registerActions()
    {
        $ret = new Collection();

        $actions = $this->actions->toArray();
        foreach ($actions as $action => $handler){
            $res = Model::create([
                'module' => $this->module,
                'operation_id' => $this->id,
                'operation' => $action,
                'handler' => $handler,
                'result' => false
            ]);

            $ret->push($res);
        }

        return $ret;
    }

    public function process(){
        $this->check();

        if ($this->registry && count($this->registry) > 0){
            $retries = [];
            foreach ($this->registry as $item) {
                if (!$item->result){
                    $q = $this->command->confirm("{$item->operation} failed. Retry? [y|N]");
                    if ($q){
                        $retries[] = $item;
                    }
                }
            }

            if (count($retries) > 0){
                //retry failed ones
                $this->execute($retries);
            }

            return;
        }

        //register the actions
        $this->registry = $this->registerActions();

        $this->execute($this->registry);

    }


    protected function execute($actions){
        foreach ($actions as $item) {
            (new $item->handler)->handle($this->command, $item);
        }
    }
}