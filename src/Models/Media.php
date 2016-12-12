<?php

namespace Mcms\Core\Models;
use Spatie\MediaLibrary\Media as BaseMedia;

class Media extends BaseMedia
{
    protected $fillable = ['orderBy', 'active', 'settings', 'user_id'];
}