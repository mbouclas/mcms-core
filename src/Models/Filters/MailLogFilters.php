<?php

namespace Mcms\Core\Models\Filters;


use Mcms\Core\QueryFilters\FilterableDate;
use Mcms\Core\QueryFilters\FilterableOrderBy;
use Mcms\Core\QueryFilters\QueryFilters;

/**
 * Class MailLogFilters
 * @package Mcms\Core\Models\Filters
 */
class MailLogFilters extends QueryFilters
{
    use FilterableDate, FilterableOrderBy;
    /**
     * @var array
     */
    protected $filterable = [
        'id',
        'to',
        'from',
        'subject',
        'body',
        'read',
        'dateStart',
        'dateEnd',
        'orderBy',
    ];

    public function id($id = null)
    {
        if ( ! isset($id)){
            return $this->builder;
        }


        if (! is_array($id)) {
            $id = $id = explode(',',$id);
        }

        return $this->builder->whereIn('id', $id);
    }

    public function to($to = null)
    {
        if ( ! $to){
            return $this->builder;
        }

        //In case ?to=active,inactive
        if (! is_array($to)) {
            $to = $to = explode(',',$to);
        }

        return $this->builder->whereIn('to', $to);
    }

    public function from($from = null)
    {
        if ( ! $from){
            return $this->builder;
        }

        //In case ?from=active,inactive
        if (! is_array($from)) {
            $from = $from = explode(',',$from);
        }

        return $this->builder->whereIn('from', $from);
    }

    /**
     * @param null|string $subject
     * @return $this
     */
    public function subject($subject = null)
    {
        if ( ! $subject){
            return $this->builder;
        }

        return $this->builder->where('subject', 'LIKE', "%{$subject}%");
    }

    /**
     * @param null|string $body
     * @return $this
     */
    public function body($body = null)
    {
        if ( ! $body){
            return $this->builder;
        }

        return $this->builder->where('body', 'LIKE', "%{$body}%");
    }

    public function read($read = null)
    {
        if ( ! isset($read)){
            return $this->builder;
        }

        //In case ?status=read,inread
        if (! is_array($read)) {
            $read = $read = explode(',',$read);
        }

        return $this->builder->whereIn('read', $read);
    }
}