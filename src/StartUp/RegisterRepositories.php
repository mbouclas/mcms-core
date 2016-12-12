<?php

namespace Mcms\Core\StartUp;

use Mcms\Core\Services\Lang\Contracts\LanguagesContract;
use Mcms\Core\Services\Lang\Repositories\DbLanguagesRepository;
use Illuminate\Support\ServiceProvider;
use App;

class RegisterRepositories
{
    public function handle(ServiceProvider $serviceProvider)
    {
        // LanguagesContract interface,  DbLanguagesRepository class
        App::bind(LanguagesContract::class, DbLanguagesRepository::class);
    }
}