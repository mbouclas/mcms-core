<?php

namespace Mcms\Core\Models;

use Mcms\Core\QueryFilters\Filterable;
use Illuminate\Database\Eloquent\Model;

class MailLog extends Model
{
    use Filterable;

    protected $table = 'mail_log';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public $casts = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'sended_at'];



}
