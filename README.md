# DatabaseMask 

Mask data from Production using Faker to use safely elsewhere.

- [DatabaseMask on Packagist](https://packagist.org/packages/brendantwhite/databasemask)
- [DatabaseMask on GitHub](https://github.com/BrendanTWhite/DatabaseMask)

## Use Case

TODO: write use case

## Installation

`composer require brendantwhite/databasemask`

## Configuration

First, ensure you have a 
[Model Factory](https://laravel.com/docs/9.x/eloquent-factories) defined for each Eloquent model class that you want to mask.

Then, add a `$masked` property to your Eloquent model classes, specifying which attributes on your
model should be masked.

```php
    <?php

    // in app/Models/User.php

    /**
     * The attributes that should be masked by DatabaseMask.
     *
     * @var array
     */
    protected $masked = [
        'name',
        'email',
        'password',
    ];
```

If you have any models that you don't need to be masked at all,
add the `$masked` property anyway, but make it an empty array.

```php
    <?php

    // in app/Models/SomeOtherModel.php

    /**
     * The attributes that should be masked by DatabaseMask.
     *
     * @var array
     */
    protected $masked = [];
```

## Usage

To mask your data, use the `php artisan dbm:mask` command. This will loop through all your Eloquent models, looking for 
the `$masked` property.

- If the `$masked` property is set to the empty set, that model 
will be skipped.

- If the `$masked` property is missing on a model, that model will
be flagged with a warning.

- If the `$masked` property contains some field names, but no Factory has been created for that model, an error is returned.

But if the `$masked` property contains field names, and a Factory has been created, then the fields in the `$masked` property will be replaced with Faker values, while all other fields will be left untouched.

## Example

TODO: example goes here

## Disclaimer

I cannot, and do not, guarantee that using DatabaseMask will make your data 100% de-identified, even when configured correctly.

I can and will make a good-faith effort to ensure that, when configured correctly, DatabaseMask will remove personally 
idendifiable information from your data. 

However I cannot guarantee that this software is 100% bug-free, and I certainly can't guarantee that you have configured it
correctly. Therefore you must use this software at your own risk, or not use it at all.

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
