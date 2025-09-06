# Object Column Function

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Total Downloads][ico-downloads]][link-downloads]
<!-- [![Build Status][ico-travis]][link-travis] -->
<!-- [![Coverage Status][ico-scrutinizer]][link-scrutinizer] -->
<!-- [![Quality Score][ico-code-quality]][link-code-quality] -->

This package provides the **object_column()** function, which works similarly to the [array_column()][array-column]
function of the standard library, but having the ability to work with objects.

To search for columns in objects, both the public properties of these objects and the methods
getColumnName() / hasColumnName() / isColumnName() / columnName() (in that exact order),
as well as by the methods of the ArrayAccess interface.

Function **object_column()** is fully backward compatible with [array_column()][array-column] and can work with regular arrays the same way.

In addition, the **object_column()** function supports call chainings.

## Highlights

- Simple API
- Framework Agnostic
- Composer ready, [PSR-2][] and [PSR-4][] compliant

## System Requirements

You need:

- **PHP >= 8.1.0** but the latest stable version of PHP is recommended

## Install

Via Composer

``` bash
$ composer require cryonighter/object-column
```

## Usage

The function works as follows:

##### Example №1: simple access to public properties

``` php
use function Cryonighter\ObjectColumn\object_column;

$objects = [
    new class {
        public $foo = '123';
        public $bar = '456';
        public $baz = '789';
    },
    new class {
        public $foo = 'qwe';
        public $bar = 'asd';
        public $baz = 'zxc';
    },
];

$result = object_column($objects, 'foo', 'bar');
```

##### Example №2: chain of calls to getters

``` php
use function Cryonighter\ObjectColumn\object_column;

$objects = [
    new class {
        public function getFoo(): object {
            return new class {
                public function baz(): string {
                    return '123';
                }
            };
        }
        public function getBar(): object {
            return new class {
                public function buz(): string {
                    return '456';
                }
            };
        }
    },
    new class {
        public function getFoo(): object {
            return new class {
                public function baz(): string {
                    return 'qwe';
                }
            };
        }
        public function getBar(): object {
            return new class {
                public function buz(): string {
                    return 'asd';
                }
            };
        }
    },
];

$result = object_column($objects, 'foo.baz', 'bar.buz');
```

##### Example №3: chain of calls to ArrayAccess objects

``` php
use function Cryonighter\ObjectColumn\object_column;

$objects = [
    new ArrayObject([
        'foo' => new ArrayObject(['baz' => '123']),
        'bar' => new ArrayObject(['buz' => '456']),
    ]),
    new ArrayObject([
        'foo' => new ArrayObject(['baz' => 'qwe']),
        'bar' => new ArrayObject(['buz' => 'asd']),
    ]),
];

$result = object_column($objects, 'foo.baz', 'bar.buz');
```

##### Result

In all cases, the result will be the same

``` php
[456 => '123', 'asd' => 'qwe']
```

### Callable arguments

For more complex cases, you can pass your own handlers, which will be applied to all objects of the first nesting level.
You will have to implement the handle of the following nesting levels yourself in your callback functions.

##### Example №4: callable arguments

``` php
use function Cryonighter\ObjectColumn\object_column;

$objects = [
    new class {
        public function getFoo(): string {
            return '123';
        }
        public function getBar(): string {
            return '456';
        }
        public function getBaz(): string {
            return '789';
        }
    },
    new class {
        public function getFoo(): string {
            return 'qwe';
        }
        public function getBar(): string {
            return 'asd';
        }
        public function getBaz(): string {
            return 'zxc';
        }
    },
];

$result = object_column(
    $objects,
    fn(object $object): string => $object->getFoo() . '-' . $object->getBar(),
    fn(object $object): string => $object->getBar() . '-' . $object->getBaz(),
);
```

##### Result

``` php
[
    '456-789' => '123-456',
    'asd-zxc' => 'qwe-asd',
];
```

### Array indexing

Also, the function can be used to index an array, for this it is enough not to pass the first argument

##### Example №5: array indexing

``` php
use function Cryonighter\ObjectColumn\object_column;

$objects = [
    [
        'foo' => ['baz' => '123'],
        'bar' => ['buz' => '456'],
    ],
    [
        'foo' => ['baz' => 'qwe'],
        'bar' => ['buz' => 'asd'],
    ],
];

$result = object_column($objects, null, 'bar.buz');
// or
$result = object_column($objects, indexKey: 'bar.buz');
```

##### Result

``` php
[
    '456' => [
        'foo' => ['baz' => '123'],
        'bar' => ['buz' => '456'],
    ],
    'asd' => [
        'foo' => ['baz' => 'qwe'],
        'bar' => ['buz' => 'asd'],
    ],
];
```

For clarity, an example with a simple array is given, but it will work for all the cases listed above.

If no second or third argument is passed to the function, the original array will be returned.
This operation is meaningless.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ php vendor/phpunit/phpunit/phpunit tests
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email `cryonighter@yandex.ru` instead of using the issue tracker.

## Credits

- [Andrey Reshetchenko][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

[array-column]: https://www.php.net/manual/en/function.array-column.php

[PSR-2]: http://www.php-fig.org/psr/psr-2/
[PSR-4]: http://www.php-fig.org/psr/psr-4/

[ico-version]: https://img.shields.io/packagist/v/cryonighter/object-column.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/cryonighter/object-column/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/cryonighter/object-column.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/cryonighter/object-column.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/cryonighter/object-column.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/cryonighter/object-column
[link-travis]: https://travis-ci.org/cryonighter/object-column
[link-scrutinizer]: https://scrutinizer-ci.com/g/cryonighter/object-column/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/cryonighter/object-column
[link-downloads]: https://packagist.org/packages/cryonighter/object-column
[link-author]: https://github.com/cryonighter
[link-contributors]: ../../contributors
