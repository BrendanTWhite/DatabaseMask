# DatabaseMask 

Mask data from Production using Faker to use safely elsewhere.

- [DatabaseMask on Packagist](https://packagist.org/packages/brendantwhite/databasemask)
- [DatabaseMask on GitHub](https://github.com/BrendanTWhite/DatabaseMask)

## Use Case

Ever wanted to use a copy of your Production database in your test environments? 
But you can't, because it's a security risk?

Now, you can get a copy of the Production database and mask just the values that need masking 
- names, phone numbers, email addresses etc - while keeping your data otherwise intact. 

## Installation

`composer require brendantwhite/databasemask`

## Configuration

First, ensure you have a 
[Model Factory](https://laravel.com/docs/eloquent-factories) defined 
for each Eloquent model class that you want to mask.

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

- If the `$masked` property is null or the empty set, that model will be skipped.

- If the `$masked` property is missing, that model will be flagged with a warning.

- If the `$masked` property is not empty, but no Factory has been created for that model, an error is returned.

But if the `$masked` property contains field names, and a Factory has been created, then 
the fields in the `$masked` property will be replaced with Faker values, while all other fields will be left untouched. 

## Backup and Restore

This package also contains light wrappers around Spatie's excellent 
[laravel-db-snapshots](https://github.com/spatie/laravel-db-snapshots) package, to make backups and restores even easier.

To use these commands you will first need to install `laravel-db-snapshots` 
as per their [installation instructions](https://github.com/spatie/laravel-db-snapshots#installation).

Then, `php artisan dbm:backup` will create a backup of your database on your `snapshots` disk, 
and `php artisan dbm:restore`  will restore a backup.

Generally, you'll want to backup from your Production enviornment, and restore to some other environment, 
and then mask that data in the other environment.

You do not need to install `laravel-db-snapshots` if you only want to use the `dbm:mask` command. 

## Disclaimer

I cannot, and do not, guarantee that using DatabaseMask will make your data 100% de-identified.

I can and will make a good-faith effort to ensure that, when configured correctly, DatabaseMask will remove personally 
idendifiable information from your data. 

However I cannot guarantee that this software is 100% bug-free, and I certainly can't guarantee that you have configured it
correctly. Therefore you must use this software at your own risk, or not use it at all.

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
