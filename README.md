# php-google-shortener
[![Latest Version](https://img.shields.io/github/release/zenapply/php-google-shortener.svg?style=flat-square)](https://github.com/zenapply/php-google-shortener/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://travis-ci.org/zenapply/php-google-shortener.svg?branch=master)](https://travis-ci.org/zenapply/php-google-shortener)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/zenapply/php-google-shortener/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/zenapply/php-google-shortener/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/zenapply/php-google-shortener/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/zenapply/php-google-shortener/?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/56f3252c35630e0029db0187/badge.svg?style=flat)](https://www.versioneye.com/user/projects/56f3252c35630e0029db0187)
[![Total Downloads](https://img.shields.io/packagist/dt/zenapply/php-google-shortener.svg?style=flat-square)](https://packagist.org/packages/zenapply/php-google-shortener)

## Installation

Install via [composer](https://getcomposer.org/) - In the terminal:
```bash
composer require zenapply/php-google-shortener
```

## Usage
```php
use Zenapply\GoogleShortener\Google;
$c = new Google("username","password");
$result = $c->shorten("https://www.google.com/");
var_dump($result);
// string(21) "http://bit.ly/1SvUIo8"
```