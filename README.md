# Laravel Inertia React File Management Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravelguru/laravel-filehandler.svg?style=flat-square)](https://packagist.org/packages/laravelguru/laravel-filehandler)
[![Total Downloads](https://img.shields.io/packagist/dt/laravelguru/laravel-filehandler.svg?style=flat-square)](https://packagist.org/packages/laravelguru/laravel-filehandler)
![GitHub Actions](https://github.com/InsafNilam/file-manager/actions/workflows/main.yml/badge.svg)

## Overview

The Laravel Inertia React File Management package provides seamless file management capabilities for your Laravel applications using React and Inertia.js. It includes prebuilt file input components and popup file dialogs for easy file uploads, storage, browsing, and management.

# Features

- Seamless integration with React and Inertia.js
- Utilizes ShadCN components for dialogs, buttons, scroll areas, and tabs
- Prebuilt file input components and popup file dialogs
- Smooth single-page application (SPA) transitions
- Comprehensive file operations: upload, download, delete, move files
- Responsive file browser
- Ideal for CMS, e-commerce platforms, project management tools, and personal portfolios

## Installation

You can install the package via composer:

```bash
composer require laravelguru/laravel-filehandler
```

# Register Service Provider

If you are using laravel 11 or update version you should add the service provider into bootstrap/providers.php:

```bash
<?php

return [
    // Other Service Providers
    LaravelGuru\LaravelFilehandler\ServiceProvider::class,
];
```

## Publishing Assets

Run the following commands to publish the package assets:

- This package is designed to be loaded only when the application is running in a web environment, ensuring it's not unnecessarily loaded during command-line operations. By checking if the app is running in the console using app()->runningInConsole();

```bash
    php artisan serve
    npm run dev
```

- The package optimizes performance by activating only when needed for web requests, keeping the application lightweight during CLI operations.

Then;

```bash
php artisan vendor:publish --provider="LaravelGuru\LaravelFilehandler\ServiceProvider"

or

php artisan vendor:publish --provider="LaravelGuru\LaravelFilehandler\ServiceProvider" --tag=config
php artisan vendor:publish --provider="LaravelGuru\LaravelFilehandler\ServiceProvider" --tag=migration
php artisan vendor:publish --provider="LaravelGuru\LaravelFilehandler\ServiceProvider" --tag=controller
php artisan vendor:publish --provider="LaravelGuru\LaravelFilehandler\ServiceProvider" --tag=resource
php artisan vendor:publish --provider="LaravelGuru\LaravelFilehandler\ServiceProvider" --tag=model
php artisan vendor:publish --provider="LaravelGuru\LaravelFilehandler\ServiceProvider" --tag=components
php artisan vendor:publish --provider="LaravelGuru\LaravelFilehandler\ServiceProvider" --tag=css
```

## Install ShadCn Components

Initialize the ShadCN components by following the [official installation guide](https://ui.shadcn.com/docs/installation/laravel). Use the commands below to add the required components:

```bash
npx shadcn-ui@latest add button
npx shadcn-ui@latest add dialog
npx shadcn-ui@latest add tabs
npx shadcn-ui@latest add scroll-area
```

## Setup

- Install Dependencies: Ensure you have React and Inertia.js set up in your Laravel project.
- Integrate Components: Use the provided React components and Inertia.js middleware in your application.
- Customize: Modify the components and handlers as needed to fit your requirements.
- Run Migrations: Apply the migrations to your database:

# Run Migration

The service provider will automatically generate a migration for the file_repos table when the application boots. Run the migration using:

```bash
php artisan migrate
```

## Usage

```php
// Usage description here
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email insafnilam.2000@gmail.com instead of using the issue tracker.

## Credits

- [Insaf Nilam](https://github.com/laravelguru)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
