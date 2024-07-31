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

## Publishing Assets

Run the following commands to publish the package assets:

```bash
php artisan vendor:publish --tag=config
php artisan vendor:publish --tag=migration
php artisan vendor:publish --tag=controller
php artisan vendor:publish --tag=resource
php artisan vendor:publish --tag=model
php artisan vendor:publish --tag=components
php artisan vendor:publish --tag=css
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
