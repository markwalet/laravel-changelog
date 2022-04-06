# Laravel Changelog

[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Latest Stable Version](https://poser.pugx.org/markwalet/laravel-changelog/v/stable)](https://packagist.org/packages/markwalet/laravel-changelog)
[![Build status](https://img.shields.io/github/workflow/status/markwalet/laravel-changelog/tests?style=flat-square&label=tests)](https://github.com/markwalet/laravel-changelog/actions)
[![Coverage](https://codecov.io/gh/markwalet/laravel-changelog/branch/master/graph/badge.svg)](https://codecov.io/gh/markwalet/laravel-changelog)
[![StyleCI](https://github.styleci.io/repos/202197691/shield?branch=master)](https://github.styleci.io/repos/202197691)
[![Total Downloads](https://poser.pugx.org/markwalet/laravel-changelog/downloads)](https://packagist.org/packages/markwalet/laravel-changelog)

A Laravel package that prevents merge conflicts on your changelog file.

It enables you to manage your changes by storing them in separate files based on the branch you are currently in. This makes sure that you are not working in the same file as other developers in your team.

## Installation
You can install this package with composer:

```shell
composer require markwalet/laravel-changelog
```

Laravel >=5.5 uses Package auto-discovery, so you don't have to register the service provider. If you want to register the service provider manually, add the following line to your `config/app.php` file:

```php
MarkWalet\Changelog\ChangelogServiceProvider::class
```

After installation, verify and change the config for your specific needs and run `php artisan changelog:install`. This creates a folder where all the changes will be stored in. This defaults to `base_path('.changes')`.

## Usage

The main functionality of this package consists of 5 commands:

- `php artisan changelog:add {--type=} {--message=}` (Add a change to the current feature entry)
- `php artisan changelog:list` (Show a list of changes for all versions)
- `php artisan changelog:unreleased` (Show a list of unreleased changes)
- `php artisan changelog:release` (Move all unreleased changes to a new version)
- `php artisan changelog:generate {--dry-run} {--path=}` (Generate a markdown file based on your changes. The path option can be empty)

## Configuration

The default configuration is defined in `changelog.php`. If you want to edit this file you can copy it to your config folder by using the following command:
```shell
php artisan vendor:publish --provider="MarkWalet\Changelog\ChangelogServiceProvider"
```

When you publish these vendor assets, you can also edit the default template that is used when generating the changelog. The template file can be found in `resources/views/vendor/changelog/changelog.blade.php`.
