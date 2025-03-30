# Symfony Fake 404 Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/symfony-fake-404-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-fake-404-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/symfony-fake-404-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-fake-404-bundle)
[![License](https://img.shields.io/github/license/tourze/symfony-fake-404-bundle.svg?style=flat-square)](LICENSE)

一个为Symfony框架提供随机自定义404错误页面的Bundle，用于替代默认的Symfony错误页面。

## 功能特性

- 自动捕获404（页面未找到）错误
- 随机显示自定义404页面
- 内置多种预设的错误页面模板（Nginx、IIS、Tomcat等）
- 轻松添加新的自定义错误页面
- 通过Twig模板完全可定制
- 零配置即可使用

## 安装

### 系统要求

- PHP 8.1或更高版本
- Symfony 6.4或更高版本
- Symfony Twig Bundle

### 通过Composer安装

```bash
composer require tourze/symfony-fake-404-bundle
```

### 注册Bundle

在`config/bundles.php`中启用Bundle：

```php
return [
    // ...
    Tourze\Fake404Bundle\Fake404Bundle::class => ['all' => true],
];
```

### 安装资源

```bash
php bin/console assets:install
```

## 快速开始

安装完成后，Bundle会自动捕获所有404错误并显示随机错误页面模板。无需额外配置。

## 使用方法

### 添加自定义404页面

1. 在`templates/bundles/Fake404Bundle/pages/`创建新的Twig模板
2. 为模板命名（例如：`my_custom_error.html.twig`）
3. 扩展基础模板：

```twig
{% extends '@Fake404/layout.html.twig' %}

{% block content %}
    在此处添加自定义404内容
{% endblock %}
```

### 内置模板

该Bundle包含多个内置的404页面模板，模仿知名服务器的错误页面：

- Nginx风格404页面
- IIS风格404页面
- Tomcat风格404页面
- CodeIgniter风格404页面

## 贡献指南

详情请参阅[CONTRIBUTING.md](CONTRIBUTING.md)。

## 版权和许可

MIT许可证。详情请参阅[许可证文件](LICENSE)。
