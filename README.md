# jnjxp.vk
Voight-Kampff: An Expressive Authentication module.

[![Latest version][ico-version]][link-packagist]
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]

## Installation
```
composer require jnjxp/vk
```

## Config

### Add Routes
```php
// config/routes.php
//...
(new Jnjxp\Vk\ConfigProvider())->registerRoutes($app);

```

### Adapter
```php
// config/autoload/auth.global.php
// eg htaccess
use Mezzio\Authentication\UserRepository;
use Mezzio\Authentication\UserRepositoryInterface;

return [
    'dependencies' => [
        'aliases' => [
            UserRepositoryInterface::class => UserRepository\Htpasswd::class
        ]
    ],
    'authentication' => [
        'redirect' => '/login',
        'htpasswd' => dirname(__DIR__) . '/../htpasswd'
    ]
];
```

### Session persistence
```bash
# eg. session-ext
composer require mezzio/mezzio-session-ext
```

### Session middleware
```php
// config/pipeline.php
$app->pipe(\Mezzio\Session\SessionMiddleware::class);
$app->pipe(\Jnjxp\Vk\Middleware\ResumeAuthMiddleware::class);
```

## Usage
```php
// ...todo
```


[ico-version]: https://img.shields.io/packagist/v/jnjxp/vk.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/jnjxp/jnjxp.vk/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/jnjxp/jnjxp.vk.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/jnjxp/jnjxp.vk.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/jnjxp/vk
[link-travis]: https://travis-ci.org/jnjxp/jnjxp.vk
[link-scrutinizer]: https://scrutinizer-ci.com/g/jnjxp/jnjxp.vk
[link-code-quality]: https://scrutinizer-ci.com/g/jnjxp/jnjxp.vk
