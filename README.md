# Core

The Core package of the CMS

## Install

Via Composer

``` bash
$ composer require Mcms/Core
```

## Usage

``` php
$skeleton = new League\Skeleton();

```

## API

## Commands

## migrations

## Seeds

## Change log

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

## Query Filters
Apply query filters to any model by :
- Adding the Filterable trait to your model.
- Extending the QueryFilters abstract and adding your actual filters as methods
- Set your $filterable array containing just the filterable methods

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

# In case you get rejected in the private repos
```
git config --global user.email "mbouclas@gmail.com"
git config --global user.name "Michael Bouclas"
eval `ssh-agent -s`
ssh-add
```

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email mbouclas@gmail.com instead of using the issue tracker.

## Credits

- Roles : [romanbican/roles](https://github.com/romanbican/roles)
- Multilingual [themsaid/laravel-multilingual](http://packalyst.com/packages/package/themsaid/laravel-multilingual)
## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

