# Core

The Core package of the CMS

## Install
To get the system up and running follow the steps bellow
* Install a fresh copy of laravel like so `laravel new my-project` or via composer `composer create-project --prefer-dist laravel/laravel my-project`
* Edit the composer.json file and add the following packages
    ```
    "mcms\/core-package": "0.*",
    "mcms\/package-admin": "0.*",
    "mcms\/package-pages": "0.*",
    "mcms\/front-end": "0.*"
    ```
* Run `composer update` in order to download the packages
* Edit your `config/app.php` and add the following service providers
     ```
      Mcms\Core\CoreServiceProvider::class,
      Mcms\Admin\AdminServiceProvider::class,
      Mcms\FrontEnd\FrontEndServiceProvider::class,
      Mcms\Pages\PagesServiceProvider::class,
     ```
* Edit your .env file and add your database information
* Run `php artisan core:install-packages` to run the interactive command line installer
and just fill in the required fields

## Admin interface
After you have completed the installation process, you can visit the admin interface
on `/admin` and login with the email/password you provided during the installation.



## Installer
``` console
php artisan core:install
```

OR

``` console
php artisan core:install provision.installer.json
```
### Provision scripts
``` json
{
  "packages" : [
    {
      "name" : "Core",
      "requiredInput" : {
        "name" : "a name"
      },
      "migrations" : [],
      "seeders" : [],
      "publish" : []
    },
    {
      "name" : "Admin",
      "requiredInput" : {
        "balls" : "To the wall"
      },
      "migrations" : [],
      "seeders" : [],
      "publish" : []
    }
  ]
}
```

# Table of contents
* [Settings Manager](https://github.com/mbouclas/mcms-core/blob/master/SettingsManager.md)
* [Images](https://github.com/mbouclas/mcms-core/blob/master/images.md)
* [Config files](https://github.com/mbouclas/mcms-core/blob/master/ConfigFiles.md)
* [Private packages](https://github.com/mbouclas/mcms-core/blob/master/satis.md)

# Other modules
* [CMS](https://github.com/mbouclas/mcms-pages) A very opinionated CMS
* [Frontend](https://github.com/mbouclas/mcms-frontEnd) The front end of any website
* [Admin interface](https://github.com/mbouclas/mcms-admin) The admin interface

