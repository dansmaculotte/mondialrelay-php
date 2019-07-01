# Colissimo Web Sercices PHP SDK

This library aims to facilitate the usage of Mondial Relay Web Services

## Services

- [Delivery Choice](https://www.mondialrelay.fr/media/108937/Solution-Web-Service-V5.6.pdf)

## Installation

### Requirements

- PHP 7.3
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
    array(
        'site_id' => MONDIAL_RELAY_SITE_ID,
        'site_key' => MONDIAL_RELAY_SITE_KEY,
    )
);

$result = $delivery->findPickupPoints('Paris', '75001', 'FR', 4);

print_r($result);
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
