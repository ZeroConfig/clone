# Introduction

The `zero-config/clone` package is meant to support developers creating deep
clones of objects. Contrary to most solutions out there, this package does
exactly what is says on the tin and requires no knowledge of the inner workings
or specific configuration in order to do what one expects it to do. 🐑

[![Bitbucket Pipelines](https://img.shields.io/bitbucket/pipelines/zeroconfig/clone.svg)](https://bitbucket.org/zeroconfig/clone/addon/pipelines/home)
[![codecov](https://codecov.io/bb/zeroconfig/clone/branch/master/graph/badge.svg?token=EXc34YM0zZ)](https://codecov.io/bb/zeroconfig/clone)
[![Packagist](https://img.shields.io/packagist/v/zero-config/clone.svg)](https://packagist.org/packages/zero-config/clone)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/zero-config/clone.svg)](https://secure.php.net/)
[![Packagist](https://img.shields.io/packagist/l/zero-config/clone.svg)](https://github.com/ZeroConfig/clone/blob/master/LICENSE)

# Installation

```
composer require --dev zero-config/clone
```

# Usage

To use the clone functionality, simply invoke the `deepClone` function.
Alternatively, the alias [`🐑`](https://www.utf8icons.com/character/128017/sheep)
can be used.

```php
<?php
/** @var object $original */
$copy = deepClone($original);
$copy = 🐑($original);
```

For an object oriented approach, one can use an instance of `Cloner`.

```php
<?php
use ZeroConfig\Cloner\Cloner;

/** @var object $original */
$cloner = new Cloner();
$copy   = $cloner($original);
```

# Rules of thumb

1. Objects are cloned recursively.
2. Properties of all visibilities (public, protected, private) are cloned.
3. Static properties are NOT cloned.
4. Singletons remain singletons.
5. Relations are kept in-tact.
   References to siblings will also be referenced between cloned siblings.
6. Cloning does not cause recursion errors when object pairs reference each other.

# Proof of concept

The cloner creates deep copies of objects, recognizing references and capable of
properly handling recursive self-references.

```php
<?php
// Define the timezone for Holland and Amsterdam during winter.
$winter = new stdClass();
$winter->amsterdam = new stdClass();
// Holland inherits from Amsterdam.
$winter->holland = $winter->amsterdam;

// Set the timezone offset for Amsterdam.
$winter->amsterdam->offset = '+1';

// Create a deep clone of the timezone to set the summer configuration.
$summer = 🐑($winter);

// Set the offset for Holland.
$summer->holland->offset = '+2';

echo json_encode(
    [
        'winter' => $winter,
        'summer' => $summer
    ],
    JSON_PRETTY_PRINT
) . PHP_EOL;
```

The code above will output:

```json
{
    "winter": {
        "amsterdam": {
            "offset": "+1"
        },
        "holland": {
            "offset": "+1"
        }
    },
    "summer": {
        "amsterdam": {
            "offset": "+2"
        },
        "holland": {
            "offset": "+2"
        }
    }
}
```

This proves that `holland` and `amsterdam` are both the same objects and when
performing a deep copy, both `amsterdam` and `holland` no longer affect the child
objects of the original timezone, yet they both still point to the same object.

This is achieved by checking whether a given object already has a clone, reusing
the clone when available.
