# Nepali Date for PHP
# nepali-date (Get Current Nepali Date)


Requirements
------------

Using this library for PHP requires the following:

* [Composer] or a manual install of the dependencies mentioned in
  `composer.json`.


Installation
------------

The recommended way to install it PHP is to install it using

```sh
composer require snlbaral/nepali-date
```


Usages
----------

**Init**
```php
require 'vendor/autoload.php';
use Snlbaral\NepaliDate\NepaliDate;

echo new NepaliDate();
//सोमबार, भदौ २८, २o७८

echo new NepaliDate("l, M d");
//सोमबार, भदौ २८
```

**Parameters**
```php
/**
 * @param string $format Date Format (i.e. "Y-m-d" or "l, m") | Default = "l, M d, Y"
 * @param boolean $convert convert number/digits to nepali numbers (i.e. १) | Default = true
 * @return string Nepali Date.
*/
echo new NepaliDate("l, M d", false);
//सोमबार, भदौ 28
```php


**Available Options**
1. l => Day name
2. d => day (1-31)
3. m => month (1-12)
4. M => month name
5. Y => Year
