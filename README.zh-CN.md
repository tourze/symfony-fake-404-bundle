# Symfony Fake 404 Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/symfony-fake-404-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-fake-404-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/symfony-fake-404-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-fake-404-bundle)
[![License](https://img.shields.io/github/license/tourze/symfony-fake-404-bundle.svg?style=flat-square)](LICENSE)

一个为Symfony框架提供随机自定义404错误页面的Bundle，用于替代默认的Symfony错误页面。该Bundle通过显示模仿其他Web服务器（如Nginx、IIS、Apache Tomcat和CodeIgniter）的错误页面来帮助混淆您的应用程序技术栈。

## 功能特性

- **自动404错误处理**：无缝捕获所有404（页面未找到）错误
- **随机错误页面显示**：随机选择并显示一个可用的自定义404页面
- **预设模板**：包含多个模仿流行Web服务器的错误页面模板：
  - Nginx风格404页面
  - IIS风格404页面
  - IIS6风格404页面
  - Apache Tomcat风格404页面
  - CodeIgniter风格404页面
- **轻松自定义**：简单添加新的自定义错误页面
- **Twig模板支持**：通过Twig模板完全可定制
- **零配置**：开箱即用，无需额外配置
- **安全增强**：帮助混淆您的应用程序底层技术栈

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

## 快速开始

安装完成后，Bundle会自动捕获所有404错误并显示随机错误页面模板。无需额外配置。

### 基本用法

```php
<?php
// 当发生404错误时，Bundle会自动：
// 1. 捕获NotFoundHttpException
// 2. 随机选择一个可用的错误页面模板
// 3. 返回一个看起来来自其他服务器的自定义404响应

// 示例：访问一个不存在的路由
// GET /non-existent-page
// -> 返回一个随机错误页面（Nginx、IIS、Tomcat等）
```

### 工作原理

1. **事件订阅器**：`NotFoundExceptionSubscriber`监听`KernelEvents::EXCEPTION`事件
2. **模板选择**：`Fake404Service`从`Resources/views/pages/`目录中随机选择可用模板
3. **响应生成**：使用选定的模板生成自定义404响应
4. **随机显示**：每个404错误都显示不同的服务器风格错误页面，使识别底层技术变得更困难

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

- **nginx.html.twig** - Nginx风格404页面
- **iis.html.twig** - IIS风格404页面
- **iis6.html.twig** - IIS6风格404页面
- **tomcat.html.twig** - Apache Tomcat风格404页面
- **ci2.html.twig** - CodeIgniter风格404页面

### 模板结构

每个模板都遵循以下结构：

```html
<html>
<head><title>404 Not Found</title></head>
<body>
<center><h1>404 Not Found</h1></center>
<hr><center>nginx</center>
</body>
</html>
```

### 配置

Bundle使用以下服务配置：

```yaml
services:
    Tourze\Fake404Bundle\Service\Fake404Service:
        arguments:
            $templatesDir: '%kernel.project_dir%/packages/symfony-fake-404-bundle/src/Resources/views/pages'
```

## API参考

### Fake404Service

处理随机错误页面生成的主要服务类。

#### 方法

- `getRandomErrorPage(): ?Response` - 返回随机404错误页面响应

### NotFoundExceptionSubscriber

捕获404错误并用自定义错误页面替换它们的事件订阅器。

#### 方法

- `onKernelException(ExceptionEvent $event): void` - 处理内核异常并替换404错误
- `getSubscribedEvents(): array` - 返回订阅的内核事件

## 安全考虑

该Bundle通过以下方式帮助提高安全性：

- 隐藏您的应用程序是用Symfony构建的事实
- 使您的网站看起来像在不同的Web服务器上运行
- 减少关于您的技术栈的信息泄露

**注意**：这应该作为综合安全策略的一部分使用，而不是唯一的安全措施。

## 性能影响

- 最小的性能开销，因为Bundle只在404错误时激活
- 模板加载在服务实例化时完成一次
- 随机模板选择使用PHP高效的`array_rand()`函数

## 贡献指南

详情请参阅[CONTRIBUTING.md](CONTRIBUTING.md)。

## 版权和许可

MIT许可证。详情请参阅[许可证文件](LICENSE)。
