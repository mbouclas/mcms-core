<?php

namespace Mcms\Core\Presenters;
use Mcms\Core\Services\Presenter\Presenter;

/**
 * Works as $user->present()->methodName
 *
 * Class UserPresenter
 * @package Mcms\Core\Presenters
 */
class UserPresenter extends Presenter
{
    /**
     * @return string
     */
    public function fullName()
    {
        return $this->model->firstName . ' ' . $this->model->lastName;
    }

    /**
     * @return mixed
     */
    public function accountAge()
    {
        return $this->model->created_at->diffForHumans();
    }
}