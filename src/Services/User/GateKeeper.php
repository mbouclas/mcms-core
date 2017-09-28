<?php

namespace Mcms\Core\Services\User;



use Mcms\Core\Exceptions\InvalidGateException;
use Mcms\Core\Models\GateKeeper as GateKeeperModel;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Validator;

class GateKeeper
{
    use ValidatesRequests;

    public $model;
    private static $instance;

    public function __construct()
    {
        $this->model = new GateKeeperModel();
    }

    public function get($level = null)
    {
        if ( ! $level){
            $level = ( ! \Auth::user()) ? 1 : \Auth::user()->maxLevel();
        }

        return $this->model->where('level', '<=', $level)->get();
    }

    public function add(array $gate)
    {
        $check = Validator::make($gate, [
            'gate' => 'required|unique:gate_keepers',
            'level' => 'required|integer',
        ]);

        if ($check->fails()){
            throw new InvalidGateException($check->errors());
        }

        return $this->model->create($gate);
    }

    public function remove($id)
    {
        return $this->model->destroy($id);
    }

    public static function addGate(array $gate)
    {
        return self::instance()->add($gate);
    }

    public static function addGates(array $gates)
    {
        foreach ($gates as $gate) {
            try {
                self::instance()->add($gate);
            }
            catch (InvalidGateException $e){
                //keep going
            }
        }

        return true;
    }

    public static function gates($level = null)
    {
        return self::instance()->get($level);
    }

    private static function instance(){
        if ( is_null( self::$instance ) )
        {
            self::$instance = new self();
        }

        return self::$instance;
    }
}