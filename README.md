# Symfony Fake 404 Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/symfony-fake-404-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-fake-404-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/symfony-fake-404-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-fake-404-bundle)
[![License](https://img.shields.io/github/license/tourze/symfony-fake-404-bundle.svg?style=flat-square)](LICENSE)

A Symfony bundle that displays random custom 404 error pages instead of the default Symfony error page. This bundle helps obfuscate your application's technology stack by displaying error pages that mimic other web servers like Nginx, IIS, Apache Tomcat, and CodeIgniter.

## Features

- **Automatic 404 Error Handling**: Seamlessly captures all 404 (Not Found) errors
- **Random Error Page Display**: Randomly selects and displays one of the available custom 404 pages
- **Pre-designed Templates**: Includes multiple error page templates mimicking popular web servers:
  - Nginx style 404 page
  - IIS style 404 page
  - IIS6 style 404 page
  - Apache Tomcat style 404 page
  - CodeIgniter style 404 page
- **Easy Customization**: Simple to add new custom error pages
- **Twig Template Support**: Fully customizable through Twig templates
- **Zero Configuration**: Works out of the box with no additional configuration required
- **Security Enhancement**: Helps obfuscate your application's underlying technology stack

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

## Quick Start

Once installed, the bundle automatically captures all 404 errors and displays a random error page template. No additional configuration is required.

### Basic Usage

```php
<?php
// When a 404 error occurs, the bundle will automatically:
// 1. Capture the NotFoundHttpException
// 2. Randomly select one of the available error page templates
// 3. Return a custom 404 response that looks like it came from another server

// Example: Accessing a non-existent route
// GET /non-existent-page
// -> Returns a random error page (Nginx, IIS, Tomcat, etc.)
```

### How It Works

1. **Event Subscriber**: The `NotFoundExceptionSubscriber` listens for `KernelEvents::EXCEPTION` events
2. **Template Selection**: The `Fake404Service` randomly selects from available templates in the `Resources/views/pages/` directory
3. **Response Generation**: A custom 404 response is generated using the selected template
4. **Random Display**: Each 404 error shows a different server-style error page, making it harder to identify the underlying technology

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

- **nginx.html.twig** - Nginx style 404 page
- **iis.html.twig** - IIS style 404 page  
- **iis6.html.twig** - IIS6 style 404 page
- **tomcat.html.twig** - Apache Tomcat style 404 page
- **ci2.html.twig** - CodeIgniter style 404 page

### Template Structure

Each template follows this structure:

```html
<html>
<head><title>404 Not Found</title></head>
<body>
<center><h1>404 Not Found</h1></center>
<hr><center>nginx</center>
</body>
</html>
```

### Configuration

The bundle uses the following service configuration:

```yaml
services:
    Tourze\Fake404Bundle\Service\Fake404Service:
        arguments:
            $templatesDir: '%kernel.project_dir%/packages/symfony-fake-404-bundle/src/Resources/views/pages'
```

## API Reference

### Fake404Service

The main service class that handles random error page generation.

#### Methods

- `getRandomErrorPage(): ?Response` - Returns a random 404 error page response

### NotFoundExceptionSubscriber

Event subscriber that captures 404 errors and replaces them with custom error pages.

#### Methods

- `onKernelException(ExceptionEvent $event): void` - Handles kernel exceptions and replaces 404 errors
- `getSubscribedEvents(): array` - Returns subscribed kernel events

## Security Considerations

This bundle helps with security through obscurity by:

- Hiding the fact that your application is built with Symfony
- Making it appear as if your site is running on different web servers
- Reducing information leakage about your technology stack

**Note**: This should be used as part of a comprehensive security strategy, not as the sole security measure.

## Performance Impact

- Minimal performance overhead as the bundle only activates on 404 errors
- Template loading is done once during service instantiation
- Random template selection uses PHP's efficient `array_rand()` function

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
