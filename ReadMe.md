# Mondial Relay Web Services PHP SDK

[![Latest Version](https://img.shields.io/packagist/v/DansMaCulotte/mondialrelay-php.svg?style=flat-square)](https://packagist.org/packages/dansmaculotte/mondialrelay-php)
[![Total Downloads](https://img.shields.io/packagist/dt/DansMaCulotte/mondialrelay-php.svg?style=flat-square)](https://packagist.org/packages/dansmaculotte/mondialrelay-php)
[![Build Status](https://img.shields.io/travis/DansMaCulotte/mondialrelay-php/master.svg?style=flat-square)](https://travis-ci.org/dansmaculotte/mondialrelay-php)
[![Quality Score](https://img.shields.io/scrutinizer/g/DansMaCulotte/mondialrelay-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/dansmaculotte/mondialrelay-php)
[![Code Coverage](https://img.shields.io/coveralls/github/DansMaCulotte/mondialrelay-php.svg?style=flat-square)](https://coveralls.io/github/dansmaculotte/mondialrelay-php)

> This library aims to facilitate the usage of Mondial Relay Web Services

## Services

- [Delivery Choice](https://api.mondialrelay.com/Web_Services.asmx?op=WSI4_PointRelais_Recherche)

## Installation

### Requirements

- PHP 7.2
- Soap Extension

You can install the package via composer:

``` bash
composer require dansmaculotte/mondialrelay-php
```

## Usage

[MondialRelay Documentation](https://www.mondialrelay.fr/media/108937/Solution-Web-Service-V5.6.pdf)

#### Find pickup points

```php
use DansMaCulotte\MondialRelay\DeliveryChoice;

$delivery = new DeliveryChoice(
    [
        'site_id' => MONDIAL_RELAY_SITE_ID,
        'site_key' => MONDIAL_RELAY_SITE_KEY,
    ]
);

$result = $delivery->findPickupPoints('FR', '75001', 'FR');

print_r($result);
```

#### Find pickup points by code

```php
use DansMaCulotte\MondialRelay\DeliveryChoice;

$delivery = new DeliveryChoice(
    [
        'site_id' => MONDIAL_RELAY_SITE_ID,
        'site_key' => MONDIAL_RELAY_SITE_KEY,
    ]
);

$result = $delivery->findPickupPointsByCode('FR', '062049');

print_r($result);
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
