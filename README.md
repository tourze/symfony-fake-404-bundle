# Symfony Fake 404 Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/symfony-fake-404-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-fake-404-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/symfony-fake-404-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-fake-404-bundle)
[![License](https://img.shields.io/github/license/tourze/symfony-fake-404-bundle.svg?style=flat-square)](LICENSE)

A Symfony bundle that displays random custom 404 error pages instead of the default Symfony error page.

## Features

- Automatically captures 404 (Not Found) errors
- Randomly displays one of the custom 404 pages
- Includes multiple pre-designed error page templates (Nginx, IIS, Tomcat, etc.)
- Easy to add new custom error pages
- Fully customizable through Twig templates
- Zero configuration required

## Installation

### Requirements

- PHP 8.1 or higher
- Symfony 6.4 or higher
- Symfony Twig Bundle

### Via Composer

```bash
composer require tourze/symfony-fake-404-bundle
```

### Bundle Registration

Enable the bundle in `config/bundles.php`:

```php
return [
    // ...
    Tourze\Fake404Bundle\Fake404Bundle::class => ['all' => true],
];
```

### Install Assets

```bash
php bin/console assets:install
```

## Quick Start

Once installed, the bundle automatically captures all 404 errors and displays a random error page template. No additional configuration is required.

## Usage

### Adding Custom 404 Pages

1. Create a new Twig template in `templates/bundles/Fake404Bundle/pages/`
2. Name your template appropriately (e.g., `my_custom_error.html.twig`)
3. Extend the base template:

```twig
{% extends '@Fake404/layout.html.twig' %}

{% block content %}
    Your custom 404 content here
{% endblock %}
```

### Built-in Templates

The bundle includes several built-in 404 page templates that mimic well-known server error pages:

- Nginx style 404 page
- IIS style 404 page
- Tomcat style 404 page
- CodeIgniter style 404 page

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
