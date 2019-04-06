# Teamwork PHP SDK - Laravel Framework

[![Latest Version on Packagist](https://img.shields.io/packagist/v/digitalequation/teamwork.svg?style=flat-square)](https://packagist.org/packages/digitalequation/teamwork)
[![Build Status](https://img.shields.io/travis/digitalequation/teamwork/master.svg?style=flat-square)](https://travis-ci.org/digitalequation/teamwork)
[![Quality Score](https://img.shields.io/scrutinizer/g/digitalequation/teamwork.svg?style=flat-square)](https://scrutinizer-ci.com/g/digitalequation/teamwork)
[![Total Downloads](https://img.shields.io/packagist/dt/digitalequation/teamwork.svg?style=flat-square)](https://packagist.org/packages/digitalequation/teamwork)
[![License](https://img.shields.io/packagist/l/digitalequation/teamwork.svg?style=flat-square)](https://github.com/digitalequation/teamwork/blob/master/LICENSE.md)

A PHP Laravel wrapper library for Teamwork Desk, Teamwork Help Docs and Teamwork Tickets API's.

## Installation

You can install the package via composer:
```bash
composer require digitalequation/teamwork
```

Run the package install command:
```bash
php artisan teamwork:install
```
This will publish and register the TeamworkServiceProvider and will also generate a config file `config/teamwork.php`.

If you edit the config file and want to restore the defaults run:
```bash
php artisan teamwork:publish
```

## Usage

``` php
// Comming soon...
```

### Testing
``` bash
composer test
```
This will also generate a coverage report that is accessible on the `build` directory, `coverage` and open the `index.html` file to see the results.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email robert@thebug.ro instead of using the issue tracker.

## Credits

- [Robert Cristian Chiribuc](https://github.com/chiribuc)
- [Marcel Mihai Bontaș](https://github.com/kirov117)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
