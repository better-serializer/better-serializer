# BetterSerializer (PHP)

## Usage

To use the serializer, install it using Composer:

```bash
$ composer require better-serializer/better-serializer
```

Since the project started to grow quite fast, it was impossible to work with it without a dependency injection container.
As the development DI container, [Pimple](https://pimple.symfony.com/) was chosen, because of its simplicity.

The serializer can be instantiated the following way:

```php
use use BetterSerializer\Serializer;

$builder = new Builder();

// to use APCu cache, run:
$builder->enableApcuCache();

// alternatively, to be able to use file cache, run:
$builder->setCacheDir('/path/to/directory');

// serializer instantiation:
$serializer = $builder->createSerializer();
```

When you obtain the serializer instance, you can use it the following way:

```php
use BetterSerializer\Common\SerializationType;

// serialization
$serializer->serialize($data, SerializationType::JSON());

// deserialization
$serializer->deserialize($jsonString, $type, SerializationType::JSON());
```
